<?php

namespace App\Services;

use App\Models\FinishedGoodsInventory;
use App\Models\RawMaterial;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Checks if a product is available in finished goods inventory for a given quantity.
     *
     * @param Product $product
     * @param int $quantity
     * @return bool
     */
    public function checkFinishedGoodsAvailability(Product $product, int $quantity): bool
    {
        $inventory = FinishedGoodsInventory::where('product_id', $product->id)->first();
        return $inventory && $inventory->quantity >= $quantity;
    }

    /**
     * Deducts a quantity of a finished product from inventory.
     *
     * @param Product $product
     * @param int $quantity
     * @return void
     * @throws \Exception
     */
    public function deductFinishedGoods(Product $product, int $quantity): void
    {
        DB::transaction(function () use ($product, $quantity) {
            $inventory = FinishedGoodsInventory::where('product_id', $product->id)->first();

            if (!$inventory || $inventory->quantity < $quantity) {
                throw new \Exception("Insufficient finished goods stock for product: " . $product->name);
            }

            $inventory->quantity -= $quantity;
            $inventory->save();
        });
    }

    /**
     * Adds a quantity of a finished product to inventory.
     * This would typically be called after production completion.
     *
     * @param Product $product
     * @param int $quantity
     * @return void
     */
    public function receiveFinishedGoods(Product $product, int $quantity): void
    {
        DB::transaction(function () use ($product, $quantity) {
            $inventory = FinishedGoodsInventory::firstOrNew(['product_id' => $product->id]);
            $inventory->quantity += $quantity;
            $inventory->save();
        });
    }

    /**
     * Checks if raw materials are available for a given BOM and production quantity.
     *
     * @param \App\Models\Bom $bom
     * @param int $productionQuantity
     * @return bool
     */
    public function checkRawMaterialAvailability(\App\Models\Bom $bom, int $productionQuantity): bool
    {
        $bom->load('bomItems.rawMaterial');
        foreach ($bom->bomItems as $bomItem) {
            $requiredQuantity = $bomItem->quantity * $productionQuantity;
            if ($bomItem->rawMaterial->quantity_in_stock < $requiredQuantity) {
                return false; // Not enough of this raw material
            }
        }
        return true;
    }

    /**
     * Deducts raw materials based on a BOM and production quantity.
     * This would typically be called when a production order starts.
     *
     * @param \App\Models\Bom $bom
     * @param int $productionQuantity
     * @return void
     * @throws \Exception
     */
    public function deductRawMaterials(\App\Models\Bom $bom, int $productionQuantity): void
    {
        DB::transaction(function () use ($bom, $productionQuantity) {
            $bom->load('bomItems.rawMaterial');
            foreach ($bom->bomItems as $bomItem) {
                $requiredQuantity = $bomItem->quantity * $productionQuantity;
                $rawMaterial = $bomItem->rawMaterial;

                if ($rawMaterial->quantity_in_stock < $requiredQuantity) {
                    throw new \Exception("Insufficient raw material stock for: " . $rawMaterial->name);
                }

                $rawMaterial->quantity_in_stock -= $requiredQuantity;
                $rawMaterial->save();
            }
        });
    }

    /**
     * Adds a quantity of raw material to inventory.
     *
     * @param RawMaterial $rawMaterial
     * @param float $quantity
     * @return void
     */
    public function receiveRawMaterials(RawMaterial $rawMaterial, float $quantity): void
    {
        DB::transaction(function () use ($rawMaterial, $quantity) {
            $rawMaterial->quantity_in_stock += $quantity;
            $rawMaterial->save();
        });
    }
}



