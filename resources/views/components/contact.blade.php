<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    @vite('resources/css/app.css')
</head>

<body class="bg-slate-100">
    <section
        class="flex flex-col justify-center items-center my-8 shadow-lg rounded-md min-w-3xl max-w-5xl bg-white p-6">
        {{-- <form action="{{ route('submit.contact') }}" method="POST" class="flex justify-center items-center flex-col max-w-3xl bg-white p-8 rounded-md "> --}}
        @csrf
        <h1 class="text-slate-900 font-bold text-6xl text-center max-lg:text-4xl">Contact Us</h1>
        <h1 class="text-center my-4 max-lg:text-md">
            Consult with our team online by filling out the form below. If you have specific inquiries regarding our
            services, please don't hesitate to get in touch. We will respond as soon as possible.
        </h1>
        <div class="flex flex-col justify-center items-start gap-7 w-full mt-6">
            <input autocomplete="off" required class="py-2 px-4 rounded-md w-full border border-gray-600"
                placeholder="Name" type="text" name="name" id="name">
            <input autocomplete="off" required class="py-2 px-4 rounded-md w-full border border-gray-600"
                placeholder="Email" type="email" name="email" id="email">
            <textarea class="py-2 px-4 rounded-md w-full border border-gray-600" placeholder="Message" name="message" id="message"
                type="text"></textarea>
            <button
                class="py-4 px-8 font-semibold rounded-md bg-green-600 text-white hover:bg-green-700 transition-all my-5"
                type="submit">
                Send
            </button>
        </div>
        </form>
    </section>
</body>

</html>
