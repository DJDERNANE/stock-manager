@extends('layouts.base')
@section('title', 'Stock History - ' . $product->name)
@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-2">{{ $product->name }} Stock History</h2>
            <p class="mb-0 opacity-75">Track all stock movements and adjustments</p>
        </div>
        <a href="{{ route('products.index') }}" class="back-btn text-decoration-none">
            <i class="bi bi-arrow-left me-2"></i>Back to Products
        </a>
    </div>
</div>

<!-- Product Info Card -->
<div class="product-info-card">
    <div class="row align-items-center">
        <div class="col-auto">
            <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="product-image-large">
        </div>
        <div class="col">
            <h4 class="mb-1">{{ $product->name }}</h4>
            <p class="text-muted mb-0">SKU: {{ $product->sku }}</p>
        </div>
        <div class="col-auto">
            <div class="stat-card text-center">
                <div class="stat-value">{{ $product->quantity }}</div>
                <div class="stat-label">Current Stock</div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline -->
<div class="timeline-container">
    @if($stockHistory->count() > 0)
        <div class="timeline">
            @foreach($stockHistory as $history)
                <div class="timeline-item">
                    <div class="timeline-marker {{ $history->quantity_change > 0 ? 'marker-increase' : 'marker-decrease' }}">
                        @if($history->quantity_change > 0)
                            <i class="bi bi-arrow-up"></i>
                        @else
                            <i class="bi bi-arrow-down"></i>
                        @endif
                    </div>
                    
                    <div class="timeline-content {{ $history->quantity_change > 0 ? 'increase' : 'decrease' }}">
                        <div class="row align-items-center mb-3">
                            <div class="col">
                                <span class="adjustment-type type-{{ $history->adjustment_type }}">
                                    {{ $history->getAdjustmentTypeLabel() }}
                                </span>
                            </div>
                            <div class="col-auto">
                                <span class="quantity-badge {{ $history->quantity_change > 0 ? 'quantity-increase' : 'quantity-decrease' }}">
                                    {{ $history->quantity_change > 0 ? '+' : '' }}{{ $history->quantity_change }} units
                                </span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4">
                                <small class="text-muted d-block">Before</small>
                                <strong>{{ $history->quantity_before }} units</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">Change</small>
                                <strong class="{{ $history->quantity_change > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $history->quantity_change > 0 ? '+' : '' }}{{ $history->quantity_change }} units
                                </strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">After</small>
                                <strong>{{ $history->quantity_after }} units</strong>
                            </div>
                        </div>

                        <div class="row mt-3 pt-3 border-top">
                            <div class="col">
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i>
                                    By: {{ $history->adjuster->name ?? 'System' }}
                                </small>
                            </div>
                            <div class="col-auto">
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $history->created_at->format('d M Y, h:i A') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center">
            {{ $stockHistory->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #cbd5e1;"></i>
            <h5 class="mt-3 text-muted">No stock history available</h5>
            <p class="text-muted">Stock movements will appear here once adjustments are made.</p>
        </div>
    @endif
</div>

@endsection