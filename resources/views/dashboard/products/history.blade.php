@extends('layouts.base')
@section('title', 'Stock History - ' . $product->name)
@section('content')

<style>
    .page-header {
       
        
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .product-info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .product-image-large {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .timeline-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 2rem;
    }

    .timeline {
        position: relative;
        padding-left: 3rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -2.35rem;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        z-index: 1;
    }

    .marker-increase {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .marker-decrease {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .timeline-content {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.5rem;
        border-left: 3px solid #e2e8f0;
        transition: all 0.3s;
    }

    .timeline-content:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateX(5px);
    }

    .timeline-content.increase {
        border-left-color: #10b981;
        background: linear-gradient(to right, #ecfdf5 0%, #f8fafc 100%);
    }

    .timeline-content.decrease {
        border-left-color: #ef4444;
        background: linear-gradient(to right, #fef2f2 0%, #f8fafc 100%);
    }

    .adjustment-type {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .type-purchase { background-color: #dbeafe; color: #1e40af; }
    .type-sale { background-color: #fee2e2; color: #991b1b; }
    .type-return { background-color: #d1fae5; color: #065f46; }
    .type-damage { background-color: #fed7aa; color: #9a3412; }
    .type-loss { background-color: #fecaca; color: #7f1d1d; }
    .type-correction { background-color: #e0e7ff; color: #4338ca; }
    .type-transfer_in { background-color: #ddd6fe; color: #5b21b6; }
    .type-transfer_out { background-color: #fce7f3; color: #9f1239; }
    .type-adjustment { background-color: #fef3c7; color: #92400e; }

    .quantity-badge {
        font-size: 1.2rem;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        display: inline-block;
    }

    .quantity-increase {
        background-color: #dcfce7;
        color: #166534;
    }

    .quantity-decrease {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .stat-card {
        background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .back-btn {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .back-btn:hover {
        background: #667eea;
        color: white;
    }
</style>

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