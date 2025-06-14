Laravel Vendor Validation Flow (No DB Persistence for Array Fields)

Required Fields in Registration Form
                                        Persistence
Field	          	Type		Required	Notes
name			text		Yes		Business or individual name
registration_number	text		Yes		Unique identifier
contact			text		Yes		Phone number or email
previous_clients	textarea	No		Comma-separated; for validation only
transaction_history	textarea	No		Comma-separated; for validation only
industry_rating		textarea	No		Comma-separated; for validation only
product_category	select (single)	Yes		Must choose one category
business_license_url	text		Yes		URL to an online business_license

UI Layer – Form
Folder: resources/views/vendor/
File			Purpose
register.blade.php	HTML registration form for vendors

Sample of: resources/views/vendor/register.blade.php
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Register as Vendor</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('vendor.register') }}" method="POST">
        @csrf

        <!-- Static Fields -->
        <div class="mb-3">
            <input name="name" value="{{ old('name') }}"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="Vendor Name" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
 	<div class="mb-3">
            <input name="business_name" value="{{ old('business_name') }}"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="Business Name" required>
            @error('business_name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>


        <div class="mb-3">
            <input name="registration_number" value="{{ old('registration_number') }}"
                   class="form-control @error('registration_number') is-invalid @enderror"
                   placeholder="Registration Number" required>
            @error('registration_number') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <input name="contact" value="{{ old('contact') }}"
                   class="form-control @error('contact') is-invalid @enderror"
                   placeholder="Contact Info" required>
            @error('contact') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Previous Clients -->
        <div class="mb-3">
            <label>Previous Clients</label>
            <div id="previous-clients-wrapper">
                @php $clients = old('previous_clients', ['']); @endphp
                @foreach ($clients as $i => $client)
                    <div class="mb-1">
                        <input type="text" name="previous_clients[]" value="{{ $client }}"
                               class="form-control @error("previous_clients.$i") is-invalid @enderror"
                               placeholder="Client {{ $i + 1 }}">
                        @error("previous_clients.$i")
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                @endforeach
            </div>
            <button type="button"
                    onclick="addField('previous-clients-wrapper', 'previous_clients[]', 'Client')"
                    class="btn btn-sm btn-outline-secondary">+ Add Client</button>
        </div>

      <!--Trasaction History-->
        <div class="mb-3">
            <label>Transaction History</label>
            <div id="transaction-history-wrapper">
                @php $transactions = old('transaction_history', ['']); @endphp
                @foreach ($transactions as $i => $txn)
                    <div class="mb-1">
                        <input type="text" name="transaction_history[]" value="{{ $txn }}"
                               class="form-control @error("transaction_history.$i") is-invalid @enderror"
                               placeholder="Transaction {{ $i + 1 }}">
                        @error("transaction_history.$i")
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                @endforeach
            </div>
            <button type="button"
                    onclick="addField('transaction-history-wrapper', 'transaction_history[]', 'Transaction')"
                    class="btn btn-sm btn-outline-secondary">+ Add Transaction</button>
        </div>

      
<!--Industry rating-->
        <div class="mb-3">
            <label>Industry Rating (optional)</label>
            <div id="industry-rating-wrapper">
                @php $ratings = old('industry_rating', ['']); @endphp
                @foreach ($ratings as $i => $rating)
                    <div class="mb-1">
                        <input type="text" name="industry_rating[]" value="{{ $rating }}"
                               class="form-control @error("industry_rating.$i") is-invalid @enderror"
                               placeholder="Rating {{ $i + 1 }}">
                        @error("industry_rating.$i")
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                @endforeach
            </div>
            <button type="button"
                    onclick="addField('industry-rating-wrapper', 'industry_rating[]', 'Rating')"
                    class="btn btn-sm btn-outline-secondary">+ Add Rating</button>
        </div>

        <!-- Product Category -->
        <div class="mb-3">
            <select name="product_category"
                    class="form-select @error('product_category') is-invalid @enderror" required>
                <option value="">-- Select Product Category --</option>
                <option value="electronics" {{ old('product_category') == 'electronics' ? 'selected' : '' }}>Electronics</option>
                <option value="clothing" {{ old('product_category') == 'clothing' ? 'selected' : '' }}>Clothing</option>
                <option value="furniture" {{ old('product_category') == 'furniture' ? 'selected' : '' }}>Furniture</option>
            </select>
            @error('product_category') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Business License URL -->
        <div class="mb-3">
            <input name="business_license_url" value="{{ old('business_license_url') }}"
                   class="form-control @error('business_license_url') is-invalid @enderror"
                   placeholder="Business License URL" required>
            @error('business_license_url') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor-form.js') }}"></script>
@endpush




JavaScriptFile: public/js/vendor-form.js

function addField(wrapperId, fieldName, placeholderText) {
    const wrapper = document.getElementById(wrapperId);

    const input = document.createElement('input');
    input.type = 'text';
    input.name = fieldName;
    input.placeholder = placeholderText;
    input.classList.add('form-control', 'mb-1');

    wrapper.appendChild(input);
}



Application Layer- Controller

File: routes/web.php

use App\Http\Controllers\Vendor\VendorController;

Route::get('/vendor/register', [VendorController::class, 'showForm'])->name('vendor.form');
Route::post('/vendor/register', [VendorController::class, 'submitForm'])->name('vendor.register');


NB: Gihozo create a form request class(I want to separate the HTTP server-side validation from the controllers
and keep their purpose solely to handling HTTP requests and responses. Makes our code clean)

cmd: "php artisan make:request VendorFormRequest"


// app/Http/Requests/VendorFormRequest.php

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

            'previous_clients' => 'required|array|max:5',
       	    'previous_clients.*' => 'nullable|string|max:255',

      	    'transaction_history' => 'required|array|max:5',
            'transaction_history.*' => 'nullable|string|max:255',

     	   'industry_rating' => 'required|array|max:5',
     	   'industry_rating.*' => 'nullable|string|max:255',

            'product_category' => 'required|string',            		 	    'business_license_url' => 'required|url',
        ];
    }
}

Inject the request class in the controller:


File			Purpose
VendorController.php	Handles form requests

Sample: VendorController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VendorService;

class VendorController extends Controller
{
    public function showForm()
    {
        return view('vendor.register');
    }

    public function submitForm(VendorFormRequest $request)
    {    
        $validated = $request->validated();

        $result = app(VendorService::class)->validateAndRegister($validated);

        return $result['success']
            ? redirect()->back()->with('success', $result['message'])
            : redirect()->back()->withErrors(['error' => $result['message']]);
    }
}





Business Logic – Validation and External API

Folder: app/Services/
File			Purpose
VendorService.php	Prepares data, sends it to Java server

Sample: VendorService.php

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




Data Layer – Database Persistence
Folder: app/Models/
File		Purpose
Vendor.php	Eloquent ORM model

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
	'business_name',
        'registration_number',
        'contact',
        'product_category',
        'business_license_url',
    ];
}



Folder: database/migrations/
File					Purpose
xxxx_xx_xx_create_vendors_table.php	Table structure for vendors

Sample:Migration
public function up()
{
    Schema::create('vendors', function (Blueprint $table) {
        $table->id();
        $table->string('name');
	$table->string('business_name');
        $table->string('registration_number')->unique();
        $table->string('contact');
        $table->string('product_category');
        $table->string('business_license_url');
        $table->timestamps();
    });
}


