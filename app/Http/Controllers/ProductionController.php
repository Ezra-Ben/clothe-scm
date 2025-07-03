<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use App\Models\ProductionOrder;
// use App\Jobs\StartProductionProcess; // If you want to offload heavy processing to a job


class ProductionController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
           $expectedToken = config('services.pms.token'); // Store in config/services.php
        $receivedToken = $request->header('X-PMS-Token');

        if (!$receivedToken || $receivedToken !== $expectedToken) {
            Log::warning('Unauthorized webhook attempt to /api/start-production', ['ip' => $request->ip()]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // --- Validation (from your screenshot) ---
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'order_id' => 'required|integer', // This looks like an ID from the *Inventory* system
        ]);

        try {
            // --- Create the Production Order (from your screenshot) ---
            $job = ProductionOrder::create([ // Assuming ProductionJob is your ProductionOrder model
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity'],
                'order_id' => $validatedData['order_id'], // This links back to the inventory system's order
                'status' => 'Pending', // Initial status
                // Add other fields relevant to your production_orders table
                // e.g., 'batch_code' => generate_unique_batch_code(),
                // 'scheduled_at' => now(),
            ]);

            // --- Start Production Process (Queue if complex) ---
            // If starting production is a heavy task, dispatch a job:
            // StartProductionProcess::dispatch($job->id);

            Log::info('Production job created:', ['job_id' => $job->id, 'data' => $validatedData]);

            return response()->json([
                'status' => 'production_started',
                'job_id' => $job->id
            ], 200); // 200 OK is standard for successful webhook receipt

        } catch (\Exception $e) {
            Log::error('Error creating production job from webhook: ' . $e->getMessage(), [
                'payload' => $request->all(),
                'exception' => $e
            ]);
            return response()->json(['message' => 'Error processing production request: ' . $e->getMessage()], 500);
        }
    }
public function completeProduction($jobId) // Or receive Request if via API endpoint
{
    $job = ProductionOrder::findOrFail($jobId); // Assuming ProductionJob is ProductionOrder

    // Update the job status
    $job->status = 'Completed';
    $job->completed_at = now(); // Add this if you track completion time
    $job->save();

    // --- Send Webhook to Inventory System ---
    $inventoryWebhookUrl = config('services.inventory.webhook_url');
    $inventoryWebhookToken = config('services.inventory.token');

    if (!$inventoryWebhookUrl) {
        Log::warning('INVENTORY_WEBHOOK_URL not set in config/services.php. Cannot notify Inventory.');
        // Return an error response or just log and continue, depending on criticality
        return response()->json(['message' => 'Production completed, but Inventory URL not configured.'], 500);
    }

    $payload = [
        'product_id' => $job->product_id,
        'quantity_produced' => $job->quantity, // Assuming 'quantity' is the produced quantity
        'order_id' => $job->order_id, // This is the ID from the Inventory system's original request
        'production_job_id' => $job->id, // Add this so Inventory can reference your internal job ID
        'completed_at' => $job->completed_at->toIso8601String(),
        // Add any other data Inventory needs
    ];

    try {
        $response = Http::withHeaders([
            'X-PMS-Token' => $inventoryWebhookToken, // Token for Inventory to verify Production
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($inventoryWebhookUrl, $payload);

        if ($response->successful()) {
            Log::info('Successfully notified Inventory of production completion.', ['job_id' => $job->id, 'response' => $response->json()]);
            return response()->json(['message' => 'Production completed and Inventory notified.']);
        } else {
            Log::error('Failed to notify Inventory of production completion.', [
                'job_id' => $job->id,
                'status' => $response->status(),
                'response_body' => $response->body()
            ]);
            return response()->json(['message' => 'Production completed, but failed to notify Inventory.'], 500);
        }
    } catch (\Exception $e) {
        Log::error('Exception while sending production completion webhook to Inventory: ' . $e->getMessage(), [
            'job_id' => $job->id,
            'exception' => $e
        ]);
        return response()->json(['message' => 'Production completed, but exception occurred notifying Inventory.'], 500);
    }
}
     public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
