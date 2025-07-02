@extends('layouts.app')
<x-app-layout>
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">Inventory Dashboard</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Suppliers</h5>
                        <p class="card-text display-6">{{ $supplierCount ?? '--' }}</p>
                        <a href="/suppliers" class="btn btn-primary btn-sm">Manage Suppliers </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Manage Procurement Requests</h5>
                        <p class="card-text display-6">{{ $pendingProcurements ?? '--' }}</p>
                        <a href="/procurement/requests" class="btn btn-primary btn-sm">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Inventory Items</h5>
                        <p class="card-text display-6">{{ $inventoryCount ?? '--' }}</p>
                        <a href="/inventory" class="btn btn-primary btn-sm">Overview</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Manage Stock</h5>
                        <a href="{{ route('inventory.index') }}" class="btn btn-primary   btn-sm">Manage Stock</a>
                    </div>
                </div>
            </div> 
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Notifications</h5>
                        <p class="card-text display-6">{{ $notificationCount ?? '--' }}</p>
                        <a href="/notifications" class="btn btn-primary btn-sm">Check</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Order Requests</h5>
                        <a href="{{ route('inventory.order.requests') }}" class="btn btn-primary btn-sm">Manage Order Requests</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Manage Suppliers</h5>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-primary btn-sm">Manage Suppliers</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Manage Procurement</h5>
                        <a href="{{ route('procurement.requests.index') }}" class="btn btn-primary btn-sm">Manage Procurement</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>