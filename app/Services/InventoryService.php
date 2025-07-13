<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Inventory;
use App\Models\OrderItem;
use App\Models\RawMaterial;


class InventoryService
{
    
    // Checks if the stock covers all order items.
    public function hasEnoughStock(Order $order)
    {
        foreach ($order->items as $item) {
            $stock = Inventory::where('product_id', $item->product_id)->value('quantity_on_hand');
            if ($stock < $item->quantity) {
                return false;
            }
        }
        return true;
    }

    // Reserves stock for the order by reducing the inventory quantity.
    public function reserveStock(Order $order)
    {  
        foreach ($order->items as $item) {
            $inventory = Inventory::where('product_id', $item->product_id)->first();
            if ($inventory && $inventory->quantity_on_hand >= $item->quantity) {
                $inventory->quantity_on_hand -= $item->quantity;
                $inventory->quantity_reserved += $item->quantity;
                $inventory->save();
            }
        }
       
    }

    public function hasEnoughRawMaterials(array $rawMaterials)
    {
        foreach ($rawMaterials as $rawMaterialId => $qtyNeeded) {
            $stock = RawMaterial::where('id', $rawMaterialId)->value('quantity_on_hand') ?? 0;
            if ($stock < $qtyNeeded) {
                return false;
            }
        }
        return true;
    }

    public function consumeRawMaterials(array $rawMaterials)
    {
        foreach ($rawMaterials as $rawMaterialId => $qty) {
            $material = RawMaterial::find($rawMaterialId);
            if ($material && $material->quantity_on_hand >= $qty) {
                $material->quantity_on_hand -= $qty;
                $material->save();
            } else {
                throw new \Exception("Not enough stock for raw material ID: $rawMaterialId");
            }
        }
    }

}
