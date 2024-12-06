@php
    $patient = Auth::user()->patient;
@endphp
{{-- Make sure jQuery is loaded --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<div class="dental-chart-container">
    <div class="dental-chart">
        <!-- Status Preview Element -->
        <div id="statusPreview" class="status-preview"></div>
        <div class="controls">
            <button class="status-btn" data-status="normal">Normal</button>
            <button class="status-btn" data-status="decayed">Decayed</button>
            <button class="status-btn" data-status="filled">Filled</button>
            <button class="status-btn" data-status="missing">Missing</button>
            <button class="status-btn" data-status="crown">Crown</button>
            <button class="status-btn" data-status="bridge">Bridge</button>
        </div>
        <div class="chart-section">
            <div class="chart-label">Upper</div>
            <div class="tooth-chart upper">
                @for ($i = 1; $i <= 16; $i++)
                    <div class="tooth" data-tooth-number="{{ $i }}">
                        <span class="tooth-label">{{ $i }}</span>
                        <img src="{{ asset('assets/tooth-images/' . $i . '.png') }}" alt="Tooth {{ $i }}">
                    </div>
                @endfor
            </div>
            <div class="tooth-chart lower">
                @for ($i = 17; $i <= 32; $i++)
                    <div class="tooth" data-tooth-number="{{ $i }}">
                        <img src="{{ asset('assets/tooth-images/' . $i . '.png') }}" alt="Tooth {{ $i }}">
                        <span class="tooth-label">{{ $i }}</span>
                    </div>
                @endfor
            </div>
            <div class="chart-label">Lower</div>
        </div>
    </div>
    <!-- Notes Modal -->
    <div class="notes-modal">
        <div class="notes-modal-content">
            <div class="notes-modal-header">
                <h3>Notes for Tooth <span id="toothNumber"></span></h3>
                <button class="close-notes-modal">&times;</button>
            </div>
            <div class="notes-modal-body">
                <textarea id="toothNotes" readonly></textarea>
            </div>
        </div>
    </div>
</div>
<style>
    .dental-chart-container {
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .controls {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
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
        cursor: default;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8f9fa;
        pointer-events: none;
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

    .chart-section {
        margin: 20px 0;
    }

    .chart-label {
        text-align: center;
        font-weight: bold;
        margin: 10px 0;
    }

    .tooth-chart {
        display: flex;
        justify-content: center;
        gap: 2px;
        margin-bottom: 20px;
    }

    .tooth {
        width: 70px;
        height: 100px;
        border: 2px solid #ccc;
        border-radius: 4px;
        display: flex;
        flex-flow: column;
        align-items: center;
        justify-content: center;
        position: relative;
        background: white;
    }

    .tooth img {
        width: 70px;
        height: 90px;
        object-fit: contain;
        /* margin-bottom: 2px; */
    }

    .tooth.normal {
        background-color: #98FB98;
        border-color: #4CAF50;
    }

    .tooth.decayed {
        background-color: #f44336;
    }

    .tooth.filled {
        background-color: #2196F3;
    }

    .tooth.missing {
        background-color: #9e9e9e;
    }

    .tooth.crown {
        background-color: #ffd700;
    }

    .tooth.bridge {
        background-color: #4CAF50;
    }

    .tooth-label {
        font-size: 12px;
        font-weight: bold;
        color: #333;
        padding: 2px z-index: 1;
    }

    .tooth-has-notes {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 10px;
        height: 10px;
        background: #FFC107;
        border-radius: 50%;
    }

    /* Notes Modal */
    .notes-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .notes-modal-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
    }

    .notes-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .notes-modal-header h3 {
        margin: 0;
    }

    .close-notes-modal {
        font-size: 24px;
        cursor: pointer;
        border: none;
        background: none;
    }

    #toothNotes {
        width: 100%;
        min-height: 100px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        resize: vertical;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize teeth data from controller with error handling
        let teethData = {};
        try {
            @if (isset($completeTeeth))
                teethData = @json($completeTeeth);
                console.log('Teeth data loaded:', teethData);
            @else
                console.warn('No teeth data available');
            @endif
        } catch (error) {
            console.error('Error parsing teeth data:', error);
        }

        // Apply initial tooth statuses
        Object.entries(teethData).forEach(([number, data]) => {
            const tooth = document.querySelector(`[data-tooth-number="${number}"]`);
            if (tooth) {
                // Check if data is a proper object with status
                const status = (data && typeof data === 'object' && data.status) ? data.status :
                    'normal';
                const note = (data && typeof data === 'object' && data.note) ? data.note.note_text : '';

                tooth.dataset.status = status;
                if (note) {
                    tooth.dataset.note = note;
                }
                updateToothAppearance(tooth);
            }
        });
        // Right click for notes
        document.querySelectorAll('.tooth').forEach(tooth => {
            tooth.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                openNotesModal(this);
            });
            // Long press for mobile
            let pressTimer;
            tooth.addEventListener('touchstart', function(e) {
                pressTimer = setTimeout(() => {
                    openNotesModal(this);
                }, 500);
            });
            tooth.addEventListener('touchend', function() {
                clearTimeout(pressTimer);
            });
        });
        // Notes modal handlers
        document.querySelector('.close-notes-modal')?.addEventListener('click', closeNoteModal);

        // Close modal when clicking outside
        document.querySelector('.notes-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeNoteModal();
            }
        });
    });

    function openNotesModal(toothElement) {
        const currentToothNumber = toothElement.dataset.toothNumber;

        document.getElementById('toothNumber').textContent = currentToothNumber;
        const existingNote = toothElement.dataset.note || '';
        document.getElementById('toothNotes').value = existingNote;

        document.querySelector('.notes-modal').style.display = 'flex';
    }

    function closeNoteModal() {
        document.querySelector('.notes-modal').style.display = 'none';
    }

    function updateToothAppearance(tooth) {
        const status = tooth.dataset.status || 'normal';
        // Remove all possible status classes
        tooth.classList.remove('normal', 'decayed', 'filled', 'missing', 'crown', 'bridge');
        // Add the current status class
        tooth.classList.add(status);
        // Update note indicator
        const note = tooth.dataset.note;
        const noteIndicator = tooth.querySelector('.tooth-has-notes');

        if (note && note.trim() !== '') {
            if (!noteIndicator) {
                const div = document.createElement('div');
                div.className = 'tooth-has-notes';
                tooth.appendChild(div);
            }
        } else if (noteIndicator) {
            noteIndicator.remove();
        }
    }
</script>
