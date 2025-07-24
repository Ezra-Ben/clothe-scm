@extends('layouts.app')

@section('content')

@push('scripts')
    <script src="{{ asset('js/vendor-form.js') }}"></script>
@endpush

<div class="container">
<h2>Register as Vendor</h2>
  @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif

  <div class="form-box">
         
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
     
             <!-- Business License URL -->
        <div class="mb-3">
            <input name="business_license_url" value="{{ old('business_license_url') }}"
                   class="form-control @error('business_license_url') is-invalid @enderror"
                   placeholder="Business License URL" required>
            @error('business_license_url') <small class="text-danger">{{ $message }}</small> @enderror
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
                <option value="">-- Select Raw Material Category --</option>
                <option value="electronics" {{ old('product_category') == 'electronics' ? 'selected' : '' }}>Cotton Fabric</option>
                <option value="clothing" {{ old('product_category') == 'clothing' ? 'selected' : '' }}>Organic cotton</option>
                <option value="furniture" {{ old('product_category') == 'furniture' ? 'selected' : '' }}>Denim Fabric</option>
                <option value="electronics" {{ old('product_category') == 'electronics' ? 'selected' : '' }}>Linen Fabric</option>
                <option value="clothing" {{ old('product_category') == 'clothing' ? 'selected' : '' }}>Buttons</option>
                <option value="furniture" {{ old('product_category') == 'furniture' ? 'selected' : '' }}>Zippers</option>
                <option value="electronics" {{ old('product_category') == 'electronics' ? 'selected' : '' }}>Threads</option>
                <option value="clothing" {{ old('product_category') == 'clothing' ? 'selected' : '' }}>Bands</option>
                <option value="furniture" {{ old('product_category') == 'furniture' ? 'selected' : '' }}>Packaging Bags</option>
            </select>
            @error('product_category') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
       
         <div class="mb-3">
            <input name="product_bulk" value="{{ old('product_bulk') }}"
                   class="form-control @error('product_bulk') is-invalid @enderror"
                   placeholder="Product Bulk" required>
            @error('product_bulk') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

               <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</div>
@endsection