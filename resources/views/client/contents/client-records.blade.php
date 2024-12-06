@extends('client.profile')
<style>
    .image-modal {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        display: flex;
        justify-content: center;
        overflow-y: auto;
        background-size: contain;
        /* Overlay */
    }
</style>
@section('content')
    <section class="w-full flex justify-center gap-5 max-h-max p-6 mx-auto max-xl:mt-14">
        <!-- Main Content -->
        <div class="w-full bg-white p-4 rounded-lg shadow-md max-xl:text-xs">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-4">
                <nav class="flex flex-wrap gap-5">
                    <button
                        class="text-gray-500 pb-2 border-b-2 border-transparent focus:outline-none hover:border-b-green-300 transition-all"
                        data-tab-target="#tab1">Dental Chart
                    </button>
                    <button
                        class="text-gray-500 pb-2 border-b-2 border-transparent focus:outline-none hover:border-b-green-300 transition-all"
                        data-tab-target="#tab2">X-rays
                    </button>
                    <button
                        class="text-gray-500 pb-2 border-b-2 border-transparent focus:outline-none hover:border-b-green-300 transition-all"
                        data-tab-target="#tab3">Contract
                    </button>
                </nav>
            </div>

            {{-- Image Modal --}}
            <div id="imageModal" class="image-modal hidden">
                <div class=" p-4 rounded">
                    <span id="closeModal"
                        class="fixed right-5 cursor-pointer text-3xl text-white bg-green-500 rounded-full px-2">&times;</span>

                    <img id="modalImage" src="" alt="Modal Image" class="max-h-screen img-fluid">
                </div>
            </div>

            <div>
                <div id="tab1" class="tab-content text-gray-700 max-h-max">
                    @include('client.contents.dental-chart', ['completeTeeth' => $completeTeeth ?? []])
                </div>
                <div id="tab2" class="tab-content text-gray-700 hidden max-h-max">
                    @if ($xrayImages->isEmpty())
                        <div class="flex flex-col justify-center items-center my-5 gap-4">
                            <img class="h-48" src="{{ asset('assets/images/x-ray.png') }}" alt="">
                            <p class="text-center">No patient x-ray images uploaded for this patient.</p>
                        </div>
                    @else
                        <div class="flex flex-wrap justify-center">
                            @foreach ($xrayImages as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="X-ray Image"
                                    class="img-fluid max-h-56"
                                    onclick="openModal('{{ asset('storage/' . $image->image_path) }}')">
                            @endforeach
                        </div>
                    @endif
                </div>
                <div id="tab3" class="tab-content text-gray-700 hidden max-h-max">
                    @if ($contractImage)
                        <div class="flex justify-center">
                            <img src="{{ asset('storage/' . $contractImage->image_path) }}" alt="Contract Image"
                                class="img-fluid max-h-96"
                                onclick="openModal('{{ asset('storage/' . $contractImage->image_path) }}')">
                        </div>
                    @else
                        <div class="flex flex-col justify-center items-center my-5 gap-4">
                            <img class="h-48" src="{{ asset('assets/images/contract.png') }}" alt="">
                            <p class="text-center">No patient contract images uploaded for this patient.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <script>
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
            modal.classList.remove('hidden');
        }

        document.getElementById('closeModal').onclick = function() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside of the image
        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        }
    </script>

@endsection
