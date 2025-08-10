<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>စီမံကိန်းနှင့်ဘဏ္ဍာရေးဝန်ကြီးဌာန(MPOF)</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Optional DataTables Buttons CSS (if you use buttons extension) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">



    <!-- Main CSS File -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">


</head>

<body class="index-page">

    <header id="header" class="header dark-background d-flex flex-column">
        <i class="header-toggle d-xl-none bi bi-list"></i>

        <div class="profile-img">
            <img src="{{ asset('img/logo/logo.jpg') }}" alt="logo" class="img-fluid">
        </div>

        <a href="index.html" class="logo d-flex align-items-center justify-content-center">
            <span class="sitename">စီမံကိန်းနှင့်ဘဏ္ဍာရေးဝန်ကြီးဌာန</span>
        </a>
        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ route('dashboard') }}" class="active"><i
                            class="bi bi-house navicon"></i>ပင်မစာမျက်နှာ</a></li>
                <li><a href="{{ route('staff.list') }}"><i class="bi bi-person navicon"></i>ခွင့်တိုင်ကြားရန်</a>
                </li>
                <li><a href="#resume"><i class="bi bi-file-earmark-text navicon"></i>လစာထုတ်ယူမှု ဇယား </a></li>
                <li><a href="{{ route('positions.index') }}"><i
                            class="bi bi-file-earmark-text navicon"></i>၀န်ထမ်းအိမ်ယာ လျှောက်ရန်</a></li>
                <li><a href="{{ route('admin.profile.show') }}"><i class="bi bi-hdd-stack navicon"></i> မိမိအကောင့်</a>
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

        @yield('content')
    </main>
    <footer id="footer" class="footer position-relative light-background">

        <div class="container">
            <div class="copyright text-center ">
                <p>© <span>Copyright</span> <strong class="px-1 sitename">iPortfolio</strong> <span>All Rights
                        Reserved</span></p>
            </div>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you've purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> Distributed by <a
                    href="https://themewagon.com">ThemeWagon</a>
            </div>
        </div>

    </footer>
    <!-- Load jQuery FIRST -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <!-- Then Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Then DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Your custom scripts last -->
    @yield('scripts')

</body>

</html>
