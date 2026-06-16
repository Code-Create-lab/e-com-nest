<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class LeadImportService
{
    /**
     * Map of normalized spreadsheet headers to lead attributes.
     *
     * @var array<string, string>
     */
    private const COLUMN_MAP = [
        'company name' => 'name',
        'name' => 'name',
        'website' => 'website',
        'email' => 'email',
        'phone' => 'phone',
        'city' => 'city',
        'industry' => 'industry',
        'source' => 'source',
        'source handle' => 'source_handle',
        'followers' => 'followers',
        'bio' => 'bio',
        'lead score (0-100)' => 'lead_score',
        'lead score' => 'lead_score',
        'score reason' => 'score_reason',
        'status' => 'status',
        'best pick' => 'best_pick',
        'audit score (0-100)' => 'audit_score',
        'audit score' => 'audit_score',
        'has ssl' => 'has_ssl',
        'mobile friendly' => 'mobile_friendly',
        'page speed (ms)' => 'page_speed_ms',
        'has contact form' => 'has_contact_form',
        'has whatsapp' => 'has_whatsapp',
        'is ecommerce' => 'is_ecommerce',
        'audit issues' => 'audit_issues',
        'audit summary' => 'audit_summary',
        'notes' => 'notes',
        'added on' => 'created_at',
        'last updated' => 'updated_at',
    ];

    private const INTEGER_FIELDS = ['followers', 'lead_score', 'audit_score', 'page_speed_ms'];

    private const BOOLEAN_FIELDS = ['best_pick', 'has_ssl', 'mobile_friendly', 'has_contact_form', 'has_whatsapp', 'is_ecommerce'];

    private const DATE_FIELDS = ['created_at', 'updated_at'];

    /**
     * Import leads from an Excel file. Rows matching an existing lead
     * (same name and source handle, or same email) are updated.
     *
     * @return array{imported: int, updated: int, skipped: int}
     */
    public function import(string $path): array
    {
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);

        $worksheet = $reader->load($path)->getActiveSheet();
        $rows = $worksheet->toArray(null, true, false);

        if (count($rows) < 2) {
            return ['imported' => 0, 'updated' => 0, 'skipped' => 0];
        }

        $attributeByColumn = $this->mapHeaders(array_shift($rows));

        $result = ['imported' => 0, 'updated' => 0, 'skipped' => 0];

        DB::transaction(function () use ($rows, $attributeByColumn, &$result): void {
            foreach ($rows as $row) {
                $attributes = $this->mapRow($row, $attributeByColumn);

                if (blank($attributes['name'] ?? null)) {
                    $result['skipped']++;

                    continue;
                }

                $attributes['source'] = $attributes['source'] ?? 'Import';

                $existing = $this->findExisting($attributes);

                if ($existing) {
                    $existing->fill($attributes)->save();
                    $result['updated']++;
                } else {
                    $lead = new Lead($attributes);
                    $lead->save();
                    $result['imported']++;
                }
            }
        });

        return $result;
    }

    /**
     * Upsert leads received from a webhook payload.
     *
     * Each record is an associative array keyed by spreadsheet header
     * ("Company Name") or by column name ("name"). Rows matching an
     * existing lead (same email, or same name + source handle) are updated.
     *
     * @param array<int, array<string, mixed>> $records
     * @return array{imported: int, updated: int, skipped: int}
     */
    public function syncRecords(array $records): array
    {
        $result = ['imported' => 0, 'updated' => 0, 'skipped' => 0];

        DB::transaction(function () use ($records, &$result): void {
            foreach ($records as $record) {
                if (! is_array($record)) {
                    $result['skipped']++;

                    continue;
                }

                $attributes = $this->mapRecord($record);

                // Require at least an email or an Instagram/source handle to identify the lead.
                if (blank($attributes['email'] ?? null) && blank($attributes['source_handle'] ?? null)) {
                    $result['skipped']++;

                    continue;
                }

                // name is NOT NULL; fall back to the handle or email when not supplied.
                if (blank($attributes['name'] ?? null)) {
                    $attributes['name'] = $attributes['source_handle'] ?? $attributes['email'];
                }

                $attributes['source'] = $attributes['source'] ?? 'Webhook';

                $existing = $this->findExisting($attributes);

                if ($existing) {
                    $existing->fill($attributes)->save();
                    $result['updated']++;
                } else {
                    (new Lead($attributes))->save();
                    $result['imported']++;
                }
            }
        });

        return $result;
    }

    /**
     * @param array<string, mixed> $record
     * @return array<string, mixed>
     */
    private function mapRecord(array $record): array
    {
        $fillable = (new Lead())->getFillable();
        $attributes = [];

        foreach ($record as $key => $value) {
            $normalized = Str::of((string) $key)->squish()->lower()->toString();

            $attribute = self::COLUMN_MAP[$normalized]
                ?? (in_array($snake = str_replace(' ', '_', $normalized), $fillable, true) ? $snake : null);

            if ($attribute === null) {
                continue;
            }

            if (is_string($value)) {
                $value = trim($value);
            }

            if ($value === null || $value === '' || $value === 'None') {
                continue;
            }

            $attributes[$attribute] = $this->castValue($attribute, $value);
        }

        return $attributes;
    }

    /**
     * @param array<int, mixed> $headerRow
     * @return array<int, string>
     */
    private function mapHeaders(array $headerRow): array
    {
        $map = [];

        foreach ($headerRow as $index => $header) {
            $normalized = Str::of((string) $header)->squish()->lower()->toString();

            if (isset(self::COLUMN_MAP[$normalized])) {
                $map[$index] = self::COLUMN_MAP[$normalized];
            }
        }

        return $map;
    }

    /**
     * @param array<int, mixed> $row
     * @param array<int, string> $attributeByColumn
     * @return array<string, mixed>
     */
    private function mapRow(array $row, array $attributeByColumn): array
    {
        $attributes = [];

        foreach ($attributeByColumn as $index => $attribute) {
            $value = $row[$index] ?? null;

            if (is_string($value)) {
                $value = trim($value);
            }

            if ($value === null || $value === '' || $value === 'None') {
                continue;
            }

            $attributes[$attribute] = $this->castValue($attribute, $value);
        }

        return $attributes;
    }

    private function castValue(string $attribute, mixed $value): mixed
    {
        if (in_array($attribute, self::INTEGER_FIELDS, true)) {
            return (int) $value;
        }

        if (in_array($attribute, self::BOOLEAN_FIELDS, true)) {
            return in_array(Str::lower((string) $value), ['yes', 'true', '1'], true);
        }

        if (in_array($attribute, self::DATE_FIELDS, true)) {
            return $this->castDate($value);
        }

        if ($attribute === 'status') {
            return $this->castStatus((string) $value);
        }

        return $value;
    }

    private function castDate(mixed $value): ?string
    {
        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d H:i:s');
        }

        $timestamp = strtotime((string) $value);

        return $timestamp === false ? null : date('Y-m-d H:i:s', $timestamp);
    }

    private function castStatus(string $value): LeadStatus
    {
        $normalized = Str::of($value)->squish()->lower()->replace([' ', '-'], '_')->toString();

        return LeadStatus::tryFrom($normalized) ?? LeadStatus::New;
    }

    private function findExisting(array $attributes): ?Lead
    {
        if (filled($attributes['email'] ?? null)) {
            $byEmail = Lead::query()->where('email', $attributes['email'])->first();

            if ($byEmail) {
                return $byEmail;
            }
        }

        if (filled($attributes['source_handle'] ?? null)) {
            return Lead::query()
                ->where('name', $attributes['name'])
                ->where('source_handle', $attributes['source_handle'])
                ->first();
        }

        return null;
    }
}
