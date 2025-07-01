<?php
/*
namespace App\Services;

use Illuminate\Support\Facades\Http;

class ProductRecommendationService
{
    public function getRecommendedProductIds($customerId)
    {
        // 1. Fetch customer behavior from DB
        $customer = \DB::table('customers')->where('id', $customerId)->first();

        $payload = [
            'total_spent' => $customer->total_spent,
            'order_frequency' => $customer->order_frequency,
            'avg_order' => $customer->avg_order,
            'product_variety' => $customer->product_variety,
            // I can add more here or change
        ];

        // 2. Send POST to FastAPI
        $response = Http::post('http://localhost:8000/recommend', $payload);

        // 3. Return product IDs (array)
        return $response->json()['recommended_product_ids'] ?? [];
    }
}
*/