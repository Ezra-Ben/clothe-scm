<x-guest-layout>
    <form method="POST" action="{{ route('select.role.store') }}">
        @csrf

        <div>
            <x-input-label for="role" :value="__('Select Role')" />
            <select id="role" name="role" class="block mt-1 w-full form-select" required>
                <option value="">Choose a role...</option>
                <option value="customer">Customer</option>
                <option value="carrier">Carrier</option>
                <option value="supplier">Supplier</option>
                <!-- Add more roles as needed -->
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Continue') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>