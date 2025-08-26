@extends('admin.layout')
@section('content')
    <div class="container">
        <button type="button" id="openPayModal" class="btn btn-primary">
            လစာပေးရန် နှိပ်ပါ
        </button>


        <table class="table table-bordered" id="paySalariesTable" style="background-color: #010549;">
            <thead>
                <tr>
                    <th>စဥ်</th>
                    <th>၀န်ထမ်းအမည်</th>
                    <th>လစာပေးချေသည့် နှစ်ကိုရွေးပါ</th>
                    <th>လစာပေးချေသည့် လ</th>
                    <th>အခြေခံလစာ</th>
                    <th>ရက်တွက်ရငွေ</th>
                    <th>ဆေးခွင့်ဖြတ်တောက်မှုမှ ကျန်ငွေ</th>
                    <th>လစာမဲ့ခွင့်ဖြတ်တောက်ငွေ</th>
                    <th>အသားတင်လစာ</th>
                    <th>လစာပေးချေသည့်ရက်စွဲ</th>
            </thead>
            <tbody>
                @foreach ($paySalaries as $paySalary)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $paySalary->user->name ?? 'N/A' }}</td>
                        <td>{{ $paySalary->salary_year?? 'N/A' }}</td>
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
                         <td>{{ $paySalary->daily_fee }}</td>
                        <th>{{ $paySalary->medical_de ?? '0' }}</th>
                        <th>{{ $paySalary->no_pay_de ?? '0' }}</th>
                        <td>{{ $paySalary->net_salary }}</td>
                        <td>{{ $paySalary->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addPayModal" tabindex="-1" aria-labelledby="addPayModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('pay_salaries.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPayModalLabel">လစာအသစ်ထည့်ရန်</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!-- User Dropdown -->
                        <div class="mb-3">
                            <label for="user_id" class="form-label">၀န်ထမ်းအမည်</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">၀န်ထမ်း ရွေးပါ</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Salary Year -->
                        <!-- Salary Year -->
                        <div class="mb-3">
                            <label for="salary_year" class="form-label">လစာ ပေးချေသည့်နှစ်</label>
                            <select name="salary_year" id="salary_year" class="form-select" required>
                                <option value="">နှစ်ကိုရွေးပါ</option>
                                @for ($year = date('Y'); $year >= 2000; $year--)
                                    @php
                                        $myanmarYear = strtr($year, [
                                            '0' => '၀',
                                            '1' => '၁',
                                            '2' => '၂',
                                            '3' => '၃',
                                            '4' => '၄',
                                            '5' => '၅',
                                            '6' => '၆',
                                            '7' => '၇',
                                            '8' => '၈',
                                            '9' => '၉',
                                        ]);
                                    @endphp
                                    <option value="{{ $year }}">{{ $year }} ({{ $myanmarYear }})
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Salary Month -->
                        <div class="mb-3">
                            <label for="salary_month" class="form-label">လစာ ပေးချေသည့်လ</label>
                            <select name="salary_month" id="salary_month" class="form-select" required>
                                <option value="">လကိုရွေးပါ</option>
                                <option value="1">January (ဇန်နဝါရီ)</option>
                                <option value="2">February (ဖေဖော်ဝါရီ)</option>
                                <option value="3">March (မတ်)</option>
                                <option value="4">April (ဧပြီ)</option>
                                <option value="5">May (မေ)</option>
                                <option value="6">June (ဇွန်)</option>
                                <option value="7">July (ဇူလိုင်)</option>
                                <option value="8">August (ဩဂုတ်)</option>
                                <option value="9">September (စက်တင်ဘာ)</option>
                                <option value="10">October (အောက်တိုဘာ)</option>
                                <option value="11">November (နိုဝင်ဘာ)</option>
                                <option value="12">December (ဒီဇင်ဘာ)</option>
                            </select>
                        </div>


                        <!-- Basic Salary -->
                        <div class="mb-3">
                            <label for="basic_salary" class="form-label">အခြေခံလစာ</label>
                            <input type="number" name="basic_salary" id="basic_salary" class="form-control" step="0.01"
                                min="0" required readonly>
                        </div>

                        <!-- Allowances -->
                        <div class="mb-3">
                            <label for="allowances" class="form-label">အပိုထောက်ပံ့ငွေ</label>
                            <input type="number" name="allowances" id="allowances" class="form-control" step="0.01"
                                min="0" value="0">
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">ငွေပေးချေမှုနည်းလမ်း</label>
                            <select name="payment_method" id="payment_method" class="form-select">
                                <option value="ငွေသား">ငွေသား</option>
                                <option value="ဘဏ်လွှဲ">ဘဏ်လွှဲ</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်ရန်</button>
                        <button type="submit" class="btn btn-primary">သိမ်းရန်</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#paySalariesTable').DataTable();
            $('#openPayModal').click(function() {
                $('#addPayModal').modal('show');
            });

            $('#user_id').change(function() {
                var userId = $(this).val();
                if (userId) {
                    $.ajax({
                        url: '/get-user-salary/' + userId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data.salary !== 'N/A') {
                                $('#basic_salary').val(data.salary);
                            } else {
                                $('#basic_salary').val('');
                            }
                        },
                        error: function() {
                            $('#basic_salary').val('');
                        }
                    });
                } else {
                    $('#basic_salary').val('');
                }
            });
        });
    </script>
@endsection
