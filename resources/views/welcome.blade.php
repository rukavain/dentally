@extends('components.layout')
@section('title')
    | Home
@endsection
@section('content')
    <section class="flex flex-wrap justify-between md:justify-center my-24 mx-8 items-start ">
        <div
            class="bg-white flex flex-col py-11 px-12 max-lg:py-5 max-lg:px-8 max-lg:mx-2 mx-8 rounded-lg shadow-lg gap-6 md:max-w-lg ">
            <h1 class="text-7xl font-bold max-md:text-5xl">
                Look forward to the dentist
            </h1>
            <h1 class="text-lg max-lg:text-sm">Top-rated clinicians</h1>
            <div class="w-1/2 py-2 flex flex-col justify-center items-start gap-4">
                {{-- <a class="max-w-sm text-center mt-4" href="{{ route('appointments.request') }}"> --}}
                {{-- <a class="max-w-sm text-center mt-4" href="{{ route('welcome') }}"> --}}
                <a href="{{ route('login') }}"
                    class="min-w-max  bg-green-600 rounded-md py-4 max-md:text-sm px-8 font-bold text-white hover:bg-green-700 transition-all">
                    BOOK NOW
                </a>
                <h1 class="text-sm min-w-max">
                    OR CALL: <span class="font-bold">0927 802 2807</span>
                </h1>
            </div>
        </div>
        <div class="w-2/5 flex justify-center items-center  max-lg:hidden">
            <img class="h-max rounded-lg" src="{{ asset('assets/images/hero-image.jpg') }}" alt="">
        </div>
    </section>
    <section class="bg-green-600 flex flex-col justify-center items-center py-8 my-4 shadow-lg ">
        <div class="max-w-5xl mb-9 px-7">
            <h1 class="text-white text-7xl max-md:text-4xl text-center font-bold">
                Feel amazing about your oral health
            </h1>
        </div>
        <div class="flex flex-col justify-center items-start max-lg:items-center max-sm:items-center my-5 gap-8 px-4">
            <div
                class="flex max-lg:flex-col max-lg:items-center max-lg:justify-center max-lg:gap-3 gap-6 justify-center items-center">
                <h1 class="bg-white text-slate-900 p-6 rounded-full font-bold text-md">
                    15+
                </h1>
                <div
                    class="flex px-1 flex-col justify-start max-lg:justify-center max-lg:items-center max-lg:text-center items-start max-lg:flex-col ">
                    <h1 class="text-white font-semibold max-lg:w-full text-4xl max-md:text-2xl">
                        Decades of experiences</h1>
                    <h1 class="text-white text-sm">Our clinical team is led by renowned clinicians.</h1>
                </div>
            </div>
            <div
                class="flex max-lg:flex-col max-lg:items-center max-lg:justify-center max-lg:gap-3 gap-6 justify-center items-center ">
                <h1 class="bg-white text-green-800 p-4 rounded-full font-bold text-md">
                    <img class="h-11 object-contain" src="{{ asset('assets/images/atom.png') }}" alt="">
                </h1>
                <div
                    class="flex px-1 flex-col justify-start max-lg:justify-center max-lg:items-center  max-lg:text-center items-start max-lg:flex-col">
                    <h1 class="text-white font-semibold max-lg:w-full text-4xl max-md:text-2xl">Science-based care</h1>
                    <h1 class="text-white text-sm">We take advantage of all the latest research.</h1>
                </div>
            </div>
            <div
                class="flex max-lg:flex-col max-lg:items-center max-lg:justify-center max-lg:gap-3 gap-6 justify-center items-center ">
                <h1 class="bg-white text-green-800 p-4 rounded-full font-bold text-md">
                    <img class="h-11 object-contain" src="{{ asset('assets/images/tooth.png') }}" alt="">
                </h1>
                <div
                    class="flex px-1 flex-col justify-start max-lg:justify-center max-lg:items-center  max-lg:text-center items-start max-lg:flex-col">
                    <h1 class="text-white font-semibold max-lg:w-full text-4xl max-md:text-2xl">Only the care you need</h1>
                    <h1 class="text-white text-sm">Our dentists don't make a commission on procedures.</h1>
                </div>
            </div>
            <a href="https://www.facebook.com/ToothImpressions" class="my-4 self-center">
                <h1
                    class="bg-white text-gray-900 font-semibold text-md py-4 px-8 rounded-md hover:bg-gray-900 hover:text-white transition-all self-center">
                    Learn more</h1>
            </a>
        </div>
    </section>
    <section class="flex justify-evenly items-center flex-wrap gap-4 my-8 max-lg:justify-center max-lg:items-center ">
        <div
            class="flex flex-col justify-start gap-4 max-w-xl p-12 rounded-md bg-green-600 max-lg:bg-white text-white shadow-lg hover:bg-green-700 transition-all cursor-pointer mx-8 max-lg:mx-4 max-lg:text-gray-900 max-lg:w-full ">
            <h1 class="text-6xl font-bold max-lg:text-5xl">
                Services
            </h1>
            <h1 class="font-semibold text-xl max-lg:text-lg">
                Exceptional dental care for a healthier, brighter smile.
            </h1>
            <span class="text-md my-2 max-lg:text-sm">
                Skilled and compassionate dental professionals prioritize patient comfort and satisfaction, ensuring
                a
                gentle and thorough experience. Emphasis is placed on education for proper oral hygiene practices to
                maintain long-term dental health.
            </span>
        </div>
        <div
            class="flex flex-wrap justify-evenly items-center max-lg:items-stretch gap-12 mx-6 p-6 max-lg:mx-2 max-lg:max-w-md max-lg:p-2 max-lg:gap-2 bg-white shadow-lg rounded-lg w-1/2 max-lg:w-full max-w-5xl self-end max-lg:justify-center">
            <div
                class=" flex flex-col justify-center items-center max-w-md bg-green-600  rounded-md py-4 px-2 max-lg:py-2 max-lg:px-1 max-lg:min-h-full max-lg:min-w-max hover:mt-[-15px] hover:bg-green-800 cursor-pointer transition-all">
                <img class="h-44 bg-white m-4 p-5 rounded-md object-contain max-lg:h-20 max-lg:m-2 max-lg:p-2"
                    src="{{ asset('assets/images/root-canal.png') }}" alt="">
                <h1 class="text-white font-semibold text-center max-lg:text-sm">Root Canal</h1>

            </div>
            <div
                class="flex flex-col justify-center items-center max-w-md bg-green-600 rounded-md py-4 px-2 max-lg:py-2 max-lg:px-1 max-lg:min-h-full hover:mt-[-15px] hover:bg-green-800 cursor-pointer transition-all">
                <img class=" h-44 bg-white m-4 p-5 rounded-md object-contain max-lg:h-20 max-lg:m-2 max-lg:p-2"
                    src="{{ asset('assets/images/tooth-extraction.png') }}" alt="">
                <h1 class="text-white font-semibold text-center max-lg:text-sm max-lg:max-w-min">Tooth Extraction</h1>
            </div>
            <div
                class="flex flex-col justify-center items-center max-w-md bg-green-600 rounded-md py-4 px-2 max-lg:py-2 max-lg:px-1max-lg:min-h-full  hover:mt-[-15px] hover:bg-green-800 cursor-pointer transition-all">
                <img class=" h-44 bg-white m-4 p-5 rounded-md object-contain max-lg:h-20 max-lg:m-2 max-lg:p-2"
                    src="{{ asset('assets/images/dental-restoration.png') }}" alt="">
                <h1 class="text-white font-semibold text-center max-lg:text-sm max-lg:max-w-min">Dental Restoration</h1>
            </div>
            <div
                class="flex flex-col justify-center items-center max-w-md bg-green-600 rounded-md py-4 px-2 max-lg:py-2 max-lg:px-1 max-lg:min-h-full  hover:mt-[-15px] hover:bg-green-800 cursor-pointer transition-all">
                <img class=" h-44 bg-white m-4 p-5 rounded-md object-contain max-lg:h-20 max-lg:m-2 max-lg:p-2"
                    src="{{ asset('assets/images/dental-sealant.png') }}" alt="">
                <h1 class="text-white font-semibold text-center max-lg:text-sm max-lg:max-w-min">Dental Sealant</h1>
            </div>
            <div
                class="flex flex-col justify-center items-center max-w-md bg-green-600 rounded-md py-4 px-2 max-lg:py-2 max-lg:px-1 max-lg:min-h-full  hover:mt-[-15px] hover:bg-green-800 cursor-pointer transition-all">
                <img class=" h-44 bg-white m-4 p-5 rounded-md object-contain max-lg:h-20 max-lg:m-2 max-lg:p-2"
                    src="{{ asset('assets/images/braces.png') }}" alt="">
                <h1 class="text-white font-semibold text-center max-lg:text-sm max-lg:max-w-min">Braces</h1>
            </div>
            <div
                class="flex flex-col justify-center items-center max-w-md bg-green-600 rounded-md py-4 px-2 max-lg:py-2 max-lg:px-1 max-lg:min-h-full  hover:mt-[-15px] hover:bg-green-800 cursor-pointer transition-all">
                <img class=" h-44 bg-white m-4 p-5 rounded-md object-contain max-lg:h-20 max-lg:m-2 max-lg:p-2"
                    src="{{ asset('assets/images/dental-cleaning.png') }}" alt="">
                <h1 class="text-white font-semibold text-center max-lg:text-sm max-lg:max-w-min">Dental Cleaning</h1>
            </div>
        </div>
    </section>

    @include('components.location')
    <div class="w-full flex justify-center items-center">
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif
    </div>
    @include('components.footer')
@endsection
