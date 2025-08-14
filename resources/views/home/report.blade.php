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
        <div class=" mt-4">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ url('/') }}">
                    <img src="/img/logo/logo.jpg" alt="logo"
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
            <table class="table table-bordered" id="reportsTable" style="font-size:12px;">
                <thead>
                    <tr>
                        <th>နံပါတ်</th>
                        <th>မှ</th>
                        <th>အထိ</th>
                        <th>လုပ်ငန်းအမည်</th>
                        <th>တည်နေရာ</th>
                        <th>စတင်မည့်ကာလ</th>
                        <th>ပြီးဆုံးမည့်ကာလ</th>
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
    </main>

    <footer id="footer" class="footer position-relative light-background mt-4">
        <div class="container">
            <div class="copyright text-center">
                <p>© <span>Copyright</span> <strong class="px-1 sitename">iPortfolio</strong> <span>All Rights
                        Reserved</span></p>
            </div>
            <div class="credits">
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> Distributed by <a
                    href="https://themewagon.com">ThemeWagon</a>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                                     <td>${report.total_investment ? report.total_investment + ' ကျပ်' : ''}</td>
                                    <td>${report.operation_year || ''}</td>
                                     <td>${report.regional_budget ? report.regional_budget + ' ကျပ်' : ''}</td>
        <td>${report.tender_price ? report.tender_price + ' ကျပ်' : ''}</td>
                                </tr>
                            `);
                        });

                        $('#reportsTable').DataTable({
                            dom: 'Bfrtip',
                            buttons: [{
                                extend: 'csvHtml5',
                                text: '<i class="bi bi-download"></i> Download CSV',
                                className: 'btn btn-success btn-sm custom-download-btn', // custom class added
                                title: 'Yearly Reports',
                                bom: true,
                                exportOptions: {
                                    columns: ':visible'
                                }
                            }]
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

            // Filter button click
            $('#filter').click(function() {
                loadTable($('#from').val(), $('#to').val());
            });
        });
    </script>
</body>

</html>
