<section class="sdg-footer">
    <img src="{{asset('assets/images/sdg-3.jpg')}}" alt="SDG 3" class="sdg-image">
    <img src="{{asset('assets/images/sdg-4.jpg')}}" alt="SDG 4" class="sdg-image">
    <img src="{{asset('assets/images/sdg-9.jpg')}}" alt="SDG 9" class="sdg-image">
    <img src="{{asset('assets/images/sdg-11.jpg')}}" alt="SDG 11" class="sdg-image">
</section>

<style>
    .sdg-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 10px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 20px;
        z-index: 1000;
    }

    .sdg-image {
        height: 40px;
        width: auto;
        transition: transform 0.3s ease;
    }

    .sdg-image:hover {
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .sdg-image {
            height: 40px;
        }

        .sdg-footer {

        display: flex;
        justify-content: center;
        align-items: center;

    }
    }
</style>