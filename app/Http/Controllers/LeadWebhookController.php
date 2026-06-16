<?php

namespace App\Http\Controllers;

use App\Services\LeadImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadWebhookController extends Controller
{
    /**
     * Receive leads pushed from another project and upsert them.
     *
     * Auth: send the shared secret in the `X-Webhook-Secret` header.
     * Body: { "leads": [ { "Company Name": "...", "Email": "...", ... } ] }
     * Keys may be spreadsheet headers ("Company Name") or column names ("name").
     */
    public function __invoke(Request $request, LeadImportService $leadImportService): JsonResponse
    {
        $secret = (string) config('services.lead_webhook.secret');

        if ($secret === '' || ! hash_equals($secret, (string) $request->header('X-Webhook-Secret'))) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $data = $request->validate([
            'leads' => ['required', 'array', 'min:1'],
            'leads.*' => ['array'],
        ]);

        $result = $leadImportService->syncRecords($data['leads']);

        return response()->json([
            'message' => 'Leads synced.',
            'imported' => $result['imported'],
            'updated' => $result['updated'],
            'skipped' => $result['skipped'],
        ]);
    }
}
