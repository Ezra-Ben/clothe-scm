<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Vendor;

class VendorService
{
    public function handleRegistration(array $validated)
    {
        $payload = [
            'name' => $validated['name'],
	    'business_name' => $validated['business_name'],
            'registration_number' => $validated['registration_number'],
            'contact' => $validated['contact'],

             'previous_clients' => $validated['previous_clients'],
            'transaction_history' => $validated['transaction_history'],
            'industry_rating' => $validated['industry_rating'] ?? [],

            'product_category' => $validated['product_category'],
            'business_license_url' => $validated['business_license_url'],
        ];


        $response = Http::post('http://localhost:8080/api/vendor/validate', $payload);

        if ($response->successful() && $response->json('valid')) {
            
        Vendor::create([
                'name' => $validated['name'],
	 	'business_name' => $validated['business_name'],
                'registration_number' => $validated['registration_number'],
                'contact' => $validated['contact'],
                'product_category' => $validated['product_category'],
                'business_license_url' => $validated['business_license_url'],  
            ]);
            return ['success' => true, 'message' => 'Vendor registered successfully.'];
        }

        return ['success' => false, 'message' => $response->json('message') ?? 'Validation failed.'];
    }
}
