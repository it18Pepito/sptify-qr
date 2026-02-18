<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#053725">
    <title>Download Apps</title>
    <link rel="icon" type="image/webp" href="{{ asset('assets/pepi-plus-logo.webp') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100dvh;
            background: linear-gradient(135deg, #70A843 0%, #053725 100%);
            color: white;
            padding: 20px;
        }

        .container {
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .logo {
            width: 120px;
            height: 119px;
            margin: 0 auto 30px;
            border-radius: 26px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            background: white;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 12px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .subtitle {
            opacity: 0.95;
            font-size: 18px;
            margin-bottom: 40px;
            font-weight: 400;
        }

        .store-buttons {
            display: flex;
            flex-direction: column;
            gap: 16px;
            align-items: center;
            margin-bottom: 30px;
        }

        .store-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 14px 32px;
            background: rgba(255, 255, 255, 0.95);
            color: #000;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 280px;
            position: relative;
            overflow: hidden;
        }

        .store-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 1);
        }

        .store-button:active {
            transform: translateY(0);
        }

        .store-button svg {
            width: 28px;
            height: 28px;
            flex-shrink: 0;
        }

        .button-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.3;
        }

        .button-text .small {
            font-size: 11px;
            font-weight: 400;
            opacity: 0.7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .button-text .large {
            font-size: 18px;
            font-weight: 700;
        }

        .app-store-btn {
            background: linear-gradient(135deg, #3A3A3C 0%, #000000 100%);
            color: white;
        }

        .app-store-btn:hover {
            background: linear-gradient(135deg, #4A4A4C 0%, #1A1A1A 100%);
        }

        .play-store-btn .button-text .large {
            background: linear-gradient(90deg, #01875f 0%, #4285f4 50%, #ea4335 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            opacity: 0.7;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        .divider span {
            padding: 0 15px;
            font-size: 14px;
            font-weight: 500;
        }

        .features {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 40px;
            opacity: 0.9;
        }

        .feature {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .feature-text {
            font-size: 13px;
            font-weight: 500;
        }

        .loader {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 30px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .checkmark {
            width: 70px;
            height: 70px;
            margin: 30px auto;
            display: none;
        }

        .checkmark circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke: white;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark path {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            stroke: white;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.4s forwards;
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }

        .hidden {
            display: none;
        }

        .show {
            display: block;
        }

        .redirect-message {
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 20px;
            font-weight: 500;
            opacity: 0.95;
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 28px;
            }

            .subtitle {
                font-size: 16px;
            }

            .store-button {
                max-width: 100%;
            }
        }
    </style>
<script>
    window.onload = function() {
        setTimeout(function() {

            const redirectUrl = @json($redirectUrl ?? null);
            const storeName = @json(
                $storeType === 'app_store'
                    ? 'App Store'
                    : 'Google Play Store'
            );

            if (!redirectUrl) {
                console.error('Redirect URL not found');
                return;
            }

            // Update subtitle
            document.querySelector('.subtitle').textContent =
                'Taking you to the ' + storeName + '...';

            // Redirect after delay (FLOW SAMA)
            setTimeout(function() {
                window.location.href = redirectUrl;

                // After redirect animation
                setTimeout(function() {
                    document.querySelector('.loader').style.display = 'none';
                    document.querySelector('.checkmark').classList.add('show');
                    document.querySelector('.subtitle').textContent = '';
                    document.querySelector('.redirect-message').textContent =
                        'Thanks for downloading!';
                }, 500);

            }, 400);

        }, 800);
    };
</script>

</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="{{ asset($app->logo) }}" alt="{{ $app->app_name }} Logo">
        </div>

        <h1>{{ $app->app_name }}</h1>
        <p class="subtitle">Your Privilege, Infinite Rewards</p>

        <div class="loader"></div>

        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle cx="26" cy="26" r="25" fill="none" />
            <path fill="none" stroke-width="4" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
        </svg>

        <p class="redirect-message"></p>

        <div class="store-buttons">
            <a href="{{ $storeType === 'app_store' ? $redirectUrl : '#' }}" class="store-button app-store-btn"
                target="_blank">
                <img src="{{ asset('assets/apple-logo.png') }}" style="width: 24px" />
                <div class="button-text">
                    <span class="small">Download on the</span>
                    <span class="large">App Store</span>
                </div>
            </a>

            <a href="{{ $storeType === 'play_store' ? $redirectUrl : '#' }}"
                class="store-button play-store-btn" target="_blank">
                <img src="{{ asset('assets/playstore-logo.png') }}" style="width: 24px" />
                <div class="button-text">
                    <span class="small">Get it on</span>
                    <span class="large">Google Play</span>
                </div>
            </a>
        </div>
    </div>
</body>

</html>
