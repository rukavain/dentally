<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }} | Dentist Dashboard </title>
    <link rel="icon" href="{{ asset('/images/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
{{--
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

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

<body class="bg-slate-100" x-data>
    <section class="flex justify-start items-start">
        <div>
            <div class="h-full">
                @include('components.sidebar')
            </div>
        </div>
        <div class="max-lg:mt-10 max-xl:self-start max-xl:justify-self-center w-full">
            <div class="m-2">
                @include('components.search')
            </div>
            @yield('content')
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('[data-tab-target]');
            const activeClass = 'border-b-green-600';

            if (tabs.length > 0) {
                tabs[0].classList.add(activeClass);
                document.querySelector('#tab1')?.classList.remove('hidden');

                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        const targetContent = document.querySelector(tab.dataset.tabTarget);
                        if (!targetContent) return;

                        document.querySelectorAll('.tab-content').forEach(content =>
                            content.classList.add('hidden')
                        );
                        targetContent.classList.remove('hidden');

                        document.querySelectorAll('.border-b-green-600').forEach(activeTab =>
                            activeTab.classList.remove(activeClass)
                        );
                        tab.classList.add(activeClass);
                    });
                });
            }
        });
    </script>
</body>
</html>
