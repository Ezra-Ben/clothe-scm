<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorFormRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
	        'business_name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:vendors',
            'contact' => 'required|string|max:255',
            'product_bulk' => 'required|string',

            'previous_clients' => 'required|array|max:5',
       	    'previous_clients.*' => 'nullable|string|max:255',

      	    'transaction_history' => 'required|array|max:5',
            'transaction_history.*' => 'nullable|string|max:255',

     	    'industry_rating' => 'required|array|max:5',
     	    'industry_rating.*' => 'nullable|string|max:255',

            'product_category' => 'required|string',            		 	   
            'business_license_url' => 'required|url',

        ];
    }
}
