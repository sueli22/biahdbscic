<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Http\Requests\StaffUpdateRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Position;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Requests\StaffRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Type;
use App\Models\Web;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function indexDashboard()
    {
        $web = Web::first();
        $usersByPosition = User::select('position_id', DB::raw('count(*) as total'))
            ->groupBy('position_id')
            ->with('position')
            ->get();

        $salaryByPosition = User::join('positions', 'users.position_id', '=', 'positions.position_id')
            ->select('positions.title', DB::raw('SUM(positions.salary) as total_salary'))
            ->groupBy('positions.title')
            ->get();

        return view('admin.home', compact('usersByPosition', 'salaryByPosition', 'web'));
    }

    private function getUser()
    {
        return auth()->user();
    }
    public function showProfile()
    {
        $web = Web::first();
        $user = $this->getUser();
        return view('admin.profile.profile', compact('user', 'web'));
    }

    public function editProfile()
    {
        $web = Web::first();
        $user = $this->getUser();
        return view('admin.profile.profile_edit', compact('user', 'web'));
    }

    public function updateProfile(UpdateUserProfileRequest $request)
    {
        $web = Web::first();
        $user = auth()->user();

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('/images', $imageName);
            $validated['image'] = $imageName;
        }
        $user->update($validated);
        return redirect()->route('admin.profile.show')->with('success', 'Profile updated successfully.');
    }

    public function showsStaffList()
    {
        $web = Web::first();
        $staffs = User::where('super_user', false)->get();
        return view('admin.staff.list', compact('staffs', 'web'));
    }

    public function destroyStaff($id)
    {
        $staff = User::findOrFail($id); // Find staff by id or throw 404 if not found
        $staff->delete();                // Delete the staff record

        return redirect()->route('staff.list')->with('success', 'Staff deleted successfully.');
    }

    public function editStaff($id)
    {
        $staff = User::findOrFail($id);
        $positions = Position::all();
        return response()->json(['staff' => $staff, 'positions' => $positions]);
    }


    public function updateStaff(StaffUpdateRequest $request, $id)
    {
        $staff = User::findOrFail($id);

        $data = $request->validated();

        // Handle image upload if exists
        if ($request->hasFile('image')) {
            if ($staff->image && Storage::disk('public')->exists($staff->image)) {
                Storage::disk('public')->delete($staff->image);
            }
            $path = $request->file('image')->store('staff', 'public');
            $data['image'] = $path;
        }

        // Convert married_status and super_user to boolean 0 or 1
        $data['married_status'] = (int) $request->input('married_status', 0);
        logger()->info('Update method called in AdminController.', ['data' =>$data['married_status']]);
        $data['super_user'] = (int) $request->input('super_user', 0);

        // Update the staff record
        $staff->update($data);

        return response()->json(['message' => 'Staff updated successfully']);
    }

    public function storeStaff(StaffRequest $request)
    {
        $data = $request->validated();

        // Handle image upload if exists
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('staff', 'public');
            $data['image'] = $path;
        }

        // Save married_status and super_user as boolean 0 or 1
        $data['married_status'] = (int) $request->input('married_status', 0);
        $data['super_user'] = false;

        // Hash password before storing
        $data['password'] = Hash::make($request->input('password'));

        Log::info('Store method called in StaffController.', ['data' => $data]);

        User::create($data);

        return response()->json(['message' => 'Staff created successfully']);
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'months' => 'required|array',
            'types' => 'required|array',
            'quantities' => 'required|array',
            'unit_prices' => 'required|array',
            'unit_prices.*' => 'numeric|min:0',
        ]);
        $months = $request->months;
        $types = $request->types;
        $quantities = $request->quantities;
        $unitPrices = $request->unit_prices;

        // Create XLSX writer
        $writer = WriterEntityFactory::createXLSXWriter();

        // Temp file to save
        $fileName = 'calculated_' . time() . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        $writer->openToFile($tempFile);

        // Create header row
        $headerRow = WriterEntityFactory::createRowFromArray(['လ', 'အမျိုးအစား', 'အရေအတွက်', 'Unit Price', 'Total']);
        $writer->addRow($headerRow);

        $grandTotal = 0;

        // Add data rows
        for ($i = 0; $i < count($types); $i++) {
            $total = $quantities[$i] * $unitPrices[$i];
            $grandTotal += $total;

            $row = WriterEntityFactory::createRowFromArray([
                $months[$i] ?? '',
                $types[$i],
                $quantities[$i],
                $unitPrices[$i] . ' ကျပ်',
                $total . ' ကျပ်',
            ]);
            $writer->addRow($row);
        }

        // Add total sum row
        $totalRow = WriterEntityFactory::createRowFromArray([
            'စုစုပေါင်း', // Label in Myanmar for Total
            '', // empty cell for quantity column
            '',
            '', // empty cell for unit price column
            $grandTotal . ' ကျပ်',
        ]);
        $writer->addRow($totalRow);

        $writer->close();

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function showStaff($id)
    {
        $staff = User::findOrFail($id);

        // Add position name or other computed properties if needed
        $staff->position_name = $staff->getPositionName();

        return response()->json($staff);
    }

    public function leaveRequestList(Request $request)
    {
        $web = Web::first();
        $status = $request->get('status');

        $query = LeaveRequest::with(['user', 'leaveType']);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $leaveRequests = $query->latest()->get();

        return view('admin.leave.request', compact('leaveRequests', 'status', 'web'));
    }

    // In Admin\LeaveRequestController.php

    public function approveLeaveRequest(Request $request, $id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->status = LeaveRequest::STATUS_APPROVED;
        $leave->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'ခွင့်တင်မှု လက်ခံပြီးပါပြီ!']);
        }

        return redirect()->back()->with('success', 'ခွင့်တင်မှု လက်ခံပြီးပါပြီ!');
    }


    public function rejectLeaveRequest($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->status = LeaveRequest::STATUS_REJECT;
        $leave->save();

        return redirect()->back()->with('success', 'ခွင့်တင်မှု ငြင်းပယ်ပြီးပါပြီ!');
    }

    public function showLeaveRequest($id)
    {
        $leave = LeaveRequest::with('user', 'leaveType')->findOrFail($id);

        return response()->json([
            'user' => [
                'name' => $leave->user->name ?? '-'
            ],
            'leave_type_name' => $leave->leaveType->title ?? ucfirst($leave->leave_type),
            'from_date' => $leave->from_date,
            'to_date' => $leave->to_date,
            'duration' => $leave->duration,
            'description' => $leave->description,
            'img' => $leave->img,
            'approved_date' => $leave->approved_date,
            'status' => $leave->status
        ]);
    }

}
