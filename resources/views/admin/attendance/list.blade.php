@extends('admin.layout')

@section('content')
<h3>နေ့စဥ် ရုံးတတ်ရောက်သူများ စာရင်း</h3>
    <table id="attendanceTable" class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>စဥ်</th>
                <th>အမည်</th>
                <th>ရက်</th>
                <th>နေ့ (ENG)</th>
                <th>နေ့ (မြန်မာ)</th>
                <th>အခြေအနေ</th>
                <th>လုပ်ဆောင်ချက်</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($attendances as $attendance)
                <tr id="attendance-{{ $attendance->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $attendance->user->name ?? 'N/A' }}</td>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->day_name }}</td>
                    <td>{{ $attendance->day_name_mm }}</td>
                    <td class="status">
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
                    <td>
                        @if ($attendance->status === 'pending')
                            <button class="btn btn-success btn-approve" data-id="{{ $attendance->id }}">
                                <i class="bi bi-check-circle"></i>
                            </button>
                            <button class="btn btn-danger btn-reject" data-id="{{ $attendance->id }}">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        @endif

                    </td>
                </tr>
            @endforeach
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
                    [2, 'desc']
                ] // order by date
            });

            // CSRF token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // Approve button
            $(document).on('click', '.btn-approve', function() {
                var id = $(this).data('id');
                $.post("{{ route('attendance.updateStatus') }}", {
                    id: id,
                    status: 'approved'
                }, function(res) {
                    $('#attendance-' + id + ' .status').text('လက်မှတ်တင်ခြင်း ကို လက်ခံပါသည်');
                    $('#attendance-' + id + ' td:last').html('');
                });
            });

            // Reject button
            $(document).on('click', '.btn-reject', function() {
                var id = $(this).data('id');
                $.post("{{ route('attendance.updateStatus') }}", {
                    id: id,
                    status: 'reject'
                }, function(res) {
                    $('#attendance-' + id + ' .status').text('လက်မှတ်တင်ခြင်း ကို လက်မခံပါ');
                    $('#attendance-' + id + ' td:last').html('');
                });
            });
        });
    </script>
@endsection
