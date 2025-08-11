<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index(Request $request)
    {
        $leaveTypes = LeaveType::all();
        return view('admin.leave.list', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_days' => 'required|integer|min:1',
        ]);

        LeaveType::create($validated);

        return response()->json(['message' => 'Leave type created successfully']);
    }


    public function show(LeaveType $leaveType)
    {
        return response()->json($leaveType);
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'title' => 'required|unique:leave_types,title,' . $leaveType->id . '|max:255',
            'description' => 'nullable|string',
            'max_days' => 'required|integer|min:0',
        ]);

        $leaveType->update($validated);

        return response()->json(['success' => true, 'leaveType' => $leaveType]);
    }

    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();

        return response()->json(['success' => true]);
    }

}
