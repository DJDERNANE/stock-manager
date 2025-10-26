@extends('layouts.base')
@section('title', 'Dashboard - ' . config('app.name'))
@section('content')

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1" style="font-size: 0.85rem;">Total Products</p>
                    <h3 class="mb-0" style="color: #667eea;">{{ $stats['total_products'] }}</h3>
                </div>
                <div style="width: 50px; height: 50px; background: #e0e7ff; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-box-seam" style="font-size: 1.5rem; color: #667eea;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card" style="border-left-color: #10b981;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1" style="font-size: 0.85rem;">In Stock</p>
                    <h3 class="mb-0" style="color: #10b981;">{{ $stats['in_stock'] }}</h3>
                </div>
                <div style="width: 50px; height: 50px; background: #d1fae5; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-check-circle" style="font-size: 1.5rem; color: #10b981;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card" style="border-left-color: #f59e0b;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1" style="font-size: 0.85rem;">Low Stock</p>
                    <h3 class="mb-0" style="color: #f59e0b;">{{ $stats['low_stock'] }}</h3>
                </div>
                <div style="width: 50px; height: 50px; background: #fef3c7; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-exclamation-triangle" style="font-size: 1.5rem; color: #f59e0b;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card" style="border-left-color: #ef4444;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1" style="font-size: 0.85rem;">Out of Stock</p>
                    <h3 class="mb-0" style="color: #ef4444;">{{ $stats['out_of_stock'] }}</h3>
                </div>
                <div style="width: 50px; height: 50px; background: #fee2e2; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-x-circle" style="font-size: 1.5rem; color: #ef4444;"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection