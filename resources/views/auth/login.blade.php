@extends('layouts.app')

@section('content')
    <div class="login-container">
        <div class="login-wrapper">
            <!-- Left Side - Branding -->
            <div class="login-brand">
                <div class="brand-content">
                    <div class="brand-icon">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <h1 class="brand-title">Health Dashboard</h1>
                    <p class="brand-subtitle">Sistem Manajemen Data Kesehatan Terpadu</p>

                    <div class="demo-credentials">
                        <h5 class="mb-3">Akun Demo</h5>
                        <div class="credential-item">
                            <small><strong>Admin:</strong> admin@healthdashboard.com</small>
                        </div>
                        <div class="credential-item">
                            <small><strong>Manager:</strong> manager@healthdashboard.com</small>
                        </div>
                        <div class="credential-item">
                            <small><strong>Staff:</strong> staff@healthdashboard.com</small>
                        </div>
                        <div class="credential-item mt-2">
                            <small><strong>Password:</strong> password</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="login-form-container">
                <div class="login-form-wrapper">
                    <div class="login-header">
                        <h2>Masuk ke Akun Anda</h2>
                        <p>Silakan login dengan email dan password Anda</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="login-form">
                        @csrf

                        <!-- Email Field -->
                        <div class="form-group mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-wrapper">
                                <i class="bi bi-envelope input-icon"></i>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" placeholder="Masukkan email Anda" required
                                    autocomplete="email" autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="form-group mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-wrapper password-wrapper">
                                <i class="bi bi-lock input-icon"></i>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    placeholder="Masukkan password Anda" required autocomplete="current-password">
                                <button type="button" class="btn-toggle-password" id="togglePassword"
                                    title="Tampilkan/Sembunyikan Password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="form-group mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ingat saya
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-login w-100 mb-3">
                            <span>Masuk</span>
                            <i class="bi bi-arrow-right ms-2"></i>
                        </button>

                        <!-- Forgot Password Link -->
                        @if (Route::has('password.request'))
                            <div class="text-center">
                                <a href="{{ route('password.request') }}" class="forgot-password-link">
                                    <i class="bi bi-question-circle me-1"></i>Lupa Password?
                                </a>
                            </div>
                        @endif
                    </form>

                    <!-- Footer -->
                    <div class="login-footer">
                        <p class="text-muted text-center">
                            © 2026 Health Dashboard. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

        .login-wrapper {
            display: flex;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            min-height: 600px;
        }

        /* Left Side - Branding */
        .login-brand {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-brand::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .brand-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .brand-icon {
            font-size: 60px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .brand-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .brand-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 40px;
        }

        .demo-credentials {
            background: rgba(255, 255, 255, 0.15);
            padding: 20px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            text-align: left;
        }

        .demo-credentials h5 {
            font-size: 14px;
            font-weight: 600;
        }

        .credential-item {
            padding: 5px 0;
            font-size: 13px;
            opacity: 0.9;
        }

        /* Right Side - Login Form */
        .login-form-container {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-form-wrapper {
            width: 100%;
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 10px;
        }

        .login-header p {
            font-size: 14px;
            color: #6B7280;
        }

        /* Form Styling */
        .login-form {
            width: 100%;
        }

        .form-group {
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            color: #9CA3AF;
            font-size: 18px;
            pointer-events: none;
        }

        .form-control {
            padding-left: 45px !important;
            padding-right: 15px;
            height: 48px;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #F9FAFB;
        }

        .form-control:focus {
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            color: #1F2937;
        }

        .form-control::placeholder {
            color: #D1D5DB;
        }

        .password-wrapper {
            position: relative;
        }

        .btn-toggle-password {
            position: absolute;
            right: 15px;
            background: none;
            border: none;
            color: #9CA3AF;
            cursor: pointer;
            padding: 0;
            font-size: 18px;
            transition: color 0.3s ease;
            z-index: 10;
        }

        .btn-toggle-password:hover {
            color: #667eea;
        }

        .btn-toggle-password i {
            display: inline-block;
        }

        .form-control.is-invalid {
            border-color: #EF4444;
            background-image: none;
        }

        .form-control.is-invalid:focus {
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .invalid-feedback {
            font-size: 13px;
            color: #EF4444;
            display: block;
            margin-top: 6px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 3px;
            border: 2px solid #D1D5DB;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-label {
            font-size: 14px;
            color: #374151;
            cursor: pointer;
            margin-left: 8px;
        }

        .btn-login {
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .forgot-password-link {
            font-size: 14px;
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
            display: inline-block;
        }

        .forgot-password-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .login-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
        }

        .login-footer p {
            font-size: 12px;
            color: #9CA3AF;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                min-height: auto;
            }

            .login-brand {
                padding: 40px 30px;
                min-height: 300px;
            }

            .login-form-container {
                padding: 40px 30px;
            }

            .brand-icon {
                font-size: 50px;
            }

            .brand-title {
                font-size: 24px;
            }

            .login-header h2 {
                font-size: 24px;
            }

            .demo-credentials {
                margin-top: 20px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePasswordBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type');
                    const newType = type === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', newType);

                    // Change icon
                    const icon = this.querySelector('i');
                    if (newType === 'text') {
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            }
        });
    </script>
@endsection
