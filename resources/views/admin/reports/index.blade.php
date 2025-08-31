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
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createModal"
                    style="font-size: 12px">
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
                        <th>ဆောင်ရွက်မည့်ဌာန/အဖွဲ့အစည်း</th>
                        <th>ဆောင်ရွက်သည့်နှစ်</th>
                        <th>ဘဏ္ဍာငွေ သန်းပေါင်း</th>
                        <th>ဆောင်ရွက်မှု အခြေနေ</th>
                        <th>အချိန်ကာလ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="chartsContainer" style="display:none;margin-left:170px">
            <div class="row">
                <div class="col-md-10 mt-5 mb-3">
                    <canvas id="budgetChart"></canvas>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 mt-5 mb-3">
                    <canvas id="termCountChart"></canvas>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 mt-5 mb-3">
                    <canvas id="statusChart"></canvas>
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
                                <label>ဌာန/အဖွဲ့အစည်း</label>
                                <input type="text" name="department" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>ဆောင်ရွက်သည့်နှစ်</label>
                                <input type="text" name="operation_year" class="form-control" min="1900">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>ဘဏ္ဍာငွေ သန်းပေါင်း</label>
                                <input type="number" step="0.01" name="total_budget" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>ဆောင်ရွက်မှု အခြေနေ</label>
                                <select name="status_report" class="form-control">
                                    <option value="">-- ရွေးချယ်ရန် --</option>
                                    <option value="finish">ပြီး</option>
                                    <option value="unfinish">မပြီးစီး</option>
                                    <option value="containue">ဆောင်ရွက်ဆဲ</option>
                                </select>
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
                            <label>ဌာန/အဖွဲ့အစည်း</label>
                            <input type="text" class="form-control" id="edit_department" name="department">
                            <div class="invalid-feedback"></div>
                        </div>


                        <div class="mb-3">
                            <label>ဆောင်ရွက်မည့်နှစ်</label>
                            <input type="number" class="form-control" id="edit_operation_year" name="operation_year">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>ဘဏ္ဍာငွေ သန်းပေါင်း</label>
                            <input type="number" step="0.01" class="form-control" id="edit_total_budget"
                                name="total_budget">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>ဆောင်ရွက်မှု အခြေနေ</label>
                            <select class="form-control" id="edit_status_report" name="status_report">
                                <option value="">-- ရွေးချယ်ရန် --</option>
                                <option value="finish">ပြီးစီး</option>
                                <option value="unfinish">မပြီးစီး</option>
                                <option value="containue">ဆောင်ရွက်ဆဲ</option>
                            </select>
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
            let budgetChart = null;
            let termChart = null;
            let statusChart = null;

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

                        let budgetByDepartment = {};
                        let termCounts = {
                            short: 0,
                            mid: 0,
                            long: 0
                        };
                        let statusCounts = {
                            finish: 0,
                            unfinish: 0,
                            containue: 0
                        };
                        $.each(data, function(index, report) {
                            $('#reportsTable tbody').append(`
            <tr>
                <td>${index + 1}</td>
                <td>${report.from || ''}</td>
                <td>${report.to || ''}</td>
                <td>${report.name || ''}</td>
                <td>${report.location || ''}</td>
                <td>${report.department || ''}</td>
                <td>${report.operation_year || ''}</td>
                <td>${report.total_budget ?? ''}</td>

<td>
  ${report.title_report === 'mid'
        ? 'ကာလလတ်'
        : report.title_report === 'long'
            ? 'ကာလရှည်'
            : report.title_report === 'short'
                ? 'ကာလတို'
                : ''}
</td>

<td>
  ${report.status_report === 'finish'
        ? 'ပြီးစီး'
        : report.status_report === 'unfinish'
            ? 'မပြီးစီး'
            : report.status_report === 'containue'
                ? 'ဆောင်ရွက်ဆဲ'
                : ''}
</td>

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

                            if (report.department && report.total_budget) {
                                budgetByDepartment[report.department] = (budgetByDepartment[
                                    report.department] || 0) + parseFloat(report
                                    .total_budget);
                            }


                            if (report.status_report === 'finish') statusCounts.finish++;
                            else if (report.status_report === 'unfinish') statusCounts
                                .unfinish++;
                            else if (report.status_report === 'containue') statusCounts
                                .containue++;

                            // Term Counts
                            if (report.title_report === 'short') termCounts.short++;
                            else if (report.title_report === 'mid') termCounts.mid++;
                            else if (report.title_report === 'long') termCounts.long++;
                        });

                        // Budget Bar Chart
                        const budgetCtx = document.getElementById('budgetChart').getContext('2d');
                        if (budgetChart) budgetChart.destroy();
                        budgetChart = new Chart(budgetCtx, {
                            type: 'bar',
                            data: {
                                labels: Object.keys(budgetByDepartment),
                                datasets: [{
                                    label: 'စုစုပေါင်းဘဏ္ဍာငွေ',
                                    data: Object.values(budgetByDepartment),
                                    backgroundColor: ['#F02E5D', '#8530E6', '#E0A051',
                                        '#3DCC88'
                                    ]
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    title: {
                                        display: true,
                                        text: 'ဌာနအလိုက် ဘဏ္ဍာငွေ ဆောင်ရွက်မှု အခြေအနေ'
                                    },
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });

                        const statusCtx = document.getElementById('statusChart').getContext('2d');
                        if (statusChart) statusChart.destroy();
                        statusChart = new Chart(statusCtx, {
                            type: 'bar',
                            data: {
                                labels: ['ပြီးစီး(လက်ရှိပြီးစီး)',
                                    'မပြီးစီး(စီမံကိန်း ကာလအတွင်း ဆောင်ရွက်‌နေဆဲ)',
                                    'ကာလလွန်'
                                ],
                                datasets: [{
                                    label: 'ဆောင်ရွက်မှု အခြေအနေ',
                                    data: [statusCounts.finish, statusCounts.unfinish,
                                        statusCounts.containue
                                    ],
                                    backgroundColor: ['#63DDE0', '#28a745', '#dc3545',
                                        '#ffc107', '#E84699'
                                    ]
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    title: {
                                        display: true,
                                        text: 'ဆောင်ရွက်ပြီးစီးမှု အလိုက် နှစ်စဥ် ဆောင်ရွက်မှု အခြေအနေ'
                                    },
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });


                        // Term Count Chart
                        const termCtx = document.getElementById('termCountChart').getContext('2d');
                        if (termChart) termChart.destroy();
                        termChart = new Chart(termCtx, {
                            type: 'bar',
                            data: {
                                labels: ['ကာလတို', 'ကာလလတ်', 'ကာလရှည်'],
                                datasets: [{
                                    label: 'Project Count',
                                    data: [termCounts.short, termCounts.mid, termCounts
                                        .long
                                    ],
                                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    title: {
                                        display: true,
                                        text: 'ကာလ အလိုက် နှစ်စဥ် ဆောင်ရွက်မှု အခြေအနေ'
                                    },
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });

                        $('#reportsTable').DataTable({
                            dom: 'Bflrtip',
                            buttons: ['copy', 'excel', 'print']
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
                        let reportType = '';
                        if (data.title_report === 'short') reportType = 'ကာလတို';
                        else if (data.title_report === 'mid') reportType = 'ကာလလတ်';
                        else if (data.title_report === 'long') reportType = 'ကာလရှည်';

                        let reportStatus = '';
                        if (data.status_report === 'finish') reportStatus = 'ပြီးစီး';
                        else if (data.status_report === 'unfinish') reportStatus = 'မပြီးစီး';
                        else if (data.status_report === 'containue') reportStatus =
                            'ဆောင်ရွက်ဆဲ';
                        var content = `
                         <p><strong>From:</strong> ${data.from}</p>
                          <p><strong>To:</strong> ${data.to}</p>
                <p><strong>စီမံကိန်းအမည်:</strong> ${data.name}</p>
                <p><strong>တည်နေရာ:</strong> ${data.location}</p>
                <p><strong>ဌာန/အဖွဲ့အစည်း:</strong> ${data.department}</p>
                <p><strong>စုစုပေါင်းဘဏ္ဍာငွေ:</strong> ${data.total_budget ?? ''}</p>
                <p><strong>ဆောင်ရွက်သည့်နှစ်:</strong> ${data.operation_year}</p>
                  <p><strong>အချိန်ကာလ:</strong> ${reportType}</p>
            <p><strong>ဆောင်ရွက်သည့်အခြေအနေ:</strong> ${reportStatus}</p>
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
                        $('#edit_department').val(data.department);
                        $('#edit_total_investment').val(data.total_investment);
                        $('#edit_operation_year').val(data.operation_year);
                        $('#edit_total_budget').val(data.total_budget ?? '');
                        $('#edit_status_report').val(data.status_report ?? '');
                        $('#edit_title_report').val(data.title_report ?? '');
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
