<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\{User, Customer, Order, Delivery, Carrier};
use Carbon\Carbon;

class TestUserDashboardSeeder extends Seeder
{
    public function run(): void
    {

        // Step 1: Use existing user (ID 3)
        $user = \App\Models\User::find(3);
        if (!$user) {
            $this->command->error("User with ID 3 not found.");
            return;
        }

        // Step 2: Create a Customer for this user
        $customer = Customer::create([
            'user_id' => $user->id,
            'name' => 'Test Customer',
            'email' => 'testcustomer@example.com',
            'phone' => '0700000000',
            'shipping_address' => 'Nakasero Hill, Kampala',
            'billing_address' => 'Nakasero Hill, Kampala',
        ]);

        // Step 3: Create an Order for the customer
        $order = Order::create([
            'customer_id' => $customer->id,
            'total_amount' => 150.00,
            'status' => 'processing',
            'notes' => 'Urgent delivery',
            'payment_method' => 'cash',
            'package_weight_kg' => 3.5,
            'dimensions_cm' => json_encode([
                'length' => 30,
                'width' => 20,
                'height' => 15
            ]),
        ]);

        // Step 4: Use existing Carrier (any)
        $carrier = Carrier::first();
        if (!$carrier) {
            $this->command->error("No carrier found. Please create one first.");
            return;
        }

        // Step 5: Create a Delivery for the order
        Delivery::create([
            'order_id' => $order->id,
            'carrier_id' => $carrier->id,
            'tracking_number' => 'TRACK' . Str::random(6),
            'status' => 'pending',
            'service_level' => 'standard',
            'route' => json_encode(['Kampala', 'Jinja']),
            'estimated_delivery' => Carbon::now()->addDays(3),
            'actual_delivery' => null,
            'notes' => 'Handle with care',
        ]);

        $this->command->info('✅ Test customer, order, and delivery seeded successfully.');
    }
}
