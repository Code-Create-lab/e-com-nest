<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_into_the_admin_flow(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/dashboard');
    }

    public function test_guest_is_redirected_to_login_when_visiting_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_create_a_customer(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('customers.store'), [
                'name' => 'Acme Corp',
                'email' => 'team@acme.test',
                'phone' => '9999999999',
                'company_name' => 'Acme',
                'address' => 'Main Street',
            ]);

        $response->assertRedirect(route('customers.index'));

        $this->assertDatabaseHas('customers', [
            'name' => 'Acme Corp',
            'email' => 'team@acme.test',
            'company_name' => 'Acme',
        ]);
    }
}
