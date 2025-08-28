<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\Http;

class LogController extends Controller {
    public function getLogs() {
        return response()->json(Log::orderBy('last_scanned', 'desc')->get());
    }

    public function scanBarcode(Request $request) {
        $validated = $request->validate([
            'sku' => 'required|string',
            'location' => 'required|string',
            'status' => 'required|string|in:IN,OUT'
        ]);

        // Send to Flask
        $resp = Http::post('http://127.0.0.1:5001/rfid-scan', $validated);

        // Save to DB after Flask returns payload
        if ($resp->successful()) {
            $data = $resp->json()['data'];
            $log = Log::create($data);

            return response()->json([
                'message' => 'RFID scanned & stored successfully',
                'data' => [
                    'sku' => $log->sku,
                    'location' => $log->location,
                    'status' => $log->status,
                    'last_scanned' => $log->last_scanned,
                ]
                ]);
        }

        return response()->json([
            'message' => 'Failed to process RFID scan',
            'error' => $resp->json()
        ], $resp->status());
    }
}
