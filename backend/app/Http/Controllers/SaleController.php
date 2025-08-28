<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\Http;

class SaleController extends Controller
{
    public function getSales()
    {
        return response()->json(Sale::orderBy('sale_id', 'desc')->get());
    }

    public function predictDemand(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string',
            'month' => 'required|string',
            'sales' => 'required|integer',
            'previous_month_number' => 'required|integer',
            'previous_month_sales' => 'required|integer',
        ]);

        // Forward to Flask for predicted demand
        $resp = Http::post('http://127.0.0.1:5001/predict-demand', $validated);

        if ($resp->successful()) {
            $data = $resp->json()['data'];
            $sale = Sale::create($data);

            return response()->json([
                'message' => 'Product demand predicted successfully',
                'data' => [
                    'sku' => $sale->sku,
                    'month' => $sale->month,
                    'current sales' => $sale->sales,
                    'predicted demand' => $sale->predicted_demand,
                ]
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to predict product demand',
            'error'   => $resp->json()
        ], $resp->status());
    }
}
