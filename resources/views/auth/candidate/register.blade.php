<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Registration - Job Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }

        .register-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 700px;
            margin: 0 auto;
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 32px;
            text-align: center;
        }

        .register-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px 0;
        }

        .register-header p {
            margin: 0;
            opacity: 0.95;
        }

        .register-body {
            padding: 32px;
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
            color: #374151;
            margin-bottom: 8px;
        }

        .nepali-label {
            font-size: 12px;
            color: #6b7280;
            margin-left: 4px;
        }

        .form-control {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            color: #6b7280;
        }

        .login-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .required {
            color: #ef4444;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .full-name-display {
            background: #f3f4f6;
            padding: 12px 16px;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            font-weight: 600;
            color: #1f2937;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="register-card">
            <div class="register-header">
                <i class="bi bi-person-plus-fill" style="font-size: 48px; margin-bottom: 16px;"></i>
                <h1>Create Your Account</h1>
                <p>Join us and start your career journey today</p>
            </div>

            <div class="register-body">
                <!-- Validation Errors -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('candidate.register.post') }}" method="POST" id="registerForm">
                    @csrf

                    <div class="row">
                        <!-- First Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                First Name <span class="nepali-label">(पहिलो नाम)</span> <span class="required">*</span>
                            </label>
                            <input type="text" name="first_name" id="first_name" 
                                class="form-control @error('first_name') is-invalid @enderror"
                                placeholder="Enter first name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Middle Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Middle Name <span class="nepali-label">(बीचको नाम)</span>
                            </label>
                            <input type="text" name="middle_name" id="middle_name"
                                class="form-control @error('middle_name') is-invalid @enderror"
                                placeholder="Enter middle name (optional)" value="{{ old('middle_name') }}">
                            @error('middle_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Last Name -->
                    <div class="mb-3">
                        <label class="form-label">
                            Last Name <span class="nepali-label">(थर)</span> <span class="required">*</span>
                        </label>
                        <input type="text" name="last_name" id="last_name"
                            class="form-control @error('last_name') is-invalid @enderror"
                            placeholder="Enter last name" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Full Name Display -->
                    <div class="mb-3">
                        <label class="form-label">
                            Full Name <span class="nepali-label">(पुरा नाम)</span>
                        </label>
                        <div class="full-name-display" id="fullNameDisplay">
                            <i class="bi bi-person me-2"></i>
                            <span id="fullNameText">Your full name will appear here</span>
                        </div>
                        <small class="text-muted">Auto-generated from first, middle, and last name</small>
                    </div>

                    <div class="row">
                        <!-- Mobile Number -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Mobile Number <span class="nepali-label">(मोबाइल नम्बर)</span> <span class="required">*</span>
                            </label>
                            <input type="text" name="mobile_number" 
                                class="form-control @error('mobile_number') is-invalid @enderror"
                                placeholder="98XXXXXXXX" value="{{ old('mobile_number') }}" 
                                maxlength="10" pattern="[0-9]{10}" required>
                            @error('mobile_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">10 digit mobile number</small>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Email <span class="nepali-label">(इमेल)</span> <span class="required">*</span>
                            </label>
                            <input type="email" name="email" 
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="your.email@example.com" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label">
                            Username <span class="nepali-label">(युजरनेम)</span> <span class="required">*</span>
                        </label>
                        <input type="text" name="username" 
                            class="form-control @error('username') is-invalid @enderror"
                            placeholder="Choose a unique username" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Letters, numbers, and underscores only</small>
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Password <span class="nepali-label">(पासवर्ड)</span> <span class="required">*</span>
                            </label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimum 8 characters" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                Confirm Password <span class="nepali-label">(पासवर्ड पुष्टि गर्नुहोस्)</span> <span class="required">*</span>
                            </label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Re-enter your password" required>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label" for="terms" style="font-size: 14px;">
                            I agree to the Terms and Conditions and Privacy Policy
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-register">
                        <i class="bi bi-person-check me-2"></i>
                        Create Account
                    </button>
                </form>

                <!-- Login Link -->
                <div class="login-link">
                    Already have an account?
                    <a href="{{ route('candidate.login') }}">Sign In</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-generate full name
        const firstNameInput = document.getElementById('first_name');
        const middleNameInput = document.getElementById('middle_name');
        const lastNameInput = document.getElementById('last_name');
        const fullNameText = document.getElementById('fullNameText');

        function updateFullName() {
            const firstName = firstNameInput.value.trim();
            const middleName = middleNameInput.value.trim();
            const lastName = lastNameInput.value.trim();

            const nameParts = [firstName, middleName, lastName].filter(part => part !== '');
            
            if (nameParts.length > 0) {
                fullNameText.textContent = nameParts.join(' ');
            } else {
                fullNameText.textContent = 'Your full name will appear here';
            }
        }

        firstNameInput.addEventListener('input', updateFullName);
        middleNameInput.addEventListener('input', updateFullName);
        lastNameInput.addEventListener('input', updateFullName);

        // Validate mobile number (only digits)
        document.querySelector('input[name="mobile_number"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Validate username (letters, numbers, underscores only)
        document.querySelector('input[name="username"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
        });
    </script>
</body>

</html>