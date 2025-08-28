<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alert;
use Illuminate\Support\Facades\Http;

class AlertController extends Controller {
    public function getAlerts() {
        return response()->json(Alert::orderBy('created_at', 'desc')->get());
    }

    public function sensorAlert(Request $request) {
        $validated = $request->validate([
            'sku' => 'required|string',
            'stock' => 'required|integer',
        ]);

        $resp = Http::post('http://127.0.0.1:5001/sensor-alert', $validated);

        // Save to DB after Flask returns payload
        if ($resp->successful()) {
            $data = $resp->json()['data'];
            $alert = Alert::create($data);

            return response()->json([
                'message' => 'Sensor alert generated',
                'data' => [
                    'sku' => $alert->sku,
                    'stock' => $alert->stock,
                    'alert' => $alert->alert,
                    'created_at' => $alert->created_at,
                ]
            ], 201);
        }

        return response()->json([
            'message' => 'Failed to sensor alert',
            'error' => $resp->json()
        ], $resp->status());
    }
}