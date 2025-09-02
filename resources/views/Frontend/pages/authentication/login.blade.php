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
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email address</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                <input type="email" class="form-control" id="loginEmail" placeholder="name@example.com"
                                    required>
                                <div class="invalid-feedback" id="emailError">
                                    Please enter a valid email address.
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" id="loginPassword" placeholder="Enter password"
                                    required>
                                <div class="invalid-feedback" id="passwordError">
                                    Please enter your password.
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                            <a href="#" class="text-primary text-decoration-none">Forgot Password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Log in</button>
                    </form>
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

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const emailInput    = document.getElementById('loginEmail');
            const passwordInput = document.getElementById('loginPassword');
            const emailError    = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            const rememberMe    = document.getElementById('rememberMe').checked;

            // Reset validation states
            emailInput.classList.remove('is-invalid');
            passwordInput.classList.remove('is-invalid');
            emailError.textContent = 'Please enter a valid email address.';
            passwordError.textContent = 'Please enter your password.';

            let isValid = true;

            // Client-side validation
            if (!emailInput.value) {
                emailInput.classList.add('is-invalid');
                isValid = false;
            }
            if (!passwordInput.value) {
                passwordInput.classList.add('is-invalid');
                isValid = false;
            }

            if (!isValid) return;

            const data = {
                email: emailInput.value,
                password: passwordInput.value
            };

            try {
                const response = await fetch('http://127.0.0.1:8000/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    // Store token
                    localStorage.setItem('access_token', result.access_token);
                    alert(result.message || 'Login successful!');
                    window.location.href = '/dashboard';
                } else {
                    // Display API error
                    if (result.message.includes('email')) {
                        emailInput.classList.add('is-invalid');
                        emailError.textContent = result.message || 'Invalid email address.';
                    } else if (result.message.includes('password')) {
                        passwordInput.classList.add('is-invalid');
                        passwordError.textContent = result.message || 'Invalid password.';
                    } else {
                        alert(result.message || 'Login failed.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred during login. Please try again.');
            }
        });
    </script>
@endsection

