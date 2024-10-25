<?php

namespace App\Http\Controllers\adminPanel;

use App\Models\AuditLog;
use App\Models\Procedure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProcedureController extends Controller
{
    public function procedure()
    {
        $procedures = Procedure::all();
        return view('admin.procedure.procedure', compact('procedures'));
    }

    public function addProcedure()
    {
        return view('admin.procedure.add-procedure');
    }

    public function storeProcedure(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'description' => 'required',
        ]);

        $procedure = Procedure::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        AuditLog::create([
            'action' => 'Create',
            'model_type' => 'New procedure added',
            'model_id' => $procedure->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);

        return redirect()->route('procedure')->with('success', 'Procedure successfully added!');
    }

    public function editProcedure($id)
    {
        $procedure = Procedure::findOrFail($id);

        return view('admin.procedure.edit-procedure', compact('procedure'));
    }

    public function updateProcedure(Request $request, $id)
    {
        $procedure = Procedure::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'description' => 'required',
        ]);

        $procedure->update($validated);

        AuditLog::create([
            'action' => 'Update',
            'model_type' => 'Procedure information updated',
            'model_id' => $procedure->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);
        return redirect()->route('procedure')->with('success', 'Procedure updated successfully!');
    }

    public function deleteProcedure(Request $request, $id)
    {
        $procedure = Procedure::findOrFail($id);

        $procedure->delete();

        AuditLog::create([
            'action' => 'Delete',
            'model_type' => 'Procedure deleted',
            'model_id' => $procedure->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);

        return redirect()->route('procedure')->with('success', 'Procedure deleted successfully!');
        session()->flash('success', 'Procedure deleted added!');
    }
}
