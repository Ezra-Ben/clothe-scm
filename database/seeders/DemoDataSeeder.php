<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\{Customer, Order, Carrier, Vendor, Supplier, User, SupplierOrder};

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Create or retrieve the user
        $user = User::firstOrCreate(
            ['email' => 'jane.supplier@example.com'],
            [
                'name' => 'Jane Supplier',
                'email_verified_at' => now(),
                'password' => Hash::make('securepass456'),
                'remember_token' => Str::random(10),
            ]
        );

        $userId = $user->id;

        // ✅ Create or retrieve the customer with user_id
        $customer = Customer::firstOrCreate(
            ['email' => 'jane@example.com'],
            [
                'name' => 'Jane Doe',
                'phone' => '0700123456',
                'shipping_address' => '123 Kampala Road, Kampala',
                'billing_address' => '123 Kampala Road, Kampala',
                'user_id' => $userId,
            ]
        );

        // ✅ Create multiple orders for the same customer
        for ($i = 1; $i <= 5; $i++) {
            Order::create([
                'customer_id' => $customer->id,
                'total_amount' => rand(10000, 50000) / 100,
                'status' => 'pending',
                'notes' => 'Order #' . $i,
                'payment_method' => 'cash',
                'package_weight_kg' => rand(1, 10),
                'dimensions_cm' => json_encode([
                    'length' => rand(10, 50),
                    'width' => rand(10, 50),
                    'height' => rand(10, 50),
                ]),
            ]);
        }

        // ✅ Create vendors linked to the user
        Vendor::create([
            'name' => 'Global Supplies Ltd',
            'business_name' => 'Global Trading Co.',
            'registration_number' => 'REG123456',
            'contact' => '+256700123456',
            'product_category' => 'Electronics',
            'business_license_url' => 'licenses/global_supplies.pdf',
            'user_id' => $userId,
        ]);

        Vendor::create([
            'name' => 'AgriCorp Uganda',
            'business_name' => 'AgriCorp Enterprises',
            'registration_number' => 'REG654321',
            'contact' => '+256781234567',
            'product_category' => 'Agriculture',
            'business_license_url' => 'licenses/agricorp.pdf',
            'user_id' => $userId,
        ]);

        // ✅ Create a supplier linked to first vendor
        $vendor = Vendor::first()?->id;

        Supplier::create([
            'vendor_id' => $vendor,
            'address' => '123 Kampala Road, Uganda',
            'added_by' => $userId,
            'name' => 'Kampala Supplies',
            'contact_person' => 'John Doe',
            'email' => 'kampala@example.com',
            'phone' => '+256770123456',
            'lead_time_days' => 5,
            'contract_terms' => 'Delivery within 5 working days.',
        ]);

        $supplier = Supplier::first()?->id;

        // ✅ Create a supplier order
        SupplierOrder::create([
            'supplier_id' => $supplier,
            'order_date' => Carbon::now()->subDays(10),
            'expected_delivery_date' => Carbon::now()->addDays(15),
            'status' => 'ordered',
            'total_amount' => 4500.00
        ]);

        // ✅ Insert carriers
        Carrier::insert([
            [
                'name' => 'DHL Uganda',
                 'user_id' => $userId,
                'code' => 'DHLUG',
                'contact_phone' => '0786544677',
                'supported_service_levels' => json_encode(['standard', 'express']),
                'service_areas' => json_encode(['Kampala', 'Jinja']),
                'base_rate_usd' => 5.00,
                'max_weight_kg' => 25.00,
                'tracking_url_template' => 'https://dhl.ug/track/{tracking_number}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Posta Uganda',
                'user_id' => $userId,
                'code' => 'POSTAUG',
                'contact_phone' => '0876454373',
                'supported_service_levels' => json_encode(['standard']),
                'service_areas' => json_encode(['Kampala', 'Gulu']),
                'base_rate_usd' => 3.00,
                'max_weight_kg' => 10.00,
                'tracking_url_template' => 'https://posta.ug/track/{tracking_number}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FedEx East Africa',
                'user_id' => $userId,
                'code' => 'FEDEXEA',
                'contact_phone' => '748993843',
                'supported_service_levels' => json_encode(['express']),
                'service_areas' => json_encode(['Kampala', 'Nairobi']),
                'base_rate_usd' => 7.00,
                'max_weight_kg' => 30.00,
                'tracking_url_template' => 'https://fedex.com/track/{tracking_number}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
