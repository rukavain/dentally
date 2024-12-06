@extends('admin.dashboard')

@section('content')
    <style>
        .odontogram-container {
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .tooth-chart {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 8px;
            margin: 20px 0;
            width: 100%;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 768px) {
            .tooth-chart {
                grid-template-columns: repeat(4, 1fr);
                gap: 6px;
            }

            .tooth img {
                width: 50px;
                height: 70px;
            }

            .odontogram-container {
                padding: 10px;
            }

            .controls {
                justify-content: center;
            }

            .status-btn {
                padding: 6px 12px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .tooth-chart {
                grid-template-columns: repeat(3, 1fr);
                gap: 4px;
            }

            .tooth {
                padding: 4px;
            }

            .tooth img {
                width: 30px;
                height: 30px;
            }

            .tooth-number {
                font-size: 10px;
            }

            .status-btn {
                padding: 4px 8px;
                font-size: 12px;
            }

            .chart-label {
                font-size: 12px;
            }
        }

        .tooth {
            position: relative;
            cursor: default;
            border: 2px solid #ccc;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 8px;
            transition: all 0.2s;
            background: white;
            aspect-ratio: 1;
            width: 100%;
        }

        .tooth img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-bottom: 4px;
            z-index: 99;
        }

        .controls {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 8px;
        }

        .status-btn {
            padding: 8px 16px;
            margin: 5px;
            border: 2px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
        }

        .status-btn.preview-mode {
            cursor: default;
            opacity: 1;
            pointer-events: none;
            background: #f8f9fa;
        }

        .status-btn.clickable:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-btn.selected {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }

        .status-btn::before {
            content: '';
            display: inline-block;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-btn[data-status="normal"]::before {
            background-color: #98FB98;
        }

        .status-btn[data-status="decayed"]::before {
            background-color: #f44336;
        }

        .status-btn[data-status="filled"]::before {
            background-color: #2196F3;
        }

        .status-btn[data-status="missing"]::before {
            background-color: #9e9e9e;
        }

        .status-btn[data-status="crown"]::before {
            background-color: #ffd700;
        }

        .status-btn[data-status="bridge"]::before {
            background-color: #4CAF50;
        }

        .tooth-status {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 2;
            border-radius: 4px;
            opacity: 0.5;
        }

        .tooth.normal .tooth-status {
            background: #98FB98;
            border-color: #4CAF50;
        }

        .tooth.decayed .tooth-status {
            background: #FF5252;
            /* Red for decay */
        }

        .tooth.filled .tooth-status {
            background: #2196F3;
            /* Blue for filled */
        }

        .tooth.missing .tooth-status {
            background: #9E9E9E;
            /* Grey for missing */
            opacity: 0.7;
        }

        .tooth.crown .tooth-status {
            background: #FFD700;
            /* Gold for crown */
        }

        .tooth.bridge .tooth-status {
            background: #4CAF50;
            /* Green for bridge */
        }

        .edit-mode .tooth {
            cursor: pointer;
        }

        .tooth.selected {
            border-color: #4CAF50;
            background-color: #E8F5E9;
        }

        .tooth-number {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            z-index: 10;
        }

        .chart-section {
            margin-top: 20px;
        }

        .chart-row {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .chart-label {
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            font-size: 14px;
            text-align: center;
        }

        .edit-mode-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border: 2px solid #4CAF50;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            background-color: white;
            color: #4CAF50;
            font-weight: 500;
        }

        .edit-mode-btn.active {
            background-color: #4CAF50;
            color: white;
        }

        .edit-mode-btn img {
            width: 16px;
            height: 16px;
            opacity: 0.8;
        }

        /* Visual Feedback Styles */
        .tooth {
            position: relative;
            border: 2px solid #ccc;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 5px;
            background-color: white;
            transition: all 0.2s ease-in-out;
            cursor: default;
        }

        /* Edit mode specific styles */
        .edit-mode .tooth {
            cursor: pointer;
        }

        .edit-mode .tooth:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-color: #4CAF50;
        }

        .edit-mode .tooth::before {
            content: '✎';
            position: absolute;
            top: -8px;
            right: -8px;
            background: #4CAF50;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.2s ease-in-out;
        }

        .edit-mode .tooth:hover::before {
            opacity: 1;
            transform: scale(1);
        }

        /* Click animation */
        .tooth.clicked {
            animation: pulseClick 0.3s ease-in-out;
        }

        @keyframes pulseClick {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(0.95);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Status Preview Cursor */
        .status-preview {
            position: fixed;
            pointer-events: none;
            z-index: 1000;
            display: none;
            align-items: center;
            padding: 4px 8px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 12px;
            transform: translate(10px, 10px);
            border: 2px solid;
        }

        .status-preview.normal {
            border-color: #4CAF50;
            color: #4CAF50;
        }

        .status-preview.decayed {
            border-color: #f44336;
            color: #f44336;
        }

        .status-preview.filled {
            border-color: #2196F3;
            color: #2196F3;
        }

        .status-preview.missing {
            border-color: #9e9e9e;
            color: #9e9e9e;
        }

        .status-preview.crown {
            border-color: #ffd700;
            color: #ffd700;
        }

        .status-preview.bridge {
            border-color: #4CAF50;
            color: #4CAF50;
        }

        /* Notes Modal Styles */
        .notes-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .notes-modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            position: relative;
        }

        .notes-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .notes-modal-title {
            font-size: 18px;
            font-weight: bold;
        }

        .close-notes-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            color: #666;
        }

        .notes-textarea {
            width: 100%;
            min-height: 150px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
            font-family: inherit;
            resize: vertical;
        }

        .notes-textarea[readonly] {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }

        .save-notes-btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
            display: none;
        }

        .notes-modal.edit-mode .save-notes-btn {
            display: block;
        }

        .save-notes-btn:hover {
            background-color: #45a049;
        }

        .tooth-has-notes {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 10px;
            height: 10px;
            background-color: #FFC107;
            border-radius: 50%;
            z-index: 3;
        }

        .tooltip {
            display: none;
        }

        @media (max-width: 480px) {
            .notes-modal-content {
                width: 95%;
                padding: 15px;
            }
        }
    </style>

    <div class="m-4">
        @include('components.search')
    </div>

    <section class="bg-white m-4 p-8 max-lg:mt-12 shadow-lg rounded-md flex flex-col justify-center z-0">
        <div class="flex flex-col justify-between items-start">
            <div class="w-full flex justify-between gap-4 my-2">
                <a class="flex items-center justify-start gap-2 py-2 px-4 border border-gray-500 rounded-md max-lg:hidden hover:border-gray-700 hover:shadow-sm transition-all"
                    href="{{ route('show.patient', $patient->id) }}">
                    <img class="h-4" src="{{ asset('assets/images/arrow-back.png') }}" alt="">
                    <h1 class="text-sm">Return to patient information</h1>
                </a>
                <button id="editModeBtn" class="edit-mode-btn">
                    <span>Edit Mode</span>
                </button>
            </div>
        </div>

        <div class="odontogram-container">
            <h2 class="text-xl font-bold mb-4 ">Dental Record of {{ $patient->first_name }} {{ $patient->last_name }}</h2>

            <!-- Status Preview Element -->
            <div id="statusPreview" class="status-preview"></div>

            <div class="controls flex justify-center">
                <button class="status-btn" data-status="normal">Normal</button>
                <button class="status-btn" data-status="decayed">Decayed</button>
                <button class="status-btn" data-status="filled">Filled</button>
                <button class="status-btn" data-status="missing">Missing</button>
                <button class="status-btn" data-status="crown">Crown</button>
                <button class="status-btn" data-status="bridge">Bridge</button>
            </div>

            <div class="chart-section">
                <div class="chart-label">Upper Right</div>
                <div class="tooth-chart upper-right">
                    @for ($i = 1; $i <= 8; $i++)
                        <div class="tooth" data-tooth-number="{{ $i }}">
                            <img src="{{ asset('assets/tooth-images/' . $i . '.png') }}" alt="Tooth {{ $i }}">
                            <div class="tooth-number">{{ $i }}</div>
                            <div class="tooth-status"></div>
                        </div>
                    @endfor
                </div>

                <div class="chart-label">Upper Left</div>
                <div class="tooth-chart upper-left">
                    @for ($i = 9; $i <= 16; $i++)
                        <div class="tooth" data-tooth-number="{{ $i }}">
                            <img src="{{ asset('assets/tooth-images/' . $i . '.png') }}" alt="Tooth {{ $i }}">
                            <div class="tooth-number">{{ $i }}</div>
                            <div class="tooth-status"></div>
                        </div>
                    @endfor
                </div>

                <div class="chart-label">Lower Left</div>
                <div class="tooth-chart lower-left">
                    @for ($i = 24; $i >= 17; $i--)
                        <div class="tooth" data-tooth-number="{{ $i }}">
                            <img src="{{ asset('assets/tooth-images/' . $i . '.png') }}" alt="Tooth {{ $i }}">
                            <div class="tooth-number">{{ $i }}</div>
                            <div class="tooth-status"></div>
                        </div>
                    @endfor
                </div>

                <div class="chart-label">Lower Right</div>
                <div class="tooth-chart lower-right">
                    @for ($i = 32; $i >= 25; $i--)
                        <div class="tooth" data-tooth-number="{{ $i }}">
                            <img src="{{ asset('assets/tooth-images/' . $i . '.png') }}" alt="Tooth {{ $i }}">
                            <div class="tooth-number">{{ $i }}</div>
                            <div class="tooth-status"></div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <!-- Notes Modal -->
    <div id="notesModal" class="notes-modal">
        <div class="notes-modal-content">
            <div class="notes-modal-header">
                <h3 class="notes-modal-title">Notes for Tooth <span id="toothNumber"></span></h3>
                <button class="close-notes-modal">&times;</button>
            </div>
            <div id="notesTimestamp" class="notes-timestamp"></div>
            <textarea id="toothNotes" class="notes-textarea" placeholder="Add notes for this tooth..."></textarea>
            <button class="save-notes-btn">Save Notes</button>
        </div>
    </div>

    <input type="hidden" id="patientId" value="{{ $patient->id }}">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // Global variables
        let isEditMode = false;
        let selectedStatus = null;
        let currentToothElement = null;
        let currentToothNumber = null;
        let pressTimer;
        let toothNotes = {};

        // Function to update tooth appearance based on status
        function updateToothAppearance(tooth) {
            const status = $(tooth).data('status') || 'normal';
            // Remove all possible status classes
            $(tooth).removeClass('normal decayed filled missing crown bridge');
            // Add the current status class
            $(tooth).addClass(status);

            // Update note indicator
            const note = $(tooth).data('note');
            const noteIndicator = $(tooth).find('.tooth-has-notes');

            if (note && note.trim() !== '') {
                if (noteIndicator.length === 0) {
                    $(tooth).append('<div class="tooth-has-notes"></div>');
                }
            } else {
                noteIndicator.remove();
            }

            // Add hover effect in edit mode
            if (isEditMode) {
                $(tooth).addClass('editable');
            } else {
                $(tooth).removeClass('editable');
            }
        }

        // Function to update button states based on mode
        function updateButtonStates() {
            $('.status-btn').each(function() {
                const btn = $(this);
                // Remove click events in preview mode
                btn.prop('disabled', !isEditMode);
                // Update appearance for preview mode
                btn.toggleClass('preview-mode', !isEditMode);
                btn.toggleClass('clickable', isEditMode);
            });
        }

        // Handle tooth click
        function handleToothClick(event) {
            if (!isEditMode) return;

            const tooth = event.currentTarget;
            const currentStatus = $(tooth).data('status') || 'normal';
            const selectedBtn = $('.status-btn.selected');
            const selectedStatus = selectedBtn.length ? selectedBtn.data('status') : null;

            if (!selectedStatus) return;

            const newStatus = currentStatus === selectedStatus ? 'normal' : selectedStatus;
            $(tooth).data('status', newStatus);
            updateToothAppearance(tooth);

            // Save to backend
            const patientId = $('#patientId').val();
            const toothNumber = $(tooth).data('tooth-number');
            saveToothStatus(patientId, toothNumber, newStatus);
        }

        function openNotesModal(toothElement) {
            currentToothElement = toothElement;
            currentToothNumber = $(toothElement).data('tooth-number');

            $('#toothNumber').text(currentToothNumber);
            const existingNote = $(toothElement).data('note') || '';
            $('#toothNotes').val(existingNote);

            // Set readonly based on edit mode
            $('#toothNotes').prop('readonly', !isEditMode);

            // Show/hide save button based on edit mode
            $('.save-notes-btn').toggle(isEditMode);

            // Toggle edit mode class on modal
            $('.notes-modal').toggleClass('edit-mode', isEditMode);
            $('.notes-modal').css('display', 'flex');
        }

        function closeNoteModal() {
            $('.notes-modal').hide();
        }

        function saveNote() {
            const noteText = $('#toothNotes').val();
            $(currentToothElement).data('note', noteText);
            updateToothAppearance(currentToothElement);

            // Save to backend
            const patientId = $('#patientId').val();
            saveToothNote(patientId, currentToothNumber, noteText);

            closeNoteModal();
        }

        // Initialize everything when document is ready
        $(document).ready(function() {
            // Edit mode toggle
            $('#editModeBtn').on('click', function() {
                isEditMode = !isEditMode;
                $(this).toggleClass('active', isEditMode);
                $('body').toggleClass('edit-mode', isEditMode);
                $('.tooth').each(function() {
                    updateToothAppearance(this);
                });

                // Update button states
                updateButtonStates();

                // Reset selected status when exiting edit mode
                if (!isEditMode) {
                    $('.status-btn').removeClass('selected');
                    selectedStatus = null;
                }
            });

            // Status button click
            $('.status-btn').on('click', function() {
                if (!isEditMode) return;

                const wasSelected = $(this).hasClass('selected');
                $('.status-btn').removeClass('selected');

                if (!wasSelected) {
                    $(this).addClass('selected');
                    selectedStatus = $(this).data('status');
                } else {
                    selectedStatus = null;
                }
            });

            // Tooth click handlers
            $('.tooth').on('click', handleToothClick);

            // Right click for notes - allow in both modes
            $('.tooth').on('contextmenu', function(e) {
                e.preventDefault();
                openNotesModal(this);
            });

            // Long press for notes - allow in both modes
            $('.tooth').on('touchstart', function(e) {
                pressTimer = setTimeout(() => {
                    openNotesModal(this);
                }, 500);
            });

            $('.tooth').on('touchend', function() {
                clearTimeout(pressTimer);
            });

            // Notes modal handlers
            $('.close-notes-modal').on('click', closeNoteModal);
            $('.save-notes-btn').on('click', saveNote);

            // Close modal when clicking outside
            $('.notes-modal').on('click', function(e) {
                if (e.target === this) {
                    closeNoteModal();
                }
            });

            // Load initial data
            const patientId = $('#patientId').val();
            loadPatientTeeth(patientId);

            // Initialize button states
            updateButtonStates();
        });

        // AJAX functions
        function loadPatientTeeth(patientId) {
            $.ajax({
                url: `{{ route('teeth.get', ['patientId' => ':patientId']) }}`.replace(':patientId', patientId),
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(teethData) {
                    Object.entries(teethData).forEach(([number, data]) => {
                        const tooth = $(`[data-tooth-number="${number}"]`);
                        if (tooth.length) {
                            tooth.data('status', data.status);
                            if (data.note) {
                                tooth.data('note', data.note.note_text);
                            }
                            updateToothAppearance(tooth[0]);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading teeth data:', error);
                    alert('Failed to load teeth data. Please try again.');
                }
            });
        }

        function saveToothStatus(patientId, toothNumber, status) {
            $.ajax({
                url: `{{ route('teeth.status.update', ['patientId' => ':patientId', 'toothNumber' => ':toothNumber']) }}`
                    .replace(':patientId', patientId)
                    .replace(':toothNumber', toothNumber),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    status: status
                },
                success: function(result) {
                    console.log('Status saved:', result);
                },
                error: function(xhr, status, error) {
                    console.error('Error saving tooth status:', error);
                    alert('Failed to save tooth status. Please try again.');
                }
            });
        }

        function saveToothNote(patientId, toothNumber, note) {
            $.ajax({
                url: `{{ route('teeth.note.update', ['patientId' => ':patientId', 'toothNumber' => ':toothNumber']) }}`
                    .replace(':patientId', patientId)
                    .replace(':toothNumber', toothNumber),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    note: note
                },
                success: function(result) {
                    console.log('Note saved:', result);
                },
                error: function(xhr, status, error) {
                    console.error('Error saving tooth note:', error);
                    alert('Failed to save tooth note. Please try again.');
                }
            });
        }
    </script>
@endsection
