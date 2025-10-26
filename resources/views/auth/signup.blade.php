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
                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Sign Up
                            </button>
                        </div>

                       

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="mb-0">
                                you have an account? 
                                <a href="{{ route('login-form') }}" class="text-decoration-none fw-bold">
                                    Login here
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

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