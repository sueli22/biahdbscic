<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>စီမံကိန်းနှင့်ဘဏ္ဍာရေးဝန်ကြီးဌာန(MPOF)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">
    <main class="container main">
        <div class="mt-4">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ url('/') }}">
                    <img src="{{ !empty($web->logoimg) ? asset('logo/' . $web->logoimg) : asset('img/logo/logo.jpg') }}" alt="logo"
                        style="max-width: 40px; height: 40px; margin-right: 20px; border-radius: 20%;">
                </a>
                <h2 class="mb-0">စီမံကိန်းနှစ်ပတ်လည်အစီရင်ခံစာများ</h2>
            </div>

            <!-- Filter -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" id="from" class="form-control" placeholder="ရှာဖွေရန် နှစ်အစထည့်ပါ">
                </div>
                <div class="col-md-3">
                    <input type="text" id="to" class="form-control" placeholder="ရှာဖွေရန် နှစ်အဆုံးထည့်ပါ">
                </div>
                <div class="col-md-2">
                    <button id="filter" class="btn btn-primary">ရှာဖွေမည်</button>
                </div>
            </div>

            <!-- DataTable -->
            <div id="tableContainer">
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
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <!-- Charts -->
            <div id="chartContainer" style="display:none;">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <canvas id="departmentPieChart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <canvas id="tenderYearChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer id="footer" class="footer position-relative light-background">
        {!! $web->footer !!}
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></scrip
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            function loadTable(from = '', to = '') {
                $.ajax({
                    url: '{{ route('yearly_reports.list') }}',
                    type: 'GET',
                    dataType: 'json',
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
                        let yearlyTender = {}; // Bar chart

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

                        // ---------- Bar Chart ----------
                        let sortedYears = Object.keys(yearlyTender).sort((a, b) => yearlyTender[b] -
                            yearlyTender[a]);
                        const tenderCtx = document.getElementById('tenderYearChart').getContext('2d');
                        if (window.tenderChart) window.tenderChart.destroy();
                        window.tenderChart = new Chart(tenderCtx, {
                            type: 'bar',
                            data: {
                                labels: sortedYears,
                                datasets: [{
                                    label: 'Tender Price (ကျပ်)',
                                    data: sortedYears.map(y => yearlyTender[y]),
                                    backgroundColor: '#36A2EB'
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    title: {
                                        display: true,
                                        text: 'Tender Price per Year (Max → Min)'
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });

                        // ---------- DataTable ----------
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

            // Toggle Table/Charts
            $('#toggleCharts').click(function() {
                $('#tableContainer, #chartContainer').toggle();
                $(this).text($('#chartContainer').is(':visible') ? 'Show Table' : 'Show Charts');
            });
        });
    </script>
</body>

</html>
