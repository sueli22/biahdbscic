@extends('employee.layout')

@section('content')
<div class="container">
    <button class="btn btn-primary mb-3" id="btnCasualLeave">
        <i class="bi bi-calendar-check-fill"></i> ခွင့်တိုင်ရန်
    </button>

    <button class="btn btn-info mb-3" id="btnShowBalance">
        <i class="bi bi-eye-fill"></i> ခွင့်ကျန်စာရင်းကြည့်ရန်
    </button>

    <div id="leaveRequestsSection">
        <table id="leaveRequestsTable" class="display table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>ခွင့်အမျိုးအစား</th>
                    <th>ခွင့်စတင်ယူသည့်နေ့</th>
                    <th>ခွင့်ပီးဆုံးသည့်နေ့</th>
                    <th>ခွင့်ယူသည့် ကာလ</th>
                    <th>ဖော်ပြချက်</th>
                    <th>အခြေအနေ</th>
                    <th>တင်သွင်းသည့်ရက်</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaveRequests as $request)
                <tr>
                    <td>

                        @if($request->req_type === 'shaung')
                        ရှောင်တခင်
                        @elseif($request->req_type === 'no-pay')
                        လစာမဲ့ခွင့်
                        @elseif($request->req_type === 'maternity')
                        မီးဖွားခွင့်
                        @elseif($request->req_type === 'medical-certificate')
                        ဆေးလက်မှတ်ခွင့်
                        @elseif($request->req_type === 'long-service')
                        လုပ်သက်ခွင့်
                        @elseif($request->req_type === 'contagious')
                        ကူးစက်ရောဂါ
                        @elseif($request->req_type === 'disability')
                        အထူးမသန်စွမ်းခွင့်
                        @elseif($request->req_type === 'volunteer-sick')
                        သဘောသားမနာကျန်းခွင့်
                        @elseif($request->req_type === 'study')
                        ပညာလေ့လာဆည်းပူခွင့်
                        @elseif($request->req_type === 'father')
                         ကလေးအဖေအဖြစ် စောင့်ရှောက်ခွင့်
                        @elseif($request->req_type === 'twin')
                        အမွှာ၆ပတ် ရယူခွင့်
                        @else
                        အခြား
                        @endif
                    </td>


                    <td>{{ $request->from_date ?? '-' }}</td>
                    <td>{{ $request->to_date ?? '-' }}</td>
                    <td>
                        {{ $request->duration ? $request->duration . ' ရက်' : '-' }}
                    </td>

                    <td>{{ $request->description ?? '-' }}</td>
                    <td>
                        @if ($request->status === 'approved')
                        ခွင့်ကို လက်ခံပါသည်
                        @elseif ($request->status === 'reject')
                        ခွင့်ကို ငြင်းပယ်ထားပါသည်
                        @elseif ($request->status === 'pending')
                        ခွင့်ကို စောင့်ဆိုင်းနေဆဲဖြစ်သည်
                        @else
                        ---
                        @endif
                    </td>
                    <td>{{ $request->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    {{-- Leave Balance Table (Initially Hidden) --}}
    <div id="leaveBalanceSection" style="display:none;">
        <h5>Leave Balance</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ခွင့်အမျိုးအစား</th>
                    <th>စုစုပေါင်း</th>
                    <th>ယူပြီး</th>
                    <th>ကျန်</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaveBalances as $type => $balance)

                <tr>
                    <td>
                        @switch($type)
                        @case('shaung')
                        ရှောင်တခင်
                        @break
                        @case('no-pay')
                        လစာမဲ့ခွင့်
                        @break
                        @case('maternity')
                        မီးဖွားခွင့်
                        @break
                        @case('medical-certificate')
                        ဆေးလက်မှတ်ခွင့်
                        @break
                        @case('long-service')
                        လုပ်သက်ခွင့်
                        @break
                        @case('contagious')
                        ကူးစက်ရောဂါ
                        @break
                        @case('disability')
                        အထူးမသန်စွမ်းခွင့်
                        @break
                        @case('volunteer-sick')
                        သဘောသားမနာကျန်းခွင့်
                        @break
                        @case('study')
                        ပညာလေ့လာဆည်းပူခွင့်
                        @break
                        @case('twin')
                        အမွှာ၆ပတ် ရယူခွင့်
                        @break
                        @case('father')
                       ကလေးအဖေအဖြစ်ကလေး စောင့်ရှောက်ခွင့်
                        @break
                        @default
                        အခြား
                        @endswitch
                    </td>
                    <td>{{ $balance['max'] }}</td>
                    <td>{{ $balance['used'] }}</td>
                    <td>{{ $balance['left'] }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>



    <!-- Casual Leave Modal -->
    <div class="modal fade" id="casualLeaveModal" tabindex="-1" aria-labelledby="casualLeaveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="casualLeaveForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="casualLeaveModalLabel">ခွင့်လျှောက်လွှာ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ပိတ်ရန်"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="leave_type" value="casual">
                        <input type="hidden" name="general" id="general" class="form-control">
                        <div class="invalid-feedback" id="error-general"></div>

                         <div class="mb-3">
                            <label for="req_type" class="form-label">လျှောက်ထားသည့်အမျိုးအစား</label>
                            <select name="req_type" id="req_type" class="form-control">
                                <option value="">-- အမျိုးအစားရွေးချယ်ရန် --</option>
                                <option value="no-pay">လစာမဲ့ခွင့်</option>
                                <option value="shaung">ရှောင်တခင်</option>
                                <option value="maternity">မီးဖွားခွင့်</option>
                                <option value="medical-certificate">ဆေးလက်မှတ်ခွင့်</option>
                                <option value="long-service">လုပ်သက်ခွင့်</option>
                                <option value="contagious">ကူးစက်ရောဂါ</option>
                                <option value="disability">အထူးမသန်စွမ်းခွင့်</option>
                                <option value="volunteer-sick">သဘောသားမနာကျန်းခွင့်</option>
                                <option value="study">ပညာလေ့လာဆည်းပူခွင့်</option>
                                <option value="twin">အမွှာ၆ပတ် ရယူခွင့် </option>
                                <option value="father">ကလေးအဖေအဖြစ်ကလေး စောင့်ရှောက်ခွင့် </option>
                            </select>
                            <div class="invalid-feedback" id="error-req_type"></div>
                        </div>


                        <div class="mb-3">
                            <label for="from_date" class="form-label">ခွင့် အစပြုရက် (မှ)</label>
                            <input type="date" name="from_date" id="from_date" class="form-control">
                            <div class="invalid-feedback" id="error-from_date"></div>
                        </div>

                        <div class="mb-3">
                            <label for="to_date" class="form-label">ခွင့် ပြီးဆုံးရက် (ထိ)</label>
                            <input type="date" name="to_date" id="to_date" class="form-control">
                            <div class="invalid-feedback" id="error-to_date"></div>
                        </div>

                        <div class="mb-3">
                            <label for="description_casual" class="form-label">အကြောင်းအရာ</label>
                            <textarea name="description" id="description_casual" class="form-control"></textarea>
                            <div class="invalid-feedback" id="error-description"></div>
                        </div>

                        <div class="mb-3">
                            <label for="img_casual" class="form-label">စာရွက်စာတမ်းတင်ရန်</label>
                            <input type="file" name="img" id="img_casual" class="form-control" accept="image/*">
                            <div class="invalid-feedback" id="error-img"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်ရန်</button>
                        <button type="submit" class="btn btn-primary">တင်သွင်းရန်</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        $('#leaveRequestsTable').DataTable({
            lengthMenu: [
                [5, 10, 15, 20, 25, 30, 35],
                [5, 10, 15, 20, 25, 30, 35]
            ],
            pageLength: 5,
            pagingType: 'full_numbers',
            language: {
                search: "ရှာဖွေခြင်း:",
                lengthMenu: "စာရင်း _MENU_ ခုကိုပြပါ",
                info: "စာရင်း _START_ မှ _END_ အထိ ပြပါ",
                paginate: {
                    previous: "ရှေ့သို့",
                    next: "နောက်သို့"
                },
                zeroRecords: "ရှာနေသော အရာမှာ Databaseတွင်မရှိပါ"
            }
        });
        // Show modals
        $('#btnCasualLeave').click(function() {
            $('#casualLeaveModal').modal('show');
            $('#casualLeaveForm')[0].reset();
            $('#casualLeaveForm .invalid-feedback').text('');
            $('#casualLeaveForm .form-control').removeClass('is-invalid');
        });

        $('#btnShowBalance').click(function() {
            $('#leaveRequestsSection').toggle();
            $('#leaveBalanceSection').toggle();

            // Update button text/icon
            if ($('#leaveBalanceSection').is(':visible')) {
                $(this).html('<i class="bi bi-list-check"></i> Leave History </i>');
            } else {
                $(this).html('<i class="bi bi-list-check"></i> ခွင့်ကျန်စာရင်းကြည့်ရန်');
            }
        });


        // Reset validation errors
        function resetErrors(formId) {
            $(`${formId} .invalid-feedback`).text('');
            $(`${formId} .form-control, ${formId} .form-select`).removeClass('is-invalid');
        }

        // Suggest To Date AJAX
        function suggestToDate(reqTypeSelector, fromDateSelector, toDateSelector) {
            let reqType = $(reqTypeSelector).val();
            let fromDate = $(fromDateSelector).val();

            if (reqType && fromDate) {
                $.ajax({
                    url: "{{ route('employee.leave.suggestToDate') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        req_type: reqType,
                        from_date: fromDate
                    },
                    success: function(res) {
                        if (res.to_date) {
                            $(toDateSelector).val(res.to_date);
                        }
                    }
                });
            }
        }

        $('#req_type, #from_date').change(function() {
            suggestToDate('#req_type', '#from_date', '#to_date');
        });

        // AJAX form submission


        // AJAX form submission helper
        function ajaxFormSubmit(formId, modalId) {
            $(formId).submit(function(e) {
                e.preventDefault();

                resetErrors(formId);

                var form = $(this)[0];
                var formData = new FormData(form);

                $.ajax({
                    url: "{{ route('employee.leave.store') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $(modalId).modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Validation error
                            let errors = xhr.responseJSON.errors;
                            console.log(errors);
                            for (const key in errors) {
                                // Replace dots with underscores for selector
                                let fieldKey = key.replace(/\./g, '_');

                                // Add is-invalid class to input/select/textarea
                                $(`${formId} [name="${key}"]`).addClass('is-invalid');

                                // Show error message
                                $(`${formId} #error-${fieldKey}`).text(errors[key][0]);
                            }
                        } else {
                            alert('အမှားတစ်ခုဖြစ်ပွားပါသည်။');
                        }

                    }
                });
            });
        }

        function toggleDateFields() {
        let reqType = $('#req_type').val();
        if (reqType === 'twin') {
            // Hide from_date and to_date
            $('#from_date').closest('.mb-3').hide();
            $('#to_date').closest('.mb-3').hide();
        } else {
            // Show for all other types
            $('#from_date').closest('.mb-3').show();
            $('#to_date').closest('.mb-3').show();
        }
    }

     toggleDateFields();

    // Run when the leave type is changed
    $('#req_type').change(function() {
        toggleDateFields();
    });

        ajaxFormSubmit('#casualLeaveForm', '#casualLeaveModal');
        ajaxFormSubmit('#specialLeaveForm', '#specialLeaveModal');
    });
</script>
@endsection
