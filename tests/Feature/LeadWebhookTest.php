<?php

namespace Tests\Feature;

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LeadWebhookTest extends TestCase
{
    use RefreshDatabase;

    private const SECRET = 'test-webhook-secret';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.lead_webhook.secret' => self::SECRET]);
    }

    /**
     * @param array<int, array<string, mixed>> $leads
     */
    private function postLeads(array $leads, ?string $secret = self::SECRET): \Illuminate\Testing\TestResponse
    {
        $headers = $secret === null ? [] : ['X-Webhook-Secret' => $secret];

        return $this->postJson(route('webhooks.leads'), ['leads' => $leads], $headers);
    }

    public function test_missing_secret_is_rejected(): void
    {
        $this->postJson(route('webhooks.leads'), ['leads' => [['Company Name' => 'X']]])
            ->assertUnauthorized();

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_wrong_secret_is_rejected(): void
    {
        $this->postLeads([['Company Name' => 'X']], 'nope')
            ->assertUnauthorized();

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_unconfigured_secret_rejects_all_requests(): void
    {
        config(['services.lead_webhook.secret' => null]);

        $this->postLeads([['Company Name' => 'X']], null)
            ->assertUnauthorized();
    }

    public function test_validation_requires_leads_array(): void
    {
        $this->postJson(route('webhooks.leads'), [], ['X-Webhook-Secret' => self::SECRET])
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('leads');
    }

    public function test_valid_payload_inserts_lead_with_spreadsheet_headers(): void
    {
        $response = $this->postLeads([[
            'Company Name' => 'Acme Co',
            'Email' => 'acme@test.com',
            'Phone' => '12345',
            'Source' => 'Instagram',
            'Source Handle' => '@acme',
            'Status' => 'New',
            'Lead Score (0-100)' => 77,
            'Has SSL' => 'Yes',
            'Mobile Friendly' => 'No',
        ]]);

        $response->assertOk()->assertJson([
            'message' => 'Leads synced.',
            'imported' => 1,
            'updated' => 0,
            'skipped' => 0,
        ]);

        $this->assertDatabaseHas('leads', [
            'name' => 'Acme Co',
            'email' => 'acme@test.com',
            'source' => 'Instagram',
            'source_handle' => '@acme',
            'status' => 'new',
            'lead_score' => 77,
            'has_ssl' => true,
            'mobile_friendly' => false,
        ]);
    }

    public function test_accepts_snake_case_column_keys(): void
    {
        $this->postLeads([[
            'name' => 'Snake Co',
            'email' => 'snake@test.com',
            'source' => 'Referral',
            'has_whatsapp' => 'Yes',
        ]])->assertOk();

        $this->assertDatabaseHas('leads', [
            'name' => 'Snake Co',
            'source' => 'Referral',
            'has_whatsapp' => true,
        ]);
    }

    public function test_resending_same_lead_updates_instead_of_duplicating(): void
    {
        $this->postLeads([[
            'Company Name' => 'Dup Co',
            'Email' => 'dup@test.com',
            'Lead Score (0-100)' => 50,
        ]])->assertOk();

        $this->postLeads([[
            'Company Name' => 'Dup Co',
            'Email' => 'dup@test.com',
            'Lead Score (0-100)' => 90,
        ]])->assertOk()->assertJson(['imported' => 0, 'updated' => 1]);

        $this->assertDatabaseCount('leads', 1);
        $this->assertDatabaseHas('leads', ['email' => 'dup@test.com', 'lead_score' => 90]);
    }

    public function test_rows_without_name_are_skipped(): void
    {
        $this->postLeads([
            ['Email' => 'noname@test.com'],
            ['Company Name' => 'Has Name', 'Source' => 'Instagram'],
        ])->assertOk()->assertJson(['imported' => 1, 'skipped' => 1]);

        $this->assertDatabaseCount('leads', 1);
    }

    public function test_source_defaults_to_webhook_when_missing(): void
    {
        $this->postLeads([['Company Name' => 'No Source Co']])->assertOk();

        $this->assertDatabaseHas('leads', [
            'name' => 'No Source Co',
            'source' => 'Webhook',
        ]);
    }

    public function test_endpoint_is_rate_limited(): void
    {
        RateLimiter::clear('');

        for ($i = 0; $i < 60; $i++) {
            $this->postLeads([['Company Name' => "Co {$i}", 'Email' => "c{$i}@test.com"]])
                ->assertOk();
        }

        $this->postLeads([['Company Name' => 'Over Limit']])
            ->assertStatus(429);
    }
}
