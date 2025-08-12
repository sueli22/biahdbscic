@extends('employee.layout')

@section('content')
<table id="paySalariesTable" class="table table-bordered">
    <thead>
                <tr>
                    <th>အမှတ်</th>
                    <th>အမည်</th>
                    <th>လစာပေးချေသည့် လ</th>
                    <th>အခြေခံလစာ</th>
                    <th>Allowances</th>
                    <th>ခွင့်ရက်အတွက် ဖြတ်ငွေ</th>
                    <th>အသားတင်လစာ</th>
                    <th>ငွေပေးချေမှုနည်းလမ်း</th>
                    <th>လစာပေးချေသည့်ရက်စွဲ</th>
                </tr>
    </thead>
    <tbody>
        @foreach ($paySalaries as $paySalary)
            <tr>
                <td>{{ $paySalary->id }}</td>
              <td>{{ $paySalary->user->name ?? 'N/A' }}</td>
                        <td>
                            @php
                                $months = [
                                    1 => 'ဇန်နဝါရီ',
                                    2 => 'ဖေဖော်ဝါရီ',
                                    3 => 'မတ်',
                                    4 => 'ဧပြီ',
                                    5 => 'မေ',
                                    6 => 'ဇွန်',
                                    7 => 'ဇူလိုင်',
                                    8 => 'ဩဂုတ်',
                                    9 => 'စက်တင်ဘာ',
                                    10 => 'အောက်တိုဘာ',
                                    11 => 'နိုဝင်ဘာ',
                                    12 => 'ဒီဇင်ဘာ',
                                ];
                            @endphp
                            {{ $months[$paySalary->salary_month] ?? '' }}
                        </td>
                <td>{{ $paySalary->basic_salary }}</td>
                <td>{{ $paySalary->allowances }}</td>
                <td>{{ $paySalary->deductions }}</td>
                <td>{{ $paySalary->net_salary }}</td>
                <td>{{ $paySalary->payment_method ?? 'N/A' }}</td>
                <td>{{ $paySalary->created_at->format('Y-m-d') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#paySalariesTable').DataTable();
        });
    </script>
@endsection
