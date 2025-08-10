<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    // List all positions
    public function index()
    {
        $positions = Position::all();
        return view('admin.positions.index', compact('positions'));
    }

    public function edit(Position $position)
    {
        return response()->json($position);
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'title' => 'required|string',
            'salary' => 'required|numeric',
        ]);

        $position->update($request->only('title', 'salary'));

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
{
    try {
        $request->validate([
            'title' => 'required|string',
            'salary' => 'required|numeric',
        ]);

        Position::create($request->only('title', 'salary'));

        return response()->json(['success' => true]);

    } catch (ValidationException $e) {
        return response()->json([
            'errors' => $e->errors()
        ], 422);
    }
}

    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->back()->with('success', 'ရာထူးကို ဖျက်ပြီးပါပြီ။');
    }
}
