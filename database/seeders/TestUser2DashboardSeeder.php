<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\{User, Customer, Order, Delivery, Carrier};

class TestUser2DashboardSeeder extends Seeder
{
    public function run(): void
    {
        // Step 1: Use existing user (ID 3)
        $user = User::find(3);
        if (!$user) {
            $this->command->error("❌ User with ID 3 not found.");
            return;
        }

        // Step 2: Create a Customer for this user
        $customer = Customer::create([
            'user_id' => $user->id,
            'name' => 'Demo Customer',
            'email' => 'demo.customer@example.com',
            'phone' => '0755000000',
            'shipping_address' => 'Kira Road, Kampala',
            'billing_address' => 'Kira Road, Kampala',
        ]);

        // Step 3: Create an Order for the customer
        $order = Order::create([
            'customer_id' => $customer->id,
            'total_amount' => 200.00,
            'status' => 'processing',
            'notes' => 'Deliver ASAP',
            'payment_method' => 'mobile money',
            'package_weight_kg' => 5.2,
            'dimensions_cm' => json_encode([
                'length' => 35,
                'width' => 25,
                'height' => 18
            ]),
        ]);

        // Step 4: Use an existing Carrier
        $carrier = Carrier::first();
        if (!$carrier) {
            $this->command->error("❌ No carrier found. Please create one first.");
            return;
        }

        // Step 5: Create a Delivery with status 'pending'
        Delivery::create([
            'order_id' => $order->id,
            'carrier_id' => $carrier->id,
            'tracking_number' => 'TRACK' . strtoupper(Str::random(6)),
            'status' => 'pending',
            'service_level' => 'standard',
            'route' => json_encode(['Kampala', 'Entebbe']),
            'estimated_delivery' => Carbon::now()->addDays(4),
            'actual_delivery' => null,
            'notes' => 'Leave at front desk if not home',
        ]);

        $this->command->info('✅ Demo customer, order, and delivery with status `pending` seeded successfully.');
    }
}
