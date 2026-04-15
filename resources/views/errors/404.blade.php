<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | Nepal Oil Corporation</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            display: flex;
            flex-direction: column;
        }

        /* ── Navbar ── */
        .navbar {
            background: linear-gradient(90deg, #ffffff 0%, #fdf9f2 100%);
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            padding: 0.75rem 1.5rem;
        }

        .noc-brand-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .noc-logo {
            height: 50px;
            width: auto;
        }

        .noc-info h5 {
            margin: 0;
            font-size: 17px;
            font-weight: 600;
            color: #1a2a4a;
            line-height: 1.2;
        }

        .noc-info p {
            margin: 0;
            font-size: 13px;
            color: #555;
            line-height: 1.2;
        }

        .noc-info small {
            font-size: 11px;
            color: #c9a84c;
            font-style: italic;
            display: block;
            margin-top: 2px;
        }

        /* ── Main area ── */
        .error-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
        }

        .error-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            padding: 3.5rem 3rem;
            text-align: center;
            max-width: 560px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        /* Gold top bar */
        .error-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, #c9a84c 0%, #a07828 50%, #c9a84c 100%);
        }

        /* Decorative blobs */
        .blob-1 {
            position: absolute;
            bottom: -20px; right: -20px;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,168,76,0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .blob-2 {
            position: absolute;
            top: 60px; left: -30px;
            width: 80px; height: 80px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,168,76,0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Icon circle */
        .error-icon-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(201,168,76,0.15) 0%, rgba(160,120,40,0.1) 100%);
            border: 2px solid rgba(201,168,76,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem auto;
            font-size: 2rem;
            color: #a07828;
        }

        /* 404 number */
        .error-code {
            font-size: 7rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -4px;
            margin-bottom: 0.25rem;
        }

        .error-divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #c9a84c, #a07828);
            border-radius: 2px;
            margin: 0.75rem auto 1.5rem auto;
        }

        .error-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a2a4a;
            margin-bottom: 0.75rem;
        }

        .error-subtitle {
            font-size: 0.95rem;
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        /* Buttons */
        .btn-gold {
            background: linear-gradient(135deg, #c9a84c 0%, #a07828 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.65rem 1.75rem;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(160,120,40,0.3);
        }

        .btn-gold:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(160,120,40,0.4);
        }

        .btn-outline-gold {
            background: transparent;
            color: #a07828;
            border: 1.5px solid #c9a84c;
            border-radius: 8px;
            padding: 0.65rem 1.75rem;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.25s ease;
        }

        .btn-outline-gold:hover {
            background: rgba(201,168,76,0.1);
            color: #a07828;
            transform: translateY(-2px);
        }

        /* Quick links */
        .quick-links {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f0ede6;
        }

        .quick-links p {
            font-size: 0.78rem;
            color: #9ca3af;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .quick-link-item {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: #a07828;
            font-size: 0.85rem;
            text-decoration: none;
            margin: 0.25rem 0.5rem;
            transition: color 0.2s;
        }

        .quick-link-item:hover {
            color: #1a2a4a;
        }

        /* ── Footer ── */
        footer {
            background: linear-gradient(90deg, #f7f5f0 0%, #f0ede6 100%);
            border-top: 1px solid #e8e2d4;
            color: #555;
            padding: 18px 0;
            text-align: center;
            font-size: 13px;
        }

        /* ── Responsive ── */
        @media (max-width: 576px) {
            .error-card {
                padding: 2.5rem 1.5rem;
            }

            .error-code {
                font-size: 5rem;
            }

            .error-title {
                font-size: 1.3rem;
            }

            .noc-info h5 {
                font-size: 14px;
            }

            .noc-info p, .noc-info small {
                font-size: 11px;
            }

            .noc-logo {
                height: 38px;
            }
        }
    </style>
</head>
<body>

    <!-- 404 Content -->
    <div class="error-wrapper">
        <div class="error-card">

            <div class="blob-1"></div>
            <div class="blob-2"></div>


            <!-- 404 -->
            <div class="error-code">404</div>

            <div class="error-divider"></div>

            <!-- Message -->
            <h1 class="error-title">Page Not Found</h1>
            <p class="error-subtitle">
                The page you are looking for might have been removed,<br>
                had its name changed, or is temporarily unavailable.
            </p>

            <!-- Buttons -->
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <!-- <a href="{{ url('/') }}" class="btn-gold">
                    <i class="bi bi-house-door-fill"></i>
                    Go to Dashboard
                </a> -->
                <a href="javascript:history.back()" class="btn-outline-gold">
                    <i class="bi bi-arrow-left"></i>
                    Go Back
                </a>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p class="mb-0">&copy; {{ date('Y') }} Nepal Oil Corporation. All rights reserved.</p>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>