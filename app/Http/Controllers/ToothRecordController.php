<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\ToothRecord;
use App\Models\ToothNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ToothRecordController extends Controller
{
    public function getPatientTeeth($patientId)
    {
        $patient = Patient::findOrFail($patientId);


        $teeth = ToothRecord::with('note')
            ->where('patient_id', $patientId)
            ->get()
            ->keyBy('tooth_number');
        // Ensure all 32 teeth are represented
        $completeTeeth = [];
        for ($i = 1; $i <= 32; $i++) {
            $completeTeeth[$i] = $teeth[$i] ?? [
                'tooth_number' => $i,
                'status' => 'normal',
                'note' => null
            ];
        }
        return response()->json($completeTeeth);
    }
    public function saveTeeth(Request $request, $patientId)
    {
        $validator = Validator::make($request->all(), [
            'teeth' => 'required|array',
            'teeth.*.number' => 'required|integer|min:1|max:32',
            'teeth.*.status' => 'required|in:normal,decayed,filled,missing,crown,bridge',
            'teeth.*.note' => 'nullable|string|max:1000'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            DB::beginTransaction();
            foreach ($request->teeth as $toothData) {
                $tooth = ToothRecord::updateOrCreate(
                    [
                        'patient_id' => $patientId,
                        'tooth_number' => $toothData['number']
                    ],
                    ['status' => $toothData['status']]
                );
                if (isset($toothData['note'])) {
                    ToothNote::updateOrCreate(
                        ['tooth_record_id' => $tooth->id],
                        ['note_text' => $toothData['note']]
                    );
                }
            }
            DB::commit();
            return response()->json(['message' => 'Teeth records saved successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to save teeth records'], 500);
        }
    }
    public function updateToothStatus(Request $request, $patientId, $toothNumber)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:normal,decayed,filled,missing,crown,bridge'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $tooth = ToothRecord::updateOrCreate(
                [
                    'patient_id' => $patientId,
                    'tooth_number' => $toothNumber
                ],
                ['status' => $request->status]
            );
            return response()->json(['message' => 'Tooth status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update tooth status'], 500);
        }
    }
    public function updateToothNote(Request $request, $patientId, $toothNumber)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required|string|max:1000'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            DB::beginTransaction();
            $tooth = ToothRecord::firstOrCreate(
                [
                    'patient_id' => $patientId,
                    'tooth_number' => $toothNumber
                ],
                ['status' => 'normal']
            );
            ToothNote::updateOrCreate(
                ['tooth_record_id' => $tooth->id],
                ['note_text' => $request->note]
            );
            DB::commit();
            return response()->json(['message' => 'Tooth note updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update tooth note'], 500);
        }
    }
}
