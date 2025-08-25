@extends('employee.layout')

@section('content')
    <form action="{{ route('attendance.today') }}" method="POST">
        @csrf
        <button type="submit" style="padding: 10px 20px; background: green; color: white; border: none;">
            ယနေ့အတွက် လက်မှတ်တင်ခြင်း
        </button>
    </form>

    <table id="attendanceTable" class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>စဥ်</th>
                <th>ရက်</th>
                <th>နေ့ (EN)</th>
                <th>နေ့ (မြန်မာ)</th>
                <th>အခြေအနေ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
                <tr>
                    <td>{{ $loop->iteration }}</td> <!-- Serial number -->
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->day_name }}</td>
                    <td>{{ $attendance->day_name_mm }}</td>
                    <td>
                        @if ($attendance->status === 'approved')
                            လက်မှတ်တင်ခြင်း ကို လက်ခံပါသည်
                        @elseif ($attendance->status === 'reject')
                            လက်မှတ်တင်ခြင်း ကို လက်မခံပါ
                        @elseif ($attendance->status === 'pending')
                            အတည်ပြုပေးမည်ကို စောင့်ဆိုင်းနေဆဲဖြစ်သည်
                        @else
                            ---
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="5">မှတ်တမ်းမရှိသေးပါ။</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#attendanceTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [
                    [1, 'desc']
                ]
            });
        });
    </script>
@endsection
