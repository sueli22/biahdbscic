<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>စီမံကိန်းနှင့်ဘဏ္ဍာရေးဝန်ကြီးဌာန(MPOF)</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Favicons -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Optional DataTables Buttons CSS (if you use buttons extension) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/bootstrap/plugin/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet">



    <!-- Main CSS File -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">


</head>

<body class="index-page">

    <header id="header" class="header dark-background d-flex flex-column" style="background-color: {{ $web->color ?? '#ffffff' }};">
        <i class="header-toggle d-xl-none bi bi-list"></i>

         <div class="profile-img">
            <img src="{{ !empty($web->logoimg) ? asset('logo/' . $web->logoimg) : asset('img/logo/logo.jpg') }}" alt="logo" class="img-fluid">
        </div>

        <a href="#" class="logo d-flex align-items-center justify-content-center">
            <span class="sitename">စီမံကိန်းနှင့်ဘဏ္ဍာရေးဝန်ကြီးဌာန</span>
        </a>
        <nav id="navmenu" class="navmenu">
            <ul>
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house navicon"></i>ပင်မစာမျက်နှာ
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.show.attendence.list') }}"
                        class="{{ request()->routeIs('admin.show.attendence.list') ? 'active' : '' }}">
                        <i class="bi bi-house navicon"></i>နေ့စဥ်ရုံးတတ်လက်မှတ်
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.sendmail.list') }}"
                        class="{{ request()->routeIs('admin.sendmail.list') ? 'active' : '' }}">
                        <i class="bi bi-house navicon"></i>စီမံကိန်းဘဏ္ဍာ အတွက် အကြောင်းပြန်ရန်
                    </a>
                </li>
                <li>
                    <a href="{{ route('yearly_reports.index') }}"
                        class="{{ request()->routeIs('yearly_reports.index') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart navicon"></i>နှစ်ပတ်လည်အစီရင်ခံစာများ
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.leave_request.list') }}"
                        class="{{ request()->routeIs('admin.leave_request.list') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text navicon"></i>ခွင့်တိုင်စာများ
                    </a>
                </li>

                <li>
                    <a href="{{ route('staff.list') }}"
                        class="{{ request()->routeIs('staff.list') ? 'active' : '' }}">
                        <i class="bi bi-person navicon"></i>၀န်ထမ်းအကောင့် စီမံမည်
                    </a>
                </li>
                <li>
                    <a href="{{ route('news.index') }}" class="{{ request()->is('news.index.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text navicon"></i>သတင်းများ
                    </a>
                </li>
                <li>
                    <a href="{{ route('positions.index') }}"
                        class="{{ request()->routeIs('positions.index') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text navicon"></i>ရာထူးနှင့်လစာ
                    </a>
                </li>
                <li>
                    <a href="{{ route('payroll.index') }}"
                        class="{{ request()->routeIs('payroll.index') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text navicon"></i>လစာပေးမည်
                    </a>
                </li>
                <li>
                    <a href="{{ route('employee_housing_request.list') }}"
                        class="{{ request()->routeIs('employee_housing_request.list') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text navicon"></i>အိမ်ယာ လျှောက်လွှာ စီမံမှု
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.profile.show') }}"
                        class="{{ request()->routeIs('admin.profile.show') ? 'active' : '' }}">
                        <i class="bi bi-hdd-stack navicon"></i>မိမိအကောင့်
                    </a>
                </li>
                <li>
                    <a href="{{ route('web.index') }}" class="{{ request()->routeIs('web.index') ? 'active' : '' }}">
                        <i class="bi bi-hdd-stack navicon"></i>Setting
                    </a>
                </li>
                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-envelope navicon"></i>ထွက်မည်
                    </a>
                </li>
            </ul>
        </nav>

    </header>

   <main class="main">
    {{-- Admin Info Section --}}
    <div class="admin-info d-flex align-items-center p-3 shadow-sm"
         style="position: fixed; top: 20px; right: 20px; width: 240px; background:#f8f9fa; border-radius: 12px; z-index: 1000;">

        <img
            src="{{ asset('storage/images/' . (Auth::user()->image ?? 'default.jpg')) }}"
            alt="Admin Image"
            class="rounded-circle me-2"
            style="width: 50px; height: 50px; object-fit: cover; border:2px solid #ddd;"
        >

        <span class="fw-bold text-dark">
            {{ Auth::user()->name ?? 'Admin' }}
        </span>
    </div>

    {{-- Page Content --}}
    @yield('content')
</main>



       <footer id="footer" class="footer position-relative light-background">
               {!! $web->footer !!}
    </footer>
    <!-- Load jQuery FIRST -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <!-- Then Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Then DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/bootstrap/plugin/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'အောင်မြင်ပါသည်!',
                text: "{{ session('success') }}",
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'အမှားဖြစ်ပါသည်!',
                text: "{{ session('error') }}",
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var headerColor = "{{ $web->color ?? '#ffffff' }}"; // fallback to white
            var header = document.getElementById('header');
            if (header) {
                header.style.backgroundColor = headerColor;
            }
        });
    </script>

    <!-- Your custom scripts last -->
    @yield('scripts')

</body>

</html>
