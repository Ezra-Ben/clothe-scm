<?php

namespace App\Services;

use App\Models\Contract;

class ContractService
{
    public function createContract(int $supplierId, array $data, int $addedBy): Contract
    {
        return Contract::create([
            'supplier_id'     => $supplierId,
            'contract_number' => $data['contract_number'],
            'start_date'      => $data['start_date'],
            'end_date'        => $data['end_date'],
            'status'          => $data['status'],
            'terms'           => $data['terms'],
            'payment_terms'   => $data['payment_terms'] ?? null,
            'renewal_date'    => $data['renewal_date'] ?? null,
            'added_by'        => $addedBy,
            'notes'           => $data['notes'] ?? null,
        ]);
    }

    public function updateContract(Contract $contract, array $data)
    {
        $contract->update($data);
    }
}
