@extends('admin.dashboard')

@section('content')
    <style>
        .upload-modal {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            /* Overlay */
        }

        .upload-modal-dialog {
            position: relative;
            margin: auto;
            top: 20%;
            width: 50%;
        }

        .upload-modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }

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
    @if (session('success'))
        @include('components.toast-notification')
    @endif
    <div class="m-4">
        @include('components.search')
    </div>
    <section class="bg-white m-4 p-8 max-lg:mt-12 shadow-lg rounded-md flex flex-col justify-center z-0">
        <div class="flex flex-col justify-between items-start ">
            <div class="w-full flex justify-between gap-4 my-2">
                <a class=" flex items-center justify-start gap-2 py-2 px-4 border border-gray-500 rounded-md max-lg:hidden hover:border-gray-700 hover:shadow-sm transition-all"
                    href=" {{ route('show.patient', $patient->id) }} ">
                    <img class="h-4" src="{{ asset('assets/images/arrow-back.png') }}" alt="">
                    <h1 class="text-sm">
                        Return to patient information</h1>
                </a>
                <div class="flex self-start text-sm max-md:text-xs">
                    <div
                        class=" flex items-center justify-start gap-2 py-2 px-4 border border-gray-500 rounded-md hover:border-gray-700 hover:shadow-sm transition-all">
                        <button id="openModalBtn" class="btn flex items-center justify-start gap-2">
                            <img class="h-4" src="{{ asset('assets/images/add.png') }}" alt="">
                            <h3>Upload Image</h3>
                        </button>
                    </div>
                    {{-- Aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa --}}
                    <!-- Modal Structure -->
                    <div id="backgroundModal" class="upload-modal" style="display:none;">
                        <div class="upload-modal-dialog">
                            <div class="upload-modal-content">
                                <div class="modal-header flex justify-between">
                                    <h5 class="modal-title text-2xl font-bold max-md:text-sm">Upload Image</h5>
                                    <button type="button" class="close text-3xl" id="closeModalBtn">&times;</button>
                                </div>
                                <form id="uploadForm" action="{{ route('upload.image') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    <div class="modal-body p-4 m-4 border">
                                        <label for="image">Choose Image:</label>
                                        <input type="file" id="image" name="image" accept="image/*" required>
                                    </div>
                                    <input type="hidden" id="image_type" name="image_type" value="background">

                                    <div class="modal-footer flex justify-end">
                                        <button type="button"
                                            class="btn border bg-gray-600 text-white rounded-md py-2 px-4"
                                            id="closeModalFooterBtn">Close</button>
                                        <button type="submit"
                                            class="btn border bg-green-600 text-white rounded-md py-2 px-4">Upload</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa --}}

                </div>
            </div>
            <h1 class="text-2xl font-bold mb-2">
                Patient background images:
            </h1>
            <div class="w-full flex justify-start">
                @if ($backgroundImages->isEmpty())
                    <p>No background images uploaded for this patient.</p>
                @else
                    <div class="flex gap-2">
                        @foreach ($backgroundImages as $image)
                            <div class="border-2 m-2">
                                <h1>{{ $image->created_at }}</h1>
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Contract Image"
                                    class="img-fluid max-h-80"
                                    onclick="openModal('{{ asset('storage/' . $image->image_path) }}')">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div id="imageModal" class="image-modal hidden">
                <div class=" p-4 rounded">
                    <span id="closeModal"
                        class="fixed right-5 cursor-pointer text-3xl text-white bg-green-500 rounded-full px-2">&times;</span>
                    <img id="modalImage" src="" alt="Modal Image" class="img-fluid max-h-screen">
                </div>
            </div>
        </div>
    </section>
    <script>
        // Get modal and buttons
        const modal = document.getElementById("backgroundModal");
        const openModalBtn = document.getElementById("openModalBtn");
        const closeModalBtn = document.getElementById("closeModalBtn");
        const closeModalFooterBtn = document.getElementById("closeModalFooterBtn");

        // Function to open the modal
        openModalBtn.addEventListener("click", function() {
            modal.style.display = "block";
        });

        // Function to close the modal
        closeModalBtn.addEventListener("click", function() {
            modal.style.display = "none";
        });

        closeModalFooterBtn.addEventListener("click", function() {
            modal.style.display = "none";
        });

        // Close the modal if the user clicks outside of it
        window.addEventListener("click", function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });

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
