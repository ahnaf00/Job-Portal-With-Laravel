@extends('frontend.layouts.master')
@section('frontend-content')
   <div class="container d-flex justify-content-center align-items-center vh-100 py-5 mt-5">
        <div class="auth-card p-4 p-md-5 rounded-4 shadow-lg bg-white w-100" style="max-width: 500px;">
            <div class="text-center mb-4">
                <a href="index.html" class="logo fw-bold fs-3 text-decoration-none d-inline-block mb-3">
                    <span class="text-primary">Job</span><span class="text-tertiary">cy.</span>
                </a>
                <p class="text-muted">Welcome! Please register to get started.</p>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="register-form" role="tabpanel" aria-labelledby="register-tab">
                    <form id="registerForm">
                        <!-- Role Selection -->
                        <div class="mb-4">
                            <label class="form-label">Register as a...</label>
                            <div class="d-flex justify-content-around">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="role" id="role-candidate" value="candidate" required checked>
                                    <label class="form-check-label" for="role-candidate">Candidate</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="role" id="role-company" value="company" required>
                                    <label class="form-check-label" for="role-company">Company</label>
                                </div>
                            </div>
                            <div class="invalid-feedback" id="roleError">Please select a role.</div>
                        </div>

                        <!-- General User Fields -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                                <div class="invalid-feedback" id="nameError">Please enter a Name.</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                <div class="invalid-feedback" id="emailError">Please enter a valid email address.</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Create password" required>
                                <div class="invalid-feedback" id="passwordError">Please enter a password.</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                                <div class="invalid-feedback" id="passwordConfirmationError">Passwords do not match.</div>
                            </div>
                        </div>

                        <!-- Candidate-Specific Fields (visible by default) -->
                        <div id="candidate-fields">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa fa-user-tag"></i></span>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name" required>
                                    <div class="invalid-feedback" id="firstNameError">Please enter your first name.</div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="last_name" class="form-label">Last Name</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa fa-user-tag"></i></span>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name" required>
                                    <div class="invalid-feedback" id="lastNameError">Please enter your last name.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Company-Specific Fields (hidden by default) -->
                        <div id="company-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa fa-building"></i></span>
                                    <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter company name">
                                    <div class="invalid-feedback" id="companyNameError">Please enter a company name.</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter company address">
                                    <div class="invalid-feedback" id="addressError">Please enter a company address.</div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="website" class="form-label">Website (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-globe"></i></span>
                                    <input type="url" class="form-control" id="website" name="website" placeholder="https://www.example.com">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Register</button>
                    </form>
                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">Or register with</p>
                        <div class="d-flex justify-content-center gap-2 mt-2">
                            <a href="#" class="btn btn-outline-dark rounded-circle btn-icon"><i class="fab fa-google"></i></a>
                            <a href="#" class="btn btn-outline-dark rounded-circle btn-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="btn btn-outline-dark rounded-circle btn-icon"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">Already registered ? <a href="{{ route('loginView') }}">Login</a> here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle candidate/company fields based on role selection
        document.querySelectorAll('input[name="role"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const candidateFields = document.getElementById('candidate-fields');
                const companyFields = document.getElementById('company-fields');
                const isCandidate = this.value === 'candidate';

                candidateFields.style.display = isCandidate ? 'block' : 'none';
                companyFields.style.display = isCandidate ? 'none' : 'block';

                // Update required attributes
                document.getElementById('first_name').required      = isCandidate;
                document.getElementById('last_name').required       = isCandidate;
                document.getElementById('company_name').required    = !isCandidate;
                document.getElementById('address').required         = !isCandidate;
            });
        });

        // Form submission handler
        document.getElementById('registerForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            // Get form inputs
            const role                  = document.querySelector('input[name="role"]:checked')?.value;
            const name                  = document.getElementById('name').value;
            const email                 = document.getElementById('email').value;
            const password              = document.getElementById('password').value;
            const passwordConfirmation  = document.getElementById('password_confirmation').value;
            const firstName             = document.getElementById('first_name').value;
            const lastName              = document.getElementById('last_name').value;
            const companyName           = document.getElementById('company_name').value;
            const address               = document.getElementById('address').value;
            const website               = document.getElementById('website').value;

            // Reset validation states
            document.querySelectorAll('.form-control, .form-check-input').forEach(el => el.classList.remove('is-invalid'));
            document.getElementById('roleError').style.display                  = 'none';
            document.getElementById('nameError').textContent                    = 'Please enter a username.';
            document.getElementById('emailError').textContent                   = 'Please enter a valid email address.';
            document.getElementById('passwordError').textContent                = 'Please enter a password.';
            document.getElementById('passwordConfirmationError').textContent    = 'Passwords do not match.';
            document.getElementById('firstNameError').textContent               = 'Please enter your first name.';
            document.getElementById('lastNameError').textContent                = 'Please enter your last name.';
            document.getElementById('companyNameError').textContent             = 'Please enter a company name.';
            document.getElementById('addressError').textContent                 = 'Please enter a company address.';

            let isValid = true;

            // Client-side validation
            if (!role) {
                document.getElementById('roleError').style.display = 'block';
                document.querySelectorAll('input[name="role"]').forEach(el => el.classList.add('is-invalid'));
                isValid = false;
            }

            if (!name) {
                document.getElementById('name').classList.add('is-invalid');
                isValid = false;
            }
            if (!email) {
                document.getElementById('email').classList.add('is-invalid');
                isValid = false;
            }
            if (!password) {
                document.getElementById('password').classList.add('is-invalid');
                isValid = false;
            }
            if (password !== passwordConfirmation) {
                document.getElementById('password_confirmation').classList.add('is-invalid');
                isValid = false;
            }

            if (role === 'candidate') {
                if (!firstName) {
                    document.getElementById('first_name').classList.add('is-invalid');
                    isValid = false;
                }
                if (!lastName) {
                    document.getElementById('last_name').classList.add('is-invalid');
                    isValid = false;
                }
            } else if (role === 'company') {
                if (!companyName) {
                    document.getElementById('company_name').classList.add('is-invalid');
                    isValid = false;
                }
                if (!address) {
                    document.getElementById('address').classList.add('is-invalid');
                    isValid = false;
                }
            }

            if (!isValid) return;

            // Prepare API data
            const data = {
                name,
                email,
                password,
                password_confirmation: passwordConfirmation,
                role
            };
            if (role === 'candidate') {
                data.first_name = firstName;
                data.last_name = lastName;
            } else if (role === 'company') {
                data.company_name = companyName;
                data.address = address;
                if (website) data.website = website;
            }

            try {
                const response = await fetch('http://127.0.0.1:8000/api/register', {
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
                    alert(result.message || 'Registration successful!');
                    // Redirect to login or dashboard
                    window.location.href = '/login'; // Replace with '/dashboard' if preferred
                } else {
                    // Display API errors
                    if (result.message.includes('email')) {
                        document.getElementById('email').classList.add('is-invalid');
                        document.getElementById('emailError').textContent = result.message || 'Email is already taken.';
                    } else if (result.message.includes('password')) {
                        document.getElementById('password').classList.add('is-invalid');
                        document.getElementById('passwordError').textContent = result.message || 'Password is invalid.';
                    } else {
                        alert(result.message || 'Registration failed.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred during registration. Please try again.');
            }
        });
    </script>
@endsection

