<?php

namespace App\Http\Controllers\Supplier;

use App\Models\Supplier;
use App\Models\Contract;
use App\Http\Requests\StoreContractRequest;
use App\Services\ContractService;
use App\Http\Controllers\Controller;

class ContractController extends Controller
{
    protected $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;

    }
    
    public function index()
    {
	$id = request()->query('id');

        $supplier = $id
            ? Supplier::with(['contracts.addedBy'])->findOrFail($id)
            : auth()->user()->vendor->supplier()->with(['contracts.addedBy'])->first();


        $contracts = $supplier->contracts;

        return view('supplier.contracts.index', compact('supplier', 'contracts'));
    }

    public function show($contractId, $id = null)
    {
        $supplier = $id
            ? Supplier::with(['contracts.addedBy'])->findOrFail($id)
            : auth()->user()->vendor->supplier()->with(['contracts.addedBy'])->first();


        $contract = $supplier->contracts()->findOrFail($contractId);

        return view('supplier.contracts.show', compact('contract', 'supplier'));
    }

    public function create($id)
    {
        $supplier = Supplier::findOrFail($id);

        return view('supplier.contracts.create', compact('supplier'));
    }

    public function store(StoreContractRequest $request, $id)
    {
        try {
            $this->contractService->createContract(
                $id,
                $request->validated(), 
                auth()->id()
            );

            return redirect()
                ->route('manage.supplier.contracts.index', ['id' => $id])
                ->with('success', 'Contract created successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors('Failed to upload contract: ' . $e->getMessage());
        }
    }
    
    public function update(StoreContractRequest $request, $id = null, $contractId)
    {
    $supplier = $id
        ? Supplier::findOrFail($id)
        : auth()->user()->vendor->supplier;

    $contract = $supplier->contracts()->findOrFail($contractId);

    try {
        $this->contractService->updateContract($contract, $request->validated());

        return redirect()
            ->route('manage.supplier.contracts.show', $supplier->id)
            ->with('success', 'Contract updated successfully.');

    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->withErrors('Failed to update contract: ' . $e->getMessage());
    }
}

}
