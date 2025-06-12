<?php


namespace App\Http\Livewire;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\VendorService;

class VendorRegisterForm extends Component
{
    use WithFileUploads;

    // Basic Info
    public $company_name;
    public $registration_number;
    public $email;
    public $phone;
    public $address;

    // Supply Record
    public $previous_clients;
    public $transaction_history;
    public $industry_ratings;

    // Product Info
    public $product_categories;
    public $material_types;
    public $pricing;
    public $availability;

    // Compliance & Verification
    public $certifications;
    public $business_license;
    public $tax_id;

    // File Uploads
    public $application_letter;
    public $id_document;

    public function render()
    {
        return view('livewire.vendor-register-form');
    }

    public function submit()
    {
        $this->validate([
            // Basic Info
            'company_name'        => 'required|string|max:255',
            'registration_number' => 'required|string|max:100',
            'email'               => 'required|email',
            'phone'               => 'required|string|max:20',
            'address'             => 'required|string|max:255',

            // Supply Record
            'previous_clients'    => 'required|string',
            'transaction_history' => 'required|string',
            'industry_ratings'    => 'required|string',

            // Product Info
            'product_categories'  => 'required|string',
            'material_types'      => 'required|string',
            'pricing'             => 'required|string',
            'availability'        => 'required|string',

            // Compliance
            'certifications'      => 'required|string',
            'business_license'    => 'required|string',
            'tax_id'              => 'required|string',

            // File Uploads
            'application_letter'  => 'required|file|mimes:pdf|max:2048',
            'id_document'         => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // File Uploads
        $letterPath = $this->application_letter->store('application_letters', 'public');
        $idPath     = $this->id_document->store('id_documents', 'public');

        // Prepare Data to Pass to VendorService
        $vendorData = [
            'company_name'        => $this->company_name,
            'registration_number' => $this->registration_number,
            'email'               => $this->email,
            'phone'               => $this->phone,
            'address'             => $this->address,

            'previous_clients'    => $this->previous_clients,
            'transaction_history' => $this->transaction_history,
            'industry_ratings'    => $this->industry_ratings,

            'product_categories'  => $this->product_categories,
            'material_types'      => $this->material_types,
            'pricing'             => $this->pricing,
            'availability'        => $this->availability,

            'certifications'      => $this->certifications,
            'business_license'    => $this->business_license,
            'tax_id'              => $this->tax_id,

            'application_letter'  => $letterPath,
            'id_document'         => $idPath,
        ];

        // Call Business Logic Layer
        $result = VendorService::handleRegistration($vendorData);

        // Handle Response from Java Server
        if ($result['status'] === 'approved') {
            session()->flash('success', 'Vendor registered and validated successfully!');
            $this->reset();
        } else {
            $this->addError('validation', $result['message'] ?? 'Validation failed.');
        }
    }
}
