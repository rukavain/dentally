<?php

namespace App\Http\Controllers\adminPanel;

use App\Models\AuditLog;
use App\Models\Procedure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProcedureController extends Controller
{
    public function procedure(Request $request)
    {
        $query = Procedure::query();

        // Handle search
        if ($request->has('search') && !empty($request->get('search'))) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('id', 'like', '%' . $searchTerm . '%');
            });
        }

        // Get sort direction, default to 'asc' if not specified
        $direction = $request->get('direction', 'asc');

        // Handle sorting
        if ($request->has('sort')) {
            $sortOption = $request->get('sort');
            switch ($sortOption) {
                case 'code':
                    $query->orderBy('id', $direction);
                    break;
                case 'name':
                    $query->orderBy('name', $direction);
                    break;
                case 'price':
                    $query->orderBy('price', $direction);
                    break;
                default:
                    $query->orderBy('id', 'asc');
            }
        } else {
            $query->orderBy('id', 'asc');
        }

        $procedures = $query->paginate(10)->appends($request->except('page'));
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
