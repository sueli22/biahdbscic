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
    <main class="main">

        <div class="login-container">
        <div class="login-card">
            <div class="login-wrapper">

                <!-- Image inside card -->
                <div class="login-image">
                    <img src="{{ !empty($web->logoimg) ? asset('logo/' . $web->logoimg) : asset('img/logo/logo.jpg') }}" alt="Login Illustration">
                </div>

                <!-- Form side -->
                <div class="login-form">
                    <h2>အကောင့်၀င်‌ရောက်ရန်</h2>
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <i class="fas fa-user"></i>
                            <input type="email" id="email" name="email"
                                placeholder="အသုံးပြုသူ အီးမေးလ် အကောင့်ထည့်ရန်" required>
                            @error('email')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="စကားဝှက်ထည့်ရန်"
                               >
                        </div>
                        <div class="button-row">
    <button type="submit" class="login-btn">ဝင်မည်</button>
    <a href="{{ url()->previous() }}" class="login-btn back-btn">နောက်သို့</a>
</div>

                    </form>
                </div>

            </div>
        </div>
    </div>
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
    <!-- Your custom scripts last -->
    @yield('scripts')

</body>

</html>
