<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ProductRecommendationService
{
    protected $segmentMap;

    public function __construct()
    {
        $this->segmentMap = json_decode(file_get_contents(storage_path('app/segment_to_products.json')), true);
    }

    public function getRecommendedProducts($customerId)
    {
        $segment = DB::table('customer_segments')
            ->where('customer_id', $customerId)
            ->orderByDesc('generated_at')
            ->first();

        if (!$segment) {
            return collect(); // Empty fallback
        }

        $segmentId = $segment->segment_id;

        $productIds = $this->segmentMap[$segmentId] ?? [];

        return DB::table('products')
            ->whereIn('id', $productIds)
            ->get();
    }
}
