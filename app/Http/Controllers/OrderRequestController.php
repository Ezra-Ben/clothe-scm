<?php

namespace App\Http\Controllers;

use App\Models\OrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class OrderRequestController extends Controller
{
    /**
    
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $orders = OrderRequest::latest()->paginate(10); 
        return view('order_requests', compact('orders'));
    }

    /**
     * Show the form for creating a new order request.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
        return view('order_requests.create');
    }

    /**
     * Store a newly created order request in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'status' => 'required|string'
            // Add other fields you expect from the form
        ]);

        OrderRequest::create($validatedData);

        return redirect()->route('order_requests.index')->with('success', 'Order Request created successfully!');
    }

    /**
    
     *
     * @param  \App\Models\OrderRequest  $orderRequest  Laravel's Route Model Binding
     * @return \Illuminate\View\View
     */
    public function show(OrderRequest $orderRequest)
    {
        
        return view('order_requests.show', compact('orderRequest'));
    }

    /**
     * Show the form for editing the specified order request.
     * @param  \App\Models\OrderRequest  $orderRequest
     * @return \Illuminate\View\View
     */
    public function edit(OrderRequest $orderRequest)
    {
    
        return view('order_requests.edit', compact('orderRequest'));
    }

    /**
     * Update the specified order request in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderRequest  $orderRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, OrderRequest $orderRequest)
    {
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'status' => 'required|string',
            
        ]);

        $orderRequest->update($validatedData);

        return redirect()->route('order_requests.index')->with('success', 'Order Request updated successfully!');
    }

    /**
     * Remove the specified order request from storage.
     * @param  \App\Models\OrderRequest  $orderRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(OrderRequest $orderRequest)
    {
        $orderRequest->delete();

        return redirect()->route('order_requests.index')->with('success', 'Order Request deleted successfully!');
    }
}
