<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{

    private function getUser()
    {
        return auth()->user();
    }
    public function showProfile()
    {
        $user = $this->getUser();
        return view('employee.profile.show', compact('user'));
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $user = auth()->user();

        // Delete old image if exists
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $path = $request->file('image')->store('staff', 'public');

        $user->image = $path;
        $user->save();

        return redirect()->back()->with('success', 'ပုံကို အောင်မြင်စွာ ပြောင်းလဲပြီးပါပြီ။');
    }

    public function showLeaveRequestForm()
    {
        $user = $this->getUser();
        $leaveTypes = LeaveType::all();
        return view('employee.leave.index', compact('user', 'leaveTypes'));
    }

    public function storeLeaveRequest(Request $request)
    {
        $rules = [
            'leave_type' => 'required|in:casual,special',
            'description' => 'nullable|string',
            'img' => 'nullable|image|max:2048',
        ];

        if ($request->leave_type === 'casual') {
            $rules['from_date'] = 'required|date';
            $rules['to_date'] = 'required|date|after_or_equal:from_date';
        } else {
            $rules['leave_type_id'] = 'required|exists:leave_types,id';
        }

        $validated = $request->validate($rules);

        $leaveRequest = new LeaveRequest();
        $leaveRequest->user_id = auth()->id();
        $leaveRequest->leave_type_id = $request->leave_type === 'special' ? $request->leave_type_id : null;
        $leaveRequest->from_date = $request->leave_type === 'casual' ? $request->from_date : null;
        $leaveRequest->to_date = $request->leave_type === 'casual' ? $request->to_date : null;
        $leaveRequest->description = $request->description;

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('leave_docs', 'public');
            $leaveRequest->img = $path;
        }

        $leaveRequest->status = 'pending';
        $leaveRequest->save();

        return response()->json(['message' => 'ခွင့်လျှောက်လွှာ အောင်မြင်စွာ တင်သွင်းပြီးပါပြီ။']);
    }

}
