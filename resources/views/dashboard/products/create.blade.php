@extends('layouts.base')

@section('title', 'Add Product - ' . config('app.name'))

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0">Add New Product</h1>
            <p class="mb-0 opacity-75">Create a new product in your inventory</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('products.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>
</div>

<div class="form-container">
    <div class="form-header">
        <h5 class="mb-0">Product Information</h5>
        <p class="mb-0 text-muted">Fill in the details below to add a new product</p>
    </div>

    <div class="form-body">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf

            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="row">
                        <!-- Product Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label required-field">Product Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required
                                   placeholder="Enter product name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SKU -->
                        <div class="col-md-6 mb-3">
                            <label for="sku" class="form-label required-field">SKU</label>
                            <input type="text" 
                                   class="form-control @error('sku') is-invalid @enderror" 
                                   id="sku" 
                                   name="sku" 
                                   value="{{ old('sku') }}" 
                                   required
                                   placeholder="e.g., PROD-001">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Enter product description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Purchase Price -->
                        <div class="col-md-6 mb-3">
                            <label for="purchase_price" class="form-label required-field">Purchase Price ($)</label>
                            <input type="number" 
                                   class="form-control @error('purchase_price') is-invalid @enderror" 
                                   id="purchase_price" 
                                   name="purchase_price" 
                                   value="{{ old('purchase_price') }}" 
                                   step="0.01"
                                   min="0"
                                   required
                                   placeholder="0.00">
                            @error('purchase_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label required-field">Initial Quantity</label>
                            <input type="number" 
                                   class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="{{ old('quantity', 0) }}" 
                                   min="0"
                                   required
                                   placeholder="0">
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Min Stock Level -->
                    <div class="mb-4">
                        <label for="min_stock_level" class="form-label required-field">Minimum Stock Level</label>
                        <input type="number" 
                               class="form-control @error('min_stock_level') is-invalid @enderror" 
                               id="min_stock_level" 
                               name="min_stock_level" 
                               value="{{ old('min_stock_level', 10) }}" 
                               min="0"
                               required
                               placeholder="10">
                        @error('min_stock_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            You'll be alerted when stock falls below this level
                        </div>
                    </div>
                </div>

                <!-- Right Column - Image Upload -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Product Image</label>
                        <div class="image-preview" id="imagePreview">
                            <div class="image-placeholder">
                                <i class="bi bi-image"></i>
                                <span>Click to upload image</span>
                            </div>
                        </div>
                        <input type="file" 
                               class="form-control d-none" 
                               id="image_url" 
                               name="image_url" 
                               accept="image/*">
                        @error('image_url')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Recommended: 500x500px, JPG, PNG or GIF
                        </div>
                    </div>

                    <!-- Stock Alert Preview -->
                    <div class="stock-alert mt-4" id="stockAlert" style="display: none;">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <span id="alertMessage"></span>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('products.index') }}" class="btn btn-cancel text-white">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary text-white">
                            <i class="bi bi-plus-circle me-2"></i>Add Product
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview functionality
        const imageInput = document.getElementById('image_url');
        const imagePreview = document.getElementById('imagePreview');
        const placeholder = imagePreview.querySelector('.image-placeholder');

        imagePreview.addEventListener('click', function() {
            imageInput.click();
        });

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(file);
            }
        });

        // Stock level validation
        const quantityInput = document.getElementById('quantity');
        const minStockInput = document.getElementById('min_stock_level');
        const stockAlert = document.getElementById('stockAlert');
        const alertMessage = document.getElementById('alertMessage');

        function checkStockLevels() {
            const quantity = parseInt(quantityInput.value) || 0;
            const minStock = parseInt(minStockInput.value) || 0;

            if (quantity <= minStock) {
                if (quantity === 0) {
                    alertMessage.textContent = 'Product will be out of stock';
                    stockAlert.style.display = 'block';
                } else if (quantity <= minStock) {
                    alertMessage.textContent = `Low stock alert: Quantity (${quantity}) is at or below minimum level (${minStock})`;
                    stockAlert.style.display = 'block';
                }
            } else {
                stockAlert.style.display = 'none';
            }
        }

        quantityInput.addEventListener('input', checkStockLevels);
        minStockInput.addEventListener('input', checkStockLevels);

        // Auto-generate SKU if empty
        const nameInput = document.getElementById('name');
        const skuInput = document.getElementById('sku');

        nameInput.addEventListener('blur', function() {
            if (!skuInput.value) {
                const name = this.value.trim();
                if (name) {
                    const sku = name.toUpperCase()
                        .replace(/[^A-Z0-9]/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-|-$/g, '')
                        .substring(0, 20) + '-' + Date.now().toString().slice(-4);
                    skuInput.value = sku;
                }
            }
        });

        // Form submission
        const form = document.getElementById('productForm');
        form.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Adding Product...';
            submitButton.disabled = true;
        });
    });
</script>
@endpush

@endsection