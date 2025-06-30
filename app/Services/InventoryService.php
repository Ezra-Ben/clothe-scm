<?php
namespace App\Services;

use App\Models\Product;
use App\Models\Inventory;
use App\Models\BOM;
use Illuminate\Support\Facades\Http;

class InventoryService
{
    public function checkProductExists($productId)
    {
        return Product::finished()->where('id', $productId)->exists();
    }

    public function hasEnoughStock($productId, $quantity)
    {
        $stock = Inventory::where('product_id', $productId)->value('quantity');
        return $stock !== null && $stock >= $quantity;
    }

    public function reserveStock($productId, $quantity)
    {
        $inventory = Inventory::where('product_id', $productId)->first();
        if ($inventory && $inventory->quantity >= $quantity) {
            $inventory->quantity -= $quantity;
            $inventory->save();
            return true;
        }
        return false;
    }
    public function canProduce($finishedProductId, $quantity)
{
    $bomItems = BOM::where('finished_product_id', $finishedProductId)->get();
    $missing = [];
    foreach ($bomItems as $bom) {
        $required = $bom->quantity * $quantity;
        $stock = Inventory::where('product_id', $bom->raw_material_id)->value('quantity');
        if ($stock < $required) {
            $missing[$bom->raw_material_id] = $required - $stock;
        }
    }
    return $missing; 
}

public function triggerProduction($finishedProductId, $quantity)
{
    
    Http::post('http://localhost:8000/api/start-production', [
        'product_id' => $finishedProductId,
        'quantity' => $quantity,
    ]);
}

    public function increaseStock($productId, $quantity)
    {
        $inventory = Inventory::firstOrCreate(['product_id' => $productId]);
        $inventory->quantity += $quantity;
        $inventory->save();
        return $inventory;
    }
}