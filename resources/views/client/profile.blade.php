<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }} @yield('title') </title>
    <link rel="icon" href="{{ asset('/images/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body class="bg-green-200">
    <section class="flex justify-start items-start">
        <div>
            <div class="h-full">
                @include('components.profile-sidebar')
            </div>
        </div>
        <div class="max-lg:mt-10 max-xl:self-start max-xl:justify-self-center w-full">
            <div class="m-2">
                @include('components.search')
            </div>
            @yield('content')
        </div>
    </section>
</body>
<script>
    const tabs = document.querySelectorAll('[data-tab-target]');
    const activeClass = 'border-b-green-600';

    tabs[0].classList.add(activeClass);
    document.querySelector('#tab1').classList.remove('hidden');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetContent = document.querySelector(tab.dataset.tabTarget);

            document.querySelectorAll('.tab-content').forEach(content => content.classList.add(
                'hidden'));
            targetContent.classList.remove('hidden');

            document.querySelectorAll('.border-b-green-600').forEach(activeTab => activeTab.classList
                .remove(
                    activeClass));
            tab.classList.add(activeClass);
        })
    });
</script>

</html>
