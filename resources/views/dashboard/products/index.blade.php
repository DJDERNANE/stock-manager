@extends('layouts.base')
@section('title', 'Products - ' . config('app.name'))
@section('content')

<div class="table-container">
    <div class="table-header">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
                <form method="GET" action="{{ route('products.index') }}" id="searchForm" class="search-form">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text"
                            class="form-control"
                            id="searchInput"
                            name="search"
                            placeholder="Search products..."
                            value="{{ request('search') }}"
                            onkeypress="handleKeyPress(event)">
                    </div>
                </form>
            </div>
            <div class="col-12 col-md-6">
                <div class="d-flex gap-2 justify-content-md-end justify-content-start flex-wrap">
                    <a href="{{ route('products.create') }}" class="w-100 w-md-auto">
                        <button class="btn btn-primary border-0 py-2 px-4 w-100">
                            <i class="bi bi-plus-circle d-md-none"></i>
                            <span class="d-none d-md-inline">Add Product</span>
                            <span class="d-md-none">Add</span>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="d-md-none">
        @foreach ($products as $product)
        <div class="card mobile-product-card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-2">
                        <img src="{{ asset('storage/' . $product->image_url) }}" alt="Product" class="product-image-mobile me-3">
                        <div>
                            <div class="product-name fw-bold">{{$product->name}}</div>
                            <div class="product-category small text-muted">SKU: {{$product->sku}}</div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary border-0" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <button class="dropdown-item" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#restockModal"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}"
                                    data-product-quantity="{{ $product->quantity }}">
                                    <i class="bi bi-box-seam me-2"></i>Adjust Stock
                                </button>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('products.edit', $product->id) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.history', $product->id) }}"><i class="bi bi-clock-history me-2"></i>History</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button class="dropdown-item text-danger" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}">
                                    <i class="bi bi-trash me-2"></i>Delete
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <div class="small text-muted">Price</div>
                        <div class="fw-bold price">{{$product->purchase_price}}</div>
                    </div>
                    <div class="col-4">
                        <div class="small text-muted">Stock</div>
                        <div class="fw-bold">{{$product->quantity}} units</div>
                    </div>
                    <div class="col-4">
                        <div class="small text-muted">Status</div>
                        <span class="badge-status 
                            @if($product->stock_status === 'Out of stock')
                                badge-out-stock
                            @elseif($product->stock_status === 'Low')
                                badge-low-stock
                            @else
                                badge-in-stock
                            @endif
                        ">
                            {{ $product->stock_status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Desktop Table View -->
    <div class="d-none d-md-block">
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th scope="col" width="50">
                            <input type="checkbox" class="form-check-input">
                        </th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Status</th>
                        <th scope="col" width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $product->image_url) }}" alt="Product" class="product-image me-3">
                                <div>
                                    <div class="product-name">{{$product->name}}</div>
                                    <div class="product-category">SKU: {{$product->sku}}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="price">{{$product->purchase_price}}</span></td>
                        <td>{{$product->quantity}} units</td>
                        <td>
                            <span class="badge-status 
                                @if($product->stock_status === 'Out of stock')
                                    badge-out-stock
                                @elseif($product->stock_status === 'Low')
                                    badge-low-stock
                                @else
                                    badge-in-stock
                                @endif
                            ">
                                {{ $product->stock_status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <button class="action-btn btn-restock" title="Adjust Stock"
                                    data-bs-toggle="modal"
                                    data-bs-target="#restockModal"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}"
                                    data-product-quantity="{{ $product->quantity }}">
                                    <i class="bi bi-box-seam"></i>
                                </button>
                                <a href="{{ route('products.edit', $product->id) }}">
                                    <button class="action-btn btn-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </a>
                                <a href="{{ route('products.history', $product->id) }}">
                                    <button class="action-btn btn-history" title="History">
                                        <i class="bi bi-clock-history"></i>
                                    </button>
                                </a>
                                <button class="action-btn btn-delete" title="Delete"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="table-header border-top">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="text-muted text-center text-md-start">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
            </div>
            <nav>
                <ul class="pagination mb-0 pagination-sm">
                    {{-- Previous Page Link --}}
                    @if ($products->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="bi bi-chevron-left"></i>
                        </span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @php
                    $current = $products->currentPage();
                    $last = $products->lastPage();
                    $start = max($current - 1, 1); // Show fewer pages on mobile
                    $end = min($current + 1, $last);
                    @endphp

                    @if($start > 1)
                    <li class="page-item d-none d-md-block">
                        <a class="page-link" href="{{ $products->url(1) }}">1</a>
                    </li>
                    @if($start > 2)
                    <li class="page-item disabled d-none d-md-block">
                        <span class="page-link">...</span>
                    </li>
                    @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i==$current)
                        <li class="page-item active">
                        <span class="page-link">{{ $i }}</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                        </li>
                        @endif
                        @endfor

                        @if($end < $last)
                            @if($end < $last - 1)
                            <li class="page-item disabled d-none d-md-block">
                            <span class="page-link">...</span>
                            </li>
                            @endif
                            <li class="page-item d-none d-md-block">
                                <a class="page-link" href="{{ $products->url($last) }}">{{ $last }}</a>
                            </li>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($products->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="bi bi-chevron-right"></i>
                                </span>
                            </li>
                            @endif
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to delete <strong id="deleteProductName"></strong>?</p>
                <p class="text-muted mt-2 mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Restock Modal -->
<div class="modal fade" id="restockModal" tabindex="-1" aria-labelledby="restockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="restockForm" method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="restockModalLabel">
                        <i class="bi bi-arrow-left-right text-primary me-2"></i>
                        Adjust Stock
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <input type="text" class="form-control" id="restockProductName" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Stock</label>
                        <input type="text" class="form-control" id="restockCurrentQuantity" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="adjustment_type" class="form-label">Adjustment Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="adjustment_type" name="adjustment_type" required>
                            <option value="">Select adjustment type</option>
                            @foreach(\App\Models\StockHistory::getAdjustmentTypes() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="quantity_change" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="quantity_change" name="quantity_change" min="1" required placeholder="Enter quantity">
                        <small class="text-muted">Enter positive number (will be adjusted based on type)</small>
                    </div>

                    <div class="mb-3" id="newQuantityPreview" style="display: none;">
                        <div class="alert alert-info mb-0">
                            <strong>New Stock Level:</strong> <span id="newQuantityValue">0</span> units
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        Adjust Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    // Delete Modal
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const productId = button.getAttribute('data-product-id');
        const productName = button.getAttribute('data-product-name');

        const deleteProductName = document.getElementById('deleteProductName');
        const deleteForm = document.getElementById('deleteForm');

        deleteProductName.textContent = productName;
        deleteForm.action = `/products/${productId}`;
    });

    // Restock Modal
    const restockModal = document.getElementById('restockModal');
    let currentQuantity = 0;

    // Increase types from StockHistory model
    const increaseTypes = ['purchase', 'return', 'transfer_in', 'correction'];
    const decreaseTypes = ['sale', 'damage', 'loss', 'transfer_out', 'correction'];

    restockModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const productId = button.getAttribute('data-product-id');
        const productName = button.getAttribute('data-product-name');
        const productQuantity = button.getAttribute('data-product-quantity');

        currentQuantity = parseInt(productQuantity);

        const restockProductName = document.getElementById('restockProductName');
        const restockCurrentQuantity = document.getElementById('restockCurrentQuantity');
        const restockForm = document.getElementById('restockForm');

        restockProductName.value = productName;
        restockCurrentQuantity.value = productQuantity + ' units';
        restockForm.action = `/products/${productId}/restock`;
    });

    // Calculate new quantity on input change
    function calculateNewQuantity() {
        const adjustmentType = document.getElementById('adjustment_type').value;
        const quantityChange = parseInt(document.getElementById('quantity_change').value) || 0;
        const newQuantityPreview = document.getElementById('newQuantityPreview');
        const newQuantityValue = document.getElementById('newQuantityValue');

        if (adjustmentType && quantityChange > 0) {
            let newQuantity;

            // Determine if increase or decrease based on adjustment type
            if (increaseTypes.includes(adjustmentType)) {
                newQuantity = currentQuantity + quantityChange;
                newQuantityPreview.querySelector('.alert').className = 'alert alert-success mb-0';
            } else if (decreaseTypes.includes(adjustmentType)) {
                newQuantity = currentQuantity - quantityChange;
                if (newQuantity < 0) {
                    newQuantityPreview.querySelector('.alert').className = 'alert alert-danger mb-0';
                    newQuantityValue.textContent = newQuantity + ' (Insufficient stock!)';
                    newQuantityPreview.style.display = 'block';
                    return;
                } else {
                    newQuantityPreview.querySelector('.alert').className = 'alert alert-warning mb-0';
                }
            }

            newQuantityValue.textContent = newQuantity;
            newQuantityPreview.style.display = 'block';
        } else {
            newQuantityPreview.style.display = 'none';
        }
    }

    // Add event listeners
    document.getElementById('adjustment_type').addEventListener('change', calculateNewQuantity);
    document.getElementById('quantity_change').addEventListener('input', calculateNewQuantity);

    // Reset form when modal is closed
    restockModal.addEventListener('hidden.bs.modal', function() {
        document.getElementById('restockForm').reset();
        document.getElementById('newQuantityPreview').style.display = 'none';
    });

    function handleKeyPress(event) {
        // Check if the pressed key is Enter (key code 13)
        if (event.keyCode === 13 || event.which === 13) {
            event.preventDefault(); // Prevent default form submission behavior
            document.getElementById('searchForm').submit();
        }
    }

    // Alternative approach using event listener (recommended)
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');

        if (searchInput && searchForm) {
            searchInput.addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    searchForm.submit();
                }
            });
        }

        // Initialize tooltips for mobile
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

@endsection