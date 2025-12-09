@extends('layouts.dashboard')

@section('title', 'Apply for Job')

@section('portal-name', 'Candidate Portal')
@section('brand-icon', 'bi bi-person-circle')
@section('dashboard-route', route('candidate.dashboard'))
@section('user-name', Auth::guard('candidate')->user()->name)
@section('user-role', 'Candidate')
@section('user-initial', strtoupper(substr(Auth::guard('candidate')->user()->name, 0, 1)))
@section('logout-route', route('candidate.logout'))

@section('sidebar-menu')
    <a href="{{ route('candidate.dashboard') }}" class="sidebar-menu-item">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('candidate.jobs.index') }}" class="sidebar-menu-item active">
        <i class="bi bi-search"></i>
        <span>Browse Vacancy</span>
    </a>
    <a href="{{ route('candidate.applications.index') }}" class="sidebar-menu-item">
        <i class="bi bi-file-earmark-text"></i>
        <span>My Applications</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-bookmark"></i>
        <span>Saved Jobs</span>
    </a>
    <a href="#" class="sidebar-menu-item">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
@endsection

@section('custom-styles')
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --success: #10b981;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-900: #0f172a;
            --white: #ffffff;
        }

        .back-link {
            font-size: 14px;
            color: var(--gray-600);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 16px;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0 0 8px 0;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--gray-500);
            margin-bottom: 24px;
        }

        /* Job Info Card */
        .job-info-card {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .job-info-title {
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }

        /* Form Card */
        .form-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 32px;
        }

        .form-section {
            margin-bottom: 32px;
            padding-bottom: 32px;
            border-bottom: 1px solid var(--gray-100);
        }

        .form-section:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 16px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .form-label .required {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 150px;
        }

        .file-upload-box {
            border: 2px dashed var(--gray-300);
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .file-upload-box:hover {
            border-color: var(--primary);
            background: var(--gray-50);
        }

        .file-upload-box.dragover {
            border-color: var(--primary);
            background: #eef2ff;
        }

        .file-icon {
            font-size: 48px;
            color: var(--primary);
            margin-bottom: 12px;
        }

        .file-info {
            font-size: 14px;
            color: var(--gray-600);
            margin-top: 8px;
        }

        .selected-file {
            margin-top: 12px;
            padding: 12px;
            background: var(--gray-50);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .selected-file-name {
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-remove-file {
            color: #ef4444;
            cursor: pointer;
            padding: 4px 8px;
        }

        .btn-remove-file:hover {
            background: #fee2e2;
            border-radius: 4px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        .btn {
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
            flex: 1;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .btn-secondary:hover {
            background: var(--gray-50);
        }

        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .read-only-info {
            padding: 12px;
            background: var(--gray-50);
            border-radius: 6px;
            font-size: 14px;
            color: var(--gray-700);
        }
    </style>
@endsection

@section('content')
    <!-- Back Link -->
    <a href="{{ route('candidate.jobs.show', $job->id) }}" class="back-link">
        <i class="bi bi-arrow-left"></i>
        Back to Job Details
    </a>

    <!-- Page Title -->
    <h1 class="page-title">Apply for Position</h1>
    <p class="page-subtitle">Complete the application form to submit your application</p>

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle"></i>
            <div>
                <strong>Please fix the following errors:</strong>
                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Job Info -->
    <div class="job-info-card">
        <div style="font-size: 12px; font-weight: 600; opacity: 0.9; margin-bottom: 8px;">{{ $job->advertisement_no }}</div>
        <h2 class="job-info-title">{{ $job->title }}</h2>
        <div style="font-size: 14px; opacity: 0.95;">
            {{ $job->department }} • {{ $job->location }} • {{ $job->position_level }}
        </div>
    </div>

    <!-- Application Form -->
    <div class="form-card">
        <form action="{{ route('candidate.jobs.applications.store', $job->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <!-- Personal Information -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-person-fill text-primary"></i>
                    Personal Information
                </h3>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill"></i>
                    <div style="font-size: 14px;">
                        Your personal information is automatically filled from your profile.
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <div class="read-only-info">{{ $candidate->user->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <div class="read-only-info">{{ $candidate->user->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <div class="read-only-info">{{ $candidate->phone ?? 'Not provided' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <div class="read-only-info">
                            {{ $candidate->date_of_birth ? \Carbon\Carbon::parse($candidate->date_of_birth)->format('M d, Y') : 'Not provided' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cover Letter -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-file-text-fill text-success"></i>
                    Cover Letter
                </h3>

                <div class="form-group">
                    <label class="form-label">
                        Write Your Cover Letter <span class="required">*</span>
                    </label>
                    <textarea name="cover_letter" class="form-control @error('cover_letter') is-invalid @enderror"
                        placeholder="Explain why you're a great fit for this position..."
                        required>{{ old('cover_letter') }}</textarea>
                    @error('cover_letter')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div class="file-info">Minimum 100 characters • Maximum 5000 characters</div>
                </div>
            </div>

            <!-- Resume Upload -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                    Resume / CV
                </h3>

                <div class="form-group">
                    <label class="form-label">
                        Upload Your Resume <span class="required">*</span>
                    </label>
                    <div class="file-upload-box" onclick="document.getElementById('resume').click()">
                        <input type="file" name="resume" id="resume" accept=".pdf" style="display: none;" required
                            onchange="handleFileSelect(this, 'resume-display')">
                        <div class="file-icon">
                            <i class="bi bi-cloud-upload"></i>
                        </div>
                        <div style="font-size: 16px; font-weight: 600; margin-bottom: 4px;">
                            Click to upload or drag and drop
                        </div>
                        <div class="file-info">PDF only • Maximum file size: 5MB</div>
                    </div>
                    <div id="resume-display"></div>
                    @error('resume')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Additional Documents -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-paperclip text-warning"></i>
                    Additional Documents (Optional)
                </h3>

                <div class="form-group">
                    <label class="form-label">Upload Additional Documents</label>
                    <div class="file-upload-box" onclick="document.getElementById('documents').click()">
                        <input type="file" name="additional_documents[]" id="documents"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple style="display: none;"
                            onchange="handleMultipleFiles(this, 'documents-display')">
                        <div class="file-icon">
                            <i class="bi bi-files"></i>
                        </div>
                        <div style="font-size: 16px; font-weight: 600; margin-bottom: 4px;">
                            Upload certificates, transcripts, etc.
                        </div>
                        <div class="file-info">PDF, DOC, DOCX, JPG, PNG • Maximum 5MB per file • Multiple files allowed
                        </div>
                    </div>
                    <div id="documents-display"></div>
                    @error('additional_documents.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="form-section">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="terms" required
                        style="width: 18px; height: 18px; margin-top: 2px;">
                    <label class="form-check-label" for="terms" style="margin-left: 8px; font-size: 14px;">
                        I confirm that all information provided is accurate and I agree to the terms and conditions
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('candidate.jobs.show', $job->id) }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send-fill"></i>
                    Submit Application
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        function handleFileSelect(input, displayId) {
            const display = document.getElementById(displayId);
            display.innerHTML = '';

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSize = (file.size / (1024 * 1024)).toFixed(2);

                const fileDiv = document.createElement('div');
                fileDiv.className = 'selected-file';
                fileDiv.innerHTML = `
                    <div class="selected-file-name">
                        <i class="bi bi-file-earmark-pdf text-danger"></i>
                        <span>${file.name} (${fileSize} MB)</span>
                    </div>
                    <button type="button" class="btn-remove-file" onclick="clearFile('${input.id}', '${displayId}')">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                `;
                display.appendChild(fileDiv);
            }
        }

        function handleMultipleFiles(input, displayId) {
            const display = document.getElementById(displayId);
            display.innerHTML = '';

            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    const fileSize = (file.size / (1024 * 1024)).toFixed(2);

                    const fileDiv = document.createElement('div');
                    fileDiv.className = 'selected-file';
                    fileDiv.innerHTML = `
                        <div class="selected-file-name">
                            <i class="bi bi-file-earmark text-primary"></i>
                            <span>${file.name} (${fileSize} MB)</span>
                        </div>
                    `;
                    display.appendChild(fileDiv);
                });
            }
        }

        function clearFile(inputId, displayId) {
            document.getElementById(inputId).value = '';
            document.getElementById(displayId).innerHTML = '';
        }

        // Drag and drop functionality
        document.querySelectorAll('.file-upload-box').forEach(box => {
            box.addEventListener('dragover', (e) => {
                e.preventDefault();
                box.classList.add('dragover');
            });

            box.addEventListener('dragleave', () => {
                box.classList.remove('dragover');
            });

            box.addEventListener('drop', (e) => {
                e.preventDefault();
                box.classList.remove('dragover');
                const input = box.querySelector('input[type="file"]');
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            });
        });
    </script>
@endsection