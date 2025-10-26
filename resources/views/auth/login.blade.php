@extends('layouts.base')

@section('title', 'Login - ' . config('app.name'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 text-center">Login to Your Account</h4>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus
                                   placeholder="Enter your email">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="Enter your password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="remember" 
                                   name="remember">
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>

                        <!-- Forgot Password Link -->
                        <div class="text-center mb-3">
                            @if (Route::has('password.request'))
                                <a  class="text-decoration-none">
                                    Forgot Your Password?
                                </a>
                            @endif
                        </div>

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="mb-0">
                                Don't have an account? 
                                <a href="{{ route('signup-form') }}" class="text-decoration-none fw-bold">
                                    Sign up here
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Social Login (Optional) -->
            <div class="card mt-3 shadow-sm">
                <div class="card-body text-center">
                    <p class="mb-2">Or login with</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-dark">
                            <i class="bi bi-google"></i> Google
                        </button>
                        <!-- Add more social buttons as needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add floating label effect
        const inputs = document.querySelectorAll('.form-control');
        
        inputs.forEach(input => {
            // Check if input has value on load
            if (input.value) {
                input.parentElement.classList.add('filled');
            }
            
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
                this.parentElement.classList.toggle('filled', this.value !== '');
            });
        });
        
        // Toggle password visibility (optional enhancement)
        const togglePassword = document.createElement('span');
        togglePassword.innerHTML = '<i class="bi bi-eye"></i>';
        togglePassword.className = 'password-toggle';
        togglePassword.style.cssText = `
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        `;
        
        const passwordInput = document.getElementById('password');
        const passwordGroup = passwordInput.parentElement;
        passwordGroup.style.position = 'relative';
        passwordGroup.appendChild(togglePassword);
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
        });
    });
</script>
@endpush