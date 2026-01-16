<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Fallback -->
     <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend_assets/favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend_assets/favicon.ico') }}" media="(prefers-color-scheme: dark)">
    <!-- Theme-specific favicons -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend_assets/favicon-wh.ico') }}" media="(prefers-color-scheme: light)">

    <!-- Preload critical resources -->

    
    @if (request()->is('admin/*'))
        <link rel="preload" href="{{ asset('admin_assets/custom.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    @else
        <link rel="preload" href="{{ asset('frontend_assets/style.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    @endif
    <noscript>
        <link rel="stylesheet" href="{{ asset('admin_assets/custom.css') }}">
    </noscript>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="{{ asset('admin_assets/vendors/custom/vendors/fontawesome5/css/all.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://fonts.googleapis.com/css2?family=Play&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Montserrat:wght@400;500;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Prevent FOUC - Hide body until styles are loaded */
        body {
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.1s ease-in-out;
        }

        /* Show body when styles are loaded */
        body.loaded {
            visibility: visible;
            opacity: 1;
        }

        /* Critical CSS for immediate rendering */
        .main-wrapr,
        .main_wrppr_alt {
            min-height: 100vh;
        }

        /* Loading state */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    @routes
    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body>
    @inertia

    <script>
        // Show body when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('loaded');
        });

        // Fallback: Show body after a short delay
        setTimeout(function() {
            document.body.classList.add('loaded');
        }, 100);
    </script>
</body>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
    integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
<!--select 2 js-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Tom Select Js -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

</html>
