<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <title>Document</title>
</head>

<body>
    <hr>
    <footer class="flex flex-col justify-center items-center py-8 px-4 z-20 bg-white">
        <section
            class="flex  gap-20 max-md:gap-4 max-md:hidden max-md:text-sm justify-evenly w-full items-start py-4 mb-12">
            <div>
                <h1>
                    STAY IN TOUCH
                </h1>
                <hr class="border-t-2 border-green-700 my-4">
                <div class="flex flex-col gap-8">
                    <div class="flex justify-start items-center gap-4">
                        <img class="h-8" src="{{ asset('assets/images/calendar.png') }}" alt="">
                        <h1>Monday - Sunday: 9:00 AM - 5:00 PM</h1>
                    </div>
                    <div class="flex justify-start items-center gap-4">
                        <img class="h-8" src="{{ asset('assets/images/telephone.png') }}" alt="">
                        <h1>0927 802 2807</h1>
                    </div>
                    <div class="flex justify-start items-center gap-4">
                        <img class="h-8" src="{{ asset('assets/images/location.png') }}" alt="">
                        <h1>Mabalacat, Philippines, 2010</h1>
                    </div>
                </div>
            </div>
            <div>
                <h1>PRACTICE</h1>
                <hr class="my-4 border-t-2 border-green-700">
                <div class="flex flex-col gap-8">
                    <h1>About</h1>

                    <h1>Contact</h1>
                    <h1>Patient Referral</h1>
                    <h1>Payment</h1>
                </div>
            </div>
            <div>
                <h1>
                    SERVICES
                </h1>
                <hr class="my-4 border-t-2 border-green-700">
                <div class="flex flex-col gap-8">
                    <h1>Orthodontics</h1>
                    <h1>Tooth Extraction</h1>
                    <h1>Tooth Filling</h1>
                    <h1>Braces</h1>
                </div>
            </div>

            <div>
                <h1>
                    SOCIAL MEDIA
                </h1>
                <hr class="border-t-2 border-green-700 my-4">
                <div class="flex flex-wrap">
                    <a target="blank" href="https://www.facebook.com/ToothImpressions">
                        <img class="h-12" src="{{ asset('assets/images/facebook-icon.png') }}" alt="">
                    </a>
                </div>
            </div>
        </section>
        <hr class="border-t-2 w-full py-4 border-green-700">
        <section class="flex justify-evenly max-md:flex-col max-md:items-center items-end w-full gap-4">

            <div class="flex flex-col max-md:text-sm text-center justify-center items-center gap-4">
                <div class="flex justify-center gap-4">
                <img class="h-12" src="{{ asset('assets/images/mcc-logo.png') }}" alt="">
                <img class="h-12" src="{{ asset('assets/images/logo.png') }}" alt="">
                <img class="h-12" src="{{ asset('assets/images/ibce-logo.png') }}" alt="">
            </div>
                <h1>&copy;Tooth Impressions Dental Clinic</h1>
            </div>

        </section>
    </footer>
</body>

</html>
