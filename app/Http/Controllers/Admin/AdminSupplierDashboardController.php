<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminSupplierDashboardController extends Controller
{
    public function index(Request $request)
    {
        if (Gate::denies('view-admin-supplier-dashboard')) {
            abort(403);
        }

        // Optional filtering
        $suppliers = Supplier::query();

        if ($request->filled('search')) {
            $suppliers->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $suppliers->where('status', $request->status);
        }

        return view('admin.supplier_dashboard', [
            'suppliers' => $suppliers->get()
        ]);
    }
}
