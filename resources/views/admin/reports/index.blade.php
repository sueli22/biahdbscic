@extends('admin.layout')
@section('content')
    <div class="container">
        <h2>စီမံကိန်းနှစ်ပတ်လည်အစီရင်ခံစာများ</h2>

        <!-- Filter -->
        <div class="row mb-3 mt-3">
            <div class="col-md-2">
                <input type="text" id="from" class="form-control" placeholder="From date (YYYY-MM-DD)">
            </div>
            <div class="col-md-2">
                <input type="text" id="to" class="form-control" placeholder="To date (YYYY-MM-DD)">
            </div>
            <div class="col-md-2">
                <button id="filter" class="btn btn-primary">ရှာဖွေမည်</button>
            </div>
            <div class="col-md-2">
                <button id="toggleBtn" class="btn btn-primary">Show Charts</button>
            </div>
            <div class="col-md-2">
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createModal" style="font-size: 12px">
                    စီမံကိန်းအသစ်ထည့်မည်
                </button>
            </div>

        </div>

        <div id="listContainer">
            <!-- DataTable -->
            <table class="table table-bordered" id="reportsTable" style="font-size:12px;">
                <thead>
                    <tr>
                        <th>စဥ်</th>
                        <th>မှ</th>
                        <th>အထိ</th>
                        <th>လုပ်ငန်းအမည်</th>
                        <th>တည်နေရာ</th>
                        <th>စတင်မည့်ကာလ</th>
                        <th>ပြီးဆုံးမည့်ကာလ</th>
                        <th>ဆောင်ရွက်မည့်ဌာန/အဖွဲ့အစည်း</th>
                        <th>ဆောင်ရွက်သည့်နှစ်</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="chartsContainer" style="display:none;margin-left:170px">
        <div class="row">
            <div class="col-md-8">
                <canvas id="departmentPieChart"></canvas>
            </div>
        </div>
    </div>
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
                                <label>ဆောင်ရွက်သည့်နှစ်</label>
                                <input type="text" name="operation_year" class="form-control" min="1900">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-2" id="saveBtn">သိမ်းမည်</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">ပြင်ဆင်ရန်</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="col-md-6 mb-2">
                            <label>စတင်မည့်နှစ်</label>
                            <input type="text" name="from" id="edit_from" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>ပြီးဆုံးမည့်နှစ်</label>
                            <input type="text" name="to" id="edit_to" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label>စီမံကိန်းအမည်</label>
                            <input type="text" class="form-control" id="edit_name" name="name">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label>တည်နေရာ</label>
                            <input type="text" class="form-control" id="edit_location" name="location">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label>စတင်မည့်လ</label>
                            <input type="text" class="form-control" id="edit_start_month" name="start_month">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label>ပြီးဆုံးမည့်လ</label>
                            <input type="text" class="form-control" id="edit_end_month" name="end_month">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label>ဌာန/အဖွဲ့အစည်း</label>
                            <input type="text" class="form-control" id="edit_department" name="department">
                            <div class="invalid-feedback"></div>
                        </div>


                        <div class="mb-3">
                            <label>ဆောင်ရွက်မည့်နှစ်</label>
                            <input type="number" class="form-control" id="edit_operation_year" name="operation_year">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">မလုပ်ဆောင်ပါ</button>
                        <button type="submit" class="btn btn-primary">အပ်ဒိတ်လုပ်မည်</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">စီမံကိန်းအသေးစိတ်</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewContent">
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

                        let departmentData = {}; // Pie chart
                        let yearlyTender = {}; // Line chart

                        $.each(data, function(index, report) {
                            $('#reportsTable tbody').append(`
            <tr>
                <td>${index + 1}</td>
                <td>${report.from || ''}</td>
                <td>${report.to || ''}</td>
                <td>${report.name || ''}</td>
                <td>${report.location || ''}</td>
                <td>${report.start_month || ''}</td>
                <td>${report.end_month || ''}</td>
                <td>${report.department || ''}</td>
                <td>${report.operation_year || ''}</td>
                <td>
           <div class="dropdown">
               <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                   <i class="bi bi-three-dots-vertical"></i>
               </button>
               <ul class="dropdown-menu dropdown-menu-end">
                   <li>
                       <a href="javascript:void(0);" class="dropdown-item view-btn" data-id="${report.id}">
                           <i class="bi bi-eye"></i> View
                       </a>
                   </li>
                   <li>
                       <a href="javascript:void(0);" class="dropdown-item edit-btn" data-id="${report.id}">
                           <i class="bi bi-pencil-square"></i> Edit
                       </a>
                   </li>
                   <li>
                       <form action="/yearly_reports/${report.id}" method="POST" onsubmit="return confirm('ဖျက်မှာ သေချာလား?');">
                           <input type="hidden" name="_token" value="{{ csrf_token() }}">
                           <input type="hidden" name="_method" value="DELETE">
                           <button class="dropdown-item text-danger" type="submit">Delete</button>
                       </form>
                   </li>
               </ul>
           </div>
       </td>
            </tr>
        `);

                            // Department count
                            if (report.department) {
                                departmentData[report.department] = (departmentData[report
                                    .department] || 0) + 1;
                            }

                            // Tender price sum per year
                            if (report.operation_year && report.tender_price) {
                                let price = parseFloat(report.tender_price);
                                if (!isNaN(price)) {
                                    yearlyTender[report.operation_year] = (yearlyTender[report
                                        .operation_year] || 0) + price;
                                }
                            }
                        });

                        // ---------- Pie Chart ----------
                        const deptCtx = document.getElementById('departmentPieChart').getContext('2d');
                        if (window.departmentChart) window.departmentChart.destroy();
                        window.departmentChart = new Chart(deptCtx, {
                            type: 'pie',
                            data: {
                                labels: Object.keys(departmentData),
                                datasets: [{
                                    data: Object.values(departmentData),
                                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56',
                                        '#4BC0C0', '#9966FF', '#FF9F40'
                                    ]
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    },
                                    title: {
                                        display: true,
                                        text: 'Reports by Department'
                                    }
                                }
                            }
                        });
                        $('#reportsTable').DataTable({
                            dom: 'Bflrtip',
                            buttons: [
                                'copy', 'excel', 'print'
                            ]
                        });
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

            $(document).on('click', '.view-btn', function() {
                var id = $(this).data('id');

                $('#viewContent').html('<p>Loading...</p>');

                $.ajax({
                    url: '/yearly_reports/' + id,
                    type: 'GET',
                    success: function(data) {
                        var content = `
                         <p><strong>From:</strong> ${data.from}</p>
                          <p><strong>To:</strong> ${data.to}</p>
                <p><strong>စီမံကိန်းအမည်:</strong> ${data.name}</p>
                <p><strong>တည်နေရာ:</strong> ${data.location}</p>
                <p><strong>စတင်မည့်ကာလ:</strong> ${data.start_month}</p>
                <p><strong>ပြီးဆုံးမည့်ကာလ:</strong> ${data.end_month}</p>
                <p><strong>ဌာန/အဖွဲ့အစည်း:</strong> ${data.department}</p>
                <p><strong>စုစုပေါင်းရင်းနှီးမြှုပ်နှံမည့်ငွေ:</strong> ${data.total_investment}</p>
                <p><strong>ဆောင်ရွက်သည့်နှစ်:</strong> ${data.operation_year}</p>
                <p><strong>တိုင်းဒေသကြီးဘတ်ဂျက်:</strong> ${data.regional_budget}</p>
                <p><strong>တင်ဒါအောင်မြင်သည့်စျေးနှုန်း:</strong> ${data.tender_price}</p>
            `;
                        $('#viewContent').html(content);
                        var viewModal = new bootstrap.Modal(document.getElementById(
                            'viewModal'));
                        viewModal.show();
                    },
                    error: function() {
                        $('#viewContent').html('<p>Data could not be loaded.</p>');
                    }
                });
            });

            var editModal = new bootstrap.Modal(document.getElementById('editModal'));


            // Edit button click → modal ဖွင့်ပြီး data load

            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: '/yearly_reports/' + id,
                    type: 'GET',
                    success: function(data) {
                        $('#edit_from').val(data.from);
                        $('#edit_to').val(data.to);
                        $('#edit_id').val(data.id);
                        $('#edit_name').val(data.name);
                        $('#edit_location').val(data.location);
                        $('#edit_start_month').val(data.start_month);
                        $('#edit_end_month').val(data.end_month);
                        $('#edit_department').val(data.department);
                        $('#edit_total_investment').val(data.total_investment);
                        $('#edit_operation_year').val(data.operation_year);
                        $('#edit_regional_budget').val(data.regional_budget);
                        $('#edit_tender_price').val(data.tender_price);

                        // Show the modal
                        editModal.show();
                    },
                    error: function() {
                        alert('Data မရနိုင်ပါ။');
                    }
                });
            });


            // Submit edit form with AJAX

            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                let id = $('#edit_id').val();
                let formData = $(this).serialize();

                $.ajax({
                    url: '/yearly_reports/' + id,
                    type: 'POST', // Use POST with _method=PUT
                    data: formData,
                    success: function(res) {

                        // Hide modal properly in Bootstrap 5
                        editModal.hide();

                        loadTable(); // refresh table
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, val) {
                                let input = $('#edit_' + key);
                                input.addClass('is-invalid');
                                input.next('.invalid-feedback').text(val[0]);
                            });
                        }
                    }
                });
            });
        });

        // Toggle between list and charts
        $('#toggleBtn').click(function() {
            if ($('#listContainer').is(':visible')) {
                $('#listContainer').hide();
                $('#chartsContainer').show();
                $(this).text('Show List').removeClass('btn-primary').addClass('btn-success');
            } else {
                $('#chartsContainer').hide();
                $('#listContainer').show();
                $(this).text('Show Charts').removeClass('btn-success').addClass('btn-primary');
            }
        });
    </script>
@endsection
