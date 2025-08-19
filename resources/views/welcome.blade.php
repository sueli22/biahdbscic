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
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Main CSS File -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <style>
        /* Hide ugly default Google banner */
        .goog-te-banner-frame.skiptranslate,
        .goog-te-gadget-icon {
            display: none !important;
        }

        body {
            top: 0px !important;
        }

        /* Style the translate dropdown */
        #google_translate_element {
            text-align: center;
            margin: 10px auto;
        }

        #google_translate_element select {
            background: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 14px;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        #google_translate_element select:hover {
            background: #e9ecef;
            border-color: #adb5bd;
        }

        .goog-te-banner-frame.skiptranslate {
            display: none !important;
        }

        .goog-te-gadget-icon {
            display: none !important;
        }

        /* Reset body always */
        body {
            top: 0px !important;
            margin-top: 0 !important;
            position: static !important;
        }

        .VIpgJd-ZVi9od-ORHb *,
        .VIpgJd-ZVi9od-ORHb,
        .VIpgJd-ZVi9od-ORHb img {
            display: none !important;
        }
    </style>

</head>

<body class="index-page">

    <header id="header" class="header dark-background d-flex flex-column"
        style="background-color: {{ $web->color ?? '#ffffff' }};">
        <i class="header-toggle d-xl-none bi bi-list"></i>

        <div class="profile-img" style="margin-top:30px">
            <img src="{{ !empty($web->logoimg) ? asset('logo/' . $web->logoimg) : asset('img/logo/logo.jpg') }}"
                alt="logo" class="img-fluid">
        </div>

        <a href="{{ url('/') }}" class="logo d-flex align-items-center justify-content-center">
            <span class="sitename">စီမံကိန်းနှင့်ဘဏ္ဍာရေးဝန်ကြီးဌာန</span>
        </a>

        <div id="google_translate_element" class="fixed-lan-btn" style="margin: 10px; text-align:center;"></div>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="#hero" class="active"><i class="bi bi-house navicon"></i>ပင်မစာမျက်နှာ</a></li>
                <li><a href="#about"><i class="bi bi-person navicon"></i>သမိုင်းကြောင်း</a></li>
                <li><a href="{{ route('home.yearly') }}"><i
                            class="bi bi-file-earmark-text navicon"></i>စီမံကိန်းနှစ်ပတ်လည်အစီရင်ခံစာများ</a></li>
                <li><a href="{{ route('welcome.news.index') }}"><i
                            class="bi bi-file-earmark-text navicon"></i>သတင်းများ</a></li>
                <li><a href="#mail"><i class="bi bi-envelope navicon"></i>ဌာနဆိုင်ရာမေးလ်ပို့ရန် </a></li>
                <li><a href="#contact"><i class="bi bi-envelope navicon"></i>ဆက်သွယ်ရန်</a></li>
            </ul>
        </nav>

    </header>
    <main class="main">
        <a href="{{ route('login') }}" class="fixed-login-btn">အကောင့်၀င်ရောက်ရန်</a>
        <!-- Resume Section -->
        <section id="hero" class="hero section" style="position: relative;">

            <!-- Background Image -->
            <div class="hero-bg"
                style="
      position: absolute;
      inset: 0;
      z-index: 1;
      overflow: hidden;
  ">
                <img src="{{ !empty($web->bgimg) ? asset('bg/' . $web->bgimg) : asset('img/logo/htd.jpg') }}"
                    alt="Background"
                    style="
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 1; /* optional overlay effect */
    ">
            </div>

            <!-- Text content -->
            <div class="container section-title"
                style="
      position: relative;
      z-index: 2;
      color: white;
      padding: 40px;
  ">
                <ul class="custom-list">
                    <li>ပြည်သူ့ဘဏ္ဍာရေးစီမံခန့်ခွဲမှုစနစ် ဖွံ့ဖြိုးတိုးတက်ရေးအတွက် ပူးပေါင်းပါဝင်အားဖြည့်ဆောင်ရွက်ရန်။
                    </li>
                    <li>ငွေကြေးစီမံခန့်ခွဲမှုနှင့် ကြွေးမြီစီမံခန့်ခွဲမှုကို ခိုင်မာအားကောင်းစေရန်။</li>
                    <li>ပြည်ထောင်စုဘဏ္ဍာရန်ပုံငွေစာရင်းပေါင်းချုပ်ခြင်းနှင့် အကောင်အထည်ဖော်မှုကို
                        စောင့်ကြည့်အစီရင်ခံတင်ပြသည့်စနစ် စဉ်ဆက်မပြတ် ဖွံ့ဖြိုးတိုးတက်စေရန်။</li>
                </ul>
            </div>
        </section>

        <section id="about" class="about section">
            <!-- Section Title -->
            <div class="container section-title">
                <h2>စီမံကိန်းရေးဆွဲရေး ဦးစီးဌာနအဆင့်ဆင့်ပြောင်းလဲ ဖွဲစည်းလာမှု</h2>
            </div><!-- End Section Title -->
            <div class="container">
                <ul class="timeline-list">
                    <li>ပြည်ထောင်စုသမ္မတမြန်မာနိုင်ငံတွင် ၁၉၄၆ ခုနှစ်တွင် အမျိုးသားစီမံကိန်းဘုတ်အဖွဲ့ကို
                        ဖွဲ့စည်းခဲ့ပါသည်။</li>
                    <li>၁၉၄၇ ခုနှစ်တွင် စီးပွါးရေးစီမံကိန်းဘုတ်အဖွဲ့ အဖြစ် ပြောင်းလဲခဲ့ပါသည်။</li>
                    <li>၁၉၄၉-၅၀ ခုနှစ်တွင် စီးပွါးရေးကောင်စီ အဖြစ် လည်းကောင်း၊ ၁၉၅၁-၅၂ ခုနှစ်တွင် စီးပွါးရေးနှင့်
                        လူမှုရေးဘုတ်အဖွဲ့ အဖြစ် လည်းကောင်း၊ ၁၉၆၁-၆၂ ခုနှစ်အထိ ပြောင်းလဲ ပေါ်ပေါက်လာခဲ့ပါသည်။</li>
                    <li>၁၉၄၈ ခုနှစ်မှ ၁၉၇၂ ခုနှစ် မတ်လ (၁၄) ရက်နေ့အထိ အမျိုးသားစီမံကိန်းဝန်ကြီးဌာန အဖြစ် ဖွဲ့စည်း
                        တည်ရှိခဲ့ပါသည်။</li>
                    <li>၁၉၇၂ ခုနှစ် မတ်လ (၁၅) ရက်နေ့မှစ၍ စီမံကိန်းနှင့် ဘဏ္ဍာရေး ဝန်ကြီးဌာန၊ စီမံကိန်းရေးဆွဲရေး ဦးစီးဌာန
                        အဖြစ် လည်းကောင်း၊</li>
                    <li>နိုင်ငံတော်ငြိမ်ဝပ်ပိပြားမှု တည်ဆောက်ရေးအဖွဲ့၏ ၁၉၉၃ ခုနှစ် ဖေဖော်ဝါရီ (၁၇) ရက်နေ့၊ နေ့စွဲပါ
                        အမိန့်ကြော်ငြာစာအမှတ် (၁၂/၉၃) ဖြင့် အမျိုးသားစီမံကိန်းနှင့် စီးပွါးရေးဖွံဖြိုးတိုးတက်မှု
                        ဝန်ကြီးဌာန၊ စီမံကိန်းရေးဆွဲရေး ဦးစီးဌာန အဖြစ် အဆင့်ဆင့် ပြင်ဆင် ဖွဲ့စည်းလာခဲ့ပါသည်။</li>
                    <li>၂၀၁၆ ခုနှစ်တွင် စီမံကိန်းနှင့် ဘဏ္ဍာရေး ဝန်ကြီးဌာန၊ စီမံကိန်းရေးဆွဲရေး ဦးစီးဌာန အဖြစ်လည်းကောင်း
                    </li>
                    <li>၂၀၁၉ ခုနှစ် နိုဝင်ဘာလ (၂၉) ရက်နေ့တွင် စီမံကိန်း၊ ဘဏ္ဍာရေးနှင့် စက်မှုဝန်ကြီးဌာန၊
                        စီမံကိန်းရေးဆွဲရေးဦးစီးဌာန အဖြစ်လည်းကောင်း ပြောင်းလဲခဲ့ပါသည်။</li>
                </ul>
            </div>
        </section><!-- /About Section -->

        <!-- Contact Section -->
        <section id="mail" class="contact section contact-nw mail">
            <div class="container mt-4 mail">
                <div class="card mail-card shadow p-4">
                    <h4 class="mb-4">📧 အီးမေးလ်ပေးပို့ခြင်း</h4>

                    <form action="{{ route('sendmail.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">ပေးပို့သူ Email</label>
                            <input type="email" name="from" class="form-control" placeholder="example@gmail.com"
                                value="{{ old('from') }}">
                            @error('from')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label class="form-label">ဌာန</label>
                            <input type="text" name="department" class="form-control"
                                placeholder="ဥပမာ - အစိုးရဌာန" value="{{ old('department') }}">
                            @error('department')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ဖုန်းနံပါတ်</label>
                            <input type="text" name="phone" class="form-control" placeholder="09xxxxxxx"
                                value="{{ old('phone') }}">
                            @error('phone')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ခေါင်းစဉ်</label>
                            <input type="text" name="title" class="form-control" placeholder="စာခေါင်းစဉ်"
                                value="{{ old('title') }}">
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">စာအကြောင်းအရာ</label>
                            <textarea name="body" class="form-control" rows="4" placeholder="စာအကြောင်းအရာရေးပါ...">{{ old('body') }}</textarea>
                            @error('body')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ဖိုင်တွဲ</label>
                            <input type="file" name="file" class="form-control">
                            @error('file')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">📨 စာပို့မည်</button>
                    </form>
                </div>
            </div>
        </section>
        <!-- /Contact Section -->

        <!-- Contact Section -->
        <section id="contact" class="contact section contact-nw">
            <!-- Section Title -->
            <div class="container section-title">
                <h3>ဆက်သွယ်ရန်လိပ်စာ</h3>
            </div>
            <div class="card shadow-sm" style="max-width: 700px; margin: 20px auto;">
                <div class="card-body">
                    <!-- လိပ်စာ Row -->
                    <div class="row align-items-center mb-2">
                        <div class="col-md-3 d-flex align-items-center">
                            <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                            <h5 class="card-subtitle mb-0 text-muted">လိပ်စာ</h5>
                        </div>
                        <div class="col-md-9">
                            <p class="card-text mb-0">
                                ဟင်္သာတမြို့မှ (၄) မိုင်အကွာ၊ ဟင်္သာတ-ပုသိမ်ကားလမ်းဘေး၊
                                အနောက်တံခွန်တိုင်ကျေးရွာအုပ်စု၊ ကုန်းကြီးကျေးရွာ
                            </p>
                        </div>
                    </div>
                    <hr>

                    <!-- ဖုန်း Row -->
                    <div class="row align-items-center mb-2">
                        <div class="col-md-3 d-flex align-items-center">
                            <i class="bi bi-telephone-fill text-muted me-2"></i>
                            <h6 class="card-subtitle mb-0 text-muted">ဖုန်း</h6>
                        </div>
                        <div class="col-md-9">
                            <p class="card-text mb-0">၀၄၄-၂၁၁၄၅</p>
                            <p class="card-text mb-0">၀၄၄-၂၁၆၇၉</p>
                            <p class="card-text mb-0">၀၉-၄၀၄၉၅၃၈၂၃</p>
                        </div>
                    </div>
                    <hr>

                    <!-- Email Row -->
                    <div class="row align-items-center mb-3">
                        <div class="col-md-3 d-flex align-items-center">
                            <i class="bi bi-envelope-fill text-muted me-2"></i>
                            <h6 class="card-subtitle mb-0 text-muted">Email</h6>
                        </div>
                        <div class="col-md-9">
                            <a href="mailto:kyawkhaingoo@gmail.com" class="card-link">kyawkhaingoo@gmail.com</a>
                        </div>
                    </div>

                    <!-- Google Map Embed -->
                    <div class="row">
                        <div class="col-12">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3126.7450313624986!2d95.43368607624012!3d17.599801581725895!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30c0c10045367eed%3A0x9c88b19ec5970fa5!2z4YCF4YCu4YCZ4YC24YCA4YCt4YCU4YC64YC44YCb4YCx4YC44YCG4YC94YCy4YCb4YCx4YC44YCm4YC44YCF4YCu4YC44YCM4YCs4YCUKOGAgeGAm-GAreGAr-GAhOGAuivhgJnhgLzhgK3hgK_hgLfhgJThgJrhgLop!5e0!3m2!1sen!2smm!4v1755180163759!5m2!1sen!2smm"
                                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </main>

    <footer id="footer" class="footer position-relative light-background">
        {!! $web->footer !!}
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/typed.js/typed.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/waypoints/noframework.waypoints.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        $(document).ready(function() {
            $('html, body').animate({
                scrollTop: $('#mail').offset().top
            }, 100);
        });
    </script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'မေးလ် ပေးပို့ခြင်း အောင်မြင်ပါသည်',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Use the Blade variable to get the color
            var headerColor = "{{ $web->color ?? '#ffffff' }}"; // fallback to white
            var header = document.getElementById('header');
            if (header) {
                header.style.backgroundColor = headerColor;
            }
        });
    </script>
    <!-- 🌍 Google Translate -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'my',
                includedLanguages: 'my,en,zh-CN,th', // Added Thai (th)
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');

            // Apply CSS via JS
            const container = document.getElementById('google_translate_element');
            container.style.fontFamily = 'Arial, sans-serif';
            container.style.fontSize = '14px';
            container.style.backgroundColor = '#f0f0f0';
            container.style.padding = '5px 0px';
            container.style.borderRadius = '8px';
            container.style.display = 'inline-block';

            // Style the dropdown after it's rendered
            setTimeout(() => {
                const select = container.querySelector('select');
                if (select) {
                    select.style.backgroundColor = '#000000';
                    select.style.border = '1px solid #ccc';
                    select.style.borderRadius = '4px';
                    select.style.padding = '2px 5px';
                    select.style.fontSize = '14px';
                }
            }, 500); // Wait for the widget to load
        }
    </script>

    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>



</body>

</html>
