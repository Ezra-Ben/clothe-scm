<?php

namespace App\Jobs;

use App\Models\OrderFulfillment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MarkProductionCompleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $fulfillment = OrderFulfillment::where('order_id', $this->orderId)->first();

        if ($fulfillment && $fulfillment->status === 'in_production') {
            $fulfillment->status = 'production_completed';
            $fulfillment->save();
        }
    }
}
