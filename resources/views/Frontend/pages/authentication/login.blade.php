@extends('frontend.layouts.master')
@section('frontend-content')
    <div class="container d-flex justify-content-center align-items-center vh-100 py-5">
        <div class="auth-card p-4 p-md-5 rounded-4 shadow-lg bg-white w-100" style="max-width: 500px;">
            <div class="text-center mb-4">
                <a href="index.html" class="logo fw-bold fs-3 text-decoration-none d-inline-block mb-3">
                    <span class="text-primary">Job</span><span class="text-secondary">cy.</span>
                </a>
                <p class="text-muted">Welcome back! Please login to your account or register to get started.</p>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="login-form" role="tabpanel" aria-labelledby="login-tab">
                    {{-- Form starts --}}
                    <form id="loginForm" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email address</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                <input type="email" class="form-control" id="loginEmail" name="email" placeholder="name@example.com" required>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Enter password" required>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                            <a href="#" class="text-primary text-decoration-none">Forgot Password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Log in</button>
                    </form>
                    {{-- Form Ends --}}
                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">Or log in with</p>
                        <div class="d-flex justify-content-center gap-2 mt-2">
                            <a href="#" class="btn btn-outline-dark rounded-circle btn-icon"><i
                                    class="fab fa-google"></i></a>
                            <a href="#" class="btn btn-outline-dark rounded-circle btn-icon"><i
                                    class="fab fa-facebook-f"></i></a>
                            <a href="#" class="btn btn-outline-dark rounded-circle btn-icon"><i
                                    class="fab fa-twitter"></i></a>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">New here ? <a href="{{ 'registerView' }}">register</a> now</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

