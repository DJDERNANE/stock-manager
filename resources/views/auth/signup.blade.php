@extends('layouts.base')

@section('title', 'Register - ' . config('app.name'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 text-center">Create Your Account</h4>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('signup') }}">
                        @csrf
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                            @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </form>
                </div>
            </div>

            <!-- Social Registration (Optional) -->
            <div class="card mt-3 shadow-sm">
                <div class="card-body text-center">
                    <p class="mb-2">Or sign up with</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <button type="button" class="btn btn-outline-dark me-md-2">
                            <i class="bi bi-google"></i> Google
                        </button>
                        <button type="button" class="btn btn-outline-primary me-md-2">
                            <i class="bi bi-facebook"></i> Facebook
                        </button>
                        <button type="button" class="btn btn-outline-dark">
                            <i class="bi bi-github"></i> GitHub
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 15px;
    }

    .card-header {
        border-bottom: 1px solid #eaeaea;
        border-radius: 15px 15px 0 0 !important;
    }

    .btn-primary {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #218838, #1e9e8a);
        transform: translateY(-1px);
        transition: all 0.3s ease;
    }

    .form-control {
        border-radius: 8px;
        padding: 12px 15px;
        border: 2px solid #eaeaea;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .password-strength {
        height: 4px;
        border-radius: 2px;
        margin-top: 5px;
        transition: all 0.3s ease;
    }

    .strength-weak {
        background-color: #dc3545;
        width: 25%;
    }

    .strength-fair {
        background-color: #fd7e14;
        width: 50%;
    }

    .strength-good {
        background-color: #ffc107;
        width: 75%;
    }

    .strength-strong {
        background-color: #28a745;
        width: 100%;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const passwordStrength = document.createElement('div');
        passwordStrength.className = 'password-strength';
        passwordInput.parentNode.appendChild(passwordStrength);

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            // Length check
            if (password.length >= 8) strength++;

            // Contains lowercase
            if (/[a-z]/.test(password)) strength++;

            // Contains uppercase
            if (/[A-Z]/.test(password)) strength++;

            // Contains numbers
            if (/[0-9]/.test(password)) strength++;

            // Contains special characters
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            // Update strength indicator
            passwordStrength.className = 'password-strength ';
            if (password.length === 0) {
                passwordStrength.style.width = '0%';
            } else if (strength <= 2) {
                passwordStrength.className += 'strength-weak';
            } else if (strength === 3) {
                passwordStrength.className += 'strength-fair';
            } else if (strength === 4) {
                passwordStrength.className += 'strength-good';
            } else {
                passwordStrength.className += 'strength-strong';
            }
        });

        // Confirm password validation
        const confirmPassword = document.getElementById('password_confirmation');
        confirmPassword.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });

        // Phone number formatting (optional)
        const phoneInput = document.getElementById('phone');
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = '(' + value;
                if (value.length > 4) {
                    value = value.slice(0, 4) + ') ' + value.slice(4);
                }
                if (value.length > 9) {
                    value = value.slice(0, 9) + '-' + value.slice(9, 13);
                }
            }
            e.target.value = value;
        });

        // Form submission enhancement
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Creating Account...';
            submitButton.disabled = true;
        });
    });
</script>
@endpush