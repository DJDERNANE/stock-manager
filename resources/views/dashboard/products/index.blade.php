@extends('layouts.base')
@section('title', 'Products - ' . config('app.name'))
@section('content')

<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);
        padding: 1.5rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .custom-table {
        margin: 0;
    }

    .custom-table thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem 1.5rem;
        border: none;
        border-bottom: 2px solid #e2e8f0;
    }

    .custom-table tbody tr {
        transition: all 0.2s;
        border-bottom: 1px solid #f1f5f9;
    }

    .custom-table tbody tr:hover {
        background-color: #f8fafc;
        transform: scale(1.01);
    }

    .custom-table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        color: #334155;
    }

    .product-image {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .product-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .product-category {
        font-size: 0.85rem;
        color: #64748b;
    }

    .badge-status {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-in-stock {
        background-color: #dcfce7;
        color: #166534;
    }

    .badge-low-stock {
        background-color: #fef3c7;
        color: #92400e;
    }

    .badge-out-stock {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .price {
        font-size: 1.1rem;
        font-weight: 700;
        color: #667eea;
    }

    .action-btn {
        padding: 0.4rem 0.6rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        margin: 0 0.2rem;
    }

    .btn-edit {
        background-color: #e0e7ff;
        color: #4338ca;
    }

    .btn-edit:hover {
        background-color: #c7d2fe;
    }

    .btn-delete {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .btn-delete:hover {
        background-color: #fecaca;
    }

    .btn-restock {
        background-color: #d1fae5;
        color: #065f46;
    }

    .btn-restock:hover {
        background-color: #a7f3d0;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding-left: 2.5rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .filter-select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
    }

    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        border-bottom: 2px solid #e2e8f0;
        padding: 1.5rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: 2px solid #e2e8f0;
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.75rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
</style>

<div class="table-container">
    <div class="table-header">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" placeholder="Search products...">
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2 justify-content-md-end items-center">
                    <select class="filter-select">
                        <option>All Status</option>
                        <option>In Stock</option>
                        <option>Low Stock</option>
                        <option>Out of Stock</option>
                    </select>

                    <a href="{{ route('products.create') }}">
                        <button class="btn btn-primary border-0 py-2 px-4">
                            Add Product
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <table class="table custom-table">
        <thead>
            <tr>
                <th scope="col">
                    <input type="checkbox" class="form-check-input">
                </th>
                <th scope="col">Product</th>
                <th scope="col">Price</th>
                <th scope="col">Stock</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
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
                    <button class="action-btn btn-edit" title="Edit">
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
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="table-header border-top">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
            </div>
            <nav>
                {{ $products->links() }}
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
    deleteModal.addEventListener('show.bs.modal', function (event) {
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
    
    restockModal.addEventListener('show.bs.modal', function (event) {
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
    restockModal.addEventListener('hidden.bs.modal', function () {
        document.getElementById('restockForm').reset();
        document.getElementById('newQuantityPreview').style.display = 'none';
    });
</script>

@endsection