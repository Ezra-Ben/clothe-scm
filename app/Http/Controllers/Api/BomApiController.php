<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bom;
use Illuminate\Http\Request;

class BomApiController extends Controller
{
    public function show(Bom $bom)
    {
        $bom->load('product', 'bomItems.rawMaterial');
        return response()->json($bom);
    }
}
