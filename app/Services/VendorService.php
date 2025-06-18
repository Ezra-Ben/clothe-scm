<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Vendor;

class VendorService
{
    public function validateAndRegister(array $validated)
    {
        $payload = [
            'name' => $validated['name'],
	    'businessName' => $validated['business_name'],
            'regNo' => $validated['registration_number'],
            'productBulk' => $validated['product_bulk'],

            'previousClients' => $validated['previous_clients'],
            'transactionHistory' => $validated['transaction_history'],
            'industryRatings' => $validated['industry_rating'] ?? [],

            'businessLicenseUrl' => $validated['business_license_url'],
        ];

                
        \Log::info('Outgoing request to Java Server:', ['payload' => $payload]);

        $response = Http::post('http://localhost:8080/api/validate-vendor', $payload);
        
        $responseData = $response->json();

        if ($response->successful() && ($responseData['valid'] ?? false)) {
            
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
        

           return [
               'success' => false,
               'errors' => [
               'server_validation' => $responseData['validationErrors'] ?? ['Validation failed from external server']
               ]
          ];  
    }
}

