<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Attendence;
use Carbon\Carbon;
use App\Models\Web;

class AttendenceController extends Controller
{
    public function showAttendance()
    {
        $web = Web::first();
        $attendances = Attendence::where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->get();
        return view('employee.attendance.show', compact('attendances', 'web'));
    }

    public function showAttendanceList()
    {
        $web = Web::first();
        $attendances = Attendence::all();
        return view('admin.attendance.list', compact('attendances', 'web'));
    }

    public function storeToday(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::now();
        $dayEnglish = $today->format('l');

        $dayMyanmar = match ($dayEnglish) {
            'Monday' => 'တနင်္လာ',
            'Tuesday' => 'အင်္ဂါ',
            'Wednesday' => 'ဗုဒ္ဓဟူး',
            'Thursday' => 'ကြာသပတေး',
            'Friday' => 'သောကြာ',
            'Saturday' => 'စနေ',
            'Sunday' => 'တနင်္ဂနွေ',
        };

        // Prevent duplicate entry for today
        if (Attendence::where('user_id', $user->id)->where('date', $today->toDateString())->exists()) {
            return back()->with('error', 'ယနေ့အတွက် ရှိပြီးသား မှတ်တမ်းဖြစ်နေပါသည်!');
        }

        Attendence::create([
            'user_id' => $user->id,
            'date' => $today->toDateString(),
            'day_name' => $dayEnglish,
            'day_name_mm' => $dayMyanmar,
            'status' => 'pending',
        ]);

        return back()->with('success', 'ယနေ့အတွက် လက်မှတ်တင်ခြင်း ပြီးစီးပါပြီ။');
    }

    public function updateStatus(Request $request)
    {
        $attendance = Attendence::findOrFail($request->id);
        $attendance->status = $request->status;
        $attendance->save();

        return response()->json(['success' => true]);
    }
}
