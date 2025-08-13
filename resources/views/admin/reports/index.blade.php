@extends('admin.layout')
@section('content')
    <div class="container">
        <h2>စီမံကိန်းနှစ်ပတ်လည်အစီရင်ခံစာများ</h2>

        <!-- Create Button -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
            စီမံကိန်းအသစ်ထည့်မည်
        </button>

        <!-- Filter -->
        <div class="row mb-3">
            <div class="col-md-3">
                <input type="text" id="from" class="form-control" placeholder="From date (YYYY-MM-DD)">
            </div>
            <div class="col-md-3">
                <input type="text" id="to" class="form-control" placeholder="To date (YYYY-MM-DD)">
            </div>
            <div class="col-md-2">
                <button id="filter" class="btn btn-primary">ရှာဖွေမည်</button>
            </div>
        </div>

        <!-- DataTable -->
        <table class="table table-bordered" id="reportsTable" style="font-size:12px;">
            <thead>
                <tr>
                    <th>နံပါတ်</th>
                    <th>လုပ်ငန်းအမည်</th>
                    <th>တည်နေရာ</th>
                    <th>စတင်မည့်ကာလ</th>
                    <th>ပြီးစီးမည့်ကာလ</th>
                    <th>ဆောင်ရွက်မည့်ဌာန/အဖွဲ့အစည်း</th>
                    <th>စုစုပေါင်းရင်းနှီးမြှုပ်နှံမည့်ငွေ</th>
                    <th>ဆောင်ရွက်သည့်နှစ်</th>
                    <th>တိုင်းဒေသကြီးဘတ်ဂျက်</th>
                    <th>တင်ဒါအောင်မြင်သည့်စျေးနှုန်း</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">စီမံကိန်းအသစ်ထည့်ရန်</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="createForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>စတင်မည့်နှစ်</label>
                                <input type="text" name="from" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>ပြီးဆုံးမည့်နှစ်</label>
                                <input type="text" name="to" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>စီမံကိန်းအမည်</label>
                                <input type="text" name="name" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>တည်နေရာ</label>
                                <input type="text" name="location" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>စတင်မည့်ကာလ</label>
                                <input type="text" name="start_month" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>ပြီးစီးမည့်ကာလ</label>
                                <input type="text" name="end_month" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>ဌာန/အဖွဲ့အစည်း</label>
                                <input type="text" name="department" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>စုစုပေါင်းရင်းနှီးမြှုပ်နှံမည့်ငွေ</label>
                                <input type="number" name="total_investment" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>ဆောင်ရွက်သည့်နှစ်</label>
                                <input type="text" name="operation_year" class="form-control" min="1900">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>တိုင်းဒေသကြီးဘတ်ဂျက်</label>
                                <input type="number" name="regional_budget" class="form-control" min="1">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>တင်ဒါအောင်မြင်သည့်စျေးနှုန်း</label>
                                <input type="number" name="tender_price" class="form-control" min="1">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-2" id="saveBtn">သိမ်းမည်</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Load table function
            function loadTable(from = '', to = '') {
                $.ajax({
                    url: '{{ route('yearly_reports.list') }}',
                    type: 'GET',
                    data: {
                        from: from,
                        to: to
                    },
                    success: function(data) {
                        if ($.fn.DataTable.isDataTable('#reportsTable')) {
                            $('#reportsTable').DataTable().destroy();
                        }
                        $('#reportsTable tbody').empty();
                        $.each(data, function(index, report) {
                            $('#reportsTable tbody').append(`
                        <tr>
                            <td>${index+1}</td>
                            <td>${report.name || ''}</td>
                            <td>${report.location || ''}</td>
                            <td>${report.start_month || ''}</td>
                            <td>${report.end_month || ''}</td>
                            <td>${report.department || ''}</td>
                            <td>${report.total_investment || ''}</td>
                            <td>${report.operation_year || ''}</td>
                            <td>${report.regional_budget || ''}</td>
                            <td>${report.tender_price || ''}</td>
                        </tr>
                    `);
                        });
                        $('#reportsTable').DataTable();
                    },
                    error: function(xhr) {
                        alert('တောင်းဆိုမှုတွင် အမှားဖြစ်နေပါသည်။');
                        console.log(xhr.responseText);
                    }
                });
            }

            // Initial load
            loadTable();

            // Filter button
            $('#filter').click(function() {
                loadTable($('#from').val(), $('#to').val());
            });

            function clearErrors() {
                $('#formErrors').addClass('d-none').empty();
                $('#createForm .is-invalid').removeClass('is-invalid');
                $('#createForm .invalid-feedback').text('');
            }

            function resetForm() {
                $('#createForm')[0].reset();
                clearErrors();
            }

            // Clear form & errors whenever modal is closed
            $('#createModal').on('hidden.bs.modal', function() {
                resetForm();
            });

            // (Optional) also clear when opening
            $('#createModal').on('show.bs.modal', function() {
                clearErrors();
            });

            // Submit
            $('#createForm').on('submit', function(e) {
                e.preventDefault();
                clearErrors();

                // disable button to prevent double click
                $('#saveBtn').prop('disabled', true);

                $.ajax({
                    url: '{{ route('yearly_reports.store') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        // success message (Myanmar)

                        // close modal and refresh the table
                        $('#createModal').modal('hide');

                        // reload your list (if you have a function)
                        if (typeof loadTable === 'function') {
                            loadTable($('#from').val(), $('#to').val());
                        }
                    },
                    error: function(xhr) {
                        $('#saveBtn').prop('disabled', false);

                        if (xhr.status === 422) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors || {};
                            let summary =
                                '<strong>ဖြည့်စွက်ရန်အချက်အလက်များရှိပါသည်။</strong><ul class="mb-0">';
                            Object.keys(errors).forEach(function(field) {
                                const messages = errors[field];
                                summary += '<li>' + (messages[0] || '') + '</li>';

                                // find input by name and show message
                                const $input = $('#createForm [name="' + field + '"]');
                                $input.addClass('is-invalid');
                                $input.siblings('.invalid-feedback').text(messages[0] ||
                                    '');
                            });
                            summary += '</ul>';

                            $('#formErrors').removeClass('d-none').html(summary);
                        } else {
                            // Other server errors
                            $('#formErrors').removeClass('d-none').html(
                                'မထောက်ပံ့ထားသော အမှားဖြစ်ပွားခဲ့သည်။ ခဏကြာ၍ ပြန်လည်စမ်းကြည့်ပါ။'
                            );
                            console.error(xhr.responseText);
                        }
                    },
                    complete: function() {
                        $('#saveBtn').prop('disabled', false);
                    }
                });
            });

        });
    </script>
@endsection
