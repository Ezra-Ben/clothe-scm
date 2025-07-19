<?php

namespace App\Jobs;
use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\OrderFulfillment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MarkReadyForShipping implements ShouldQueue
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

        if ($fulfillment && $fulfillment->status === 'production_completed') {
            $fulfillment->status = 'ready_for_shipping';
            $fulfillment->save();

            $order = $fulfillment->order; 
            $outboundShipment = app(\App\Services\OutboundShipmentService::class)->createForOrder($order);

            $logisticsManager = User::all()->first(function ($user) {
                return $user->hasRole('logistics_manager');
            });
    
            if ($logisticsManager) {
                $logisticsManager->notify(new \App\Notifications\OutboundShipmentCreatedNotification($outboundShipment));  
            }
        }
    }
}
