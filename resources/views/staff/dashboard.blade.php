<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }} | Staff Dashboard </title>
    <link rel="icon" href="{{ asset('/images/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    @vite('resources/css/app.css')
</head>

<body>
    <section class="h-screen w-full  bg-slate-100 flex justify-start">
        <div>
            @include('components.sidebar')
        </div>
        <div class="w-full">
            @yield('content')
        </div>
    </section>
    <hr>
    {{-- @include('components.footer') --}}
</body>

</html>
