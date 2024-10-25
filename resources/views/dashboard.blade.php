<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }} | Admin Dashboard </title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        /* styling for contact tooltips */
        .tooltip {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        details[open] .dropdown-content {
            max-height: 500px;
            opacity: 1;
            transition: max-height 0.4s ease-in-out, opacity 0.4s ease-in-out;
        }

        .dropdown-content {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-in-out, opacity 0.4s ease-in-out;
        }
    </style>
</head>

<body>
    <div id="overlay"></div>
    <div id="dropdown-overlay"></div>
    <section class="w-full max-md:h-max md:h-full bg-green-200 flex justify-start">
        <div>
            @include('components.sidebar')
        </div>
        <div class="w-full max-lg:mt-12">
            @yield('content')
        </div>
    </section>
    <hr>
</body>

</html>
