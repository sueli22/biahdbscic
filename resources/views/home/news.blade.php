<!DOCTYPE html>
<html lang="my">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>စီမံကိန်းနှင့် ဘဏ္ဍာရေးဝန်ကြီးဌာန (MPOF)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    <style>
        ul.news-list {
            list-style-type: none;
            padding: 0;
        }

        ul.news-list li {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        ul.news-list li:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .news-content {
            white-space: pre-line;
        }

        .viewMoreBtn {
            color: #0d6efd;
            cursor: pointer;
        }

        .viewMoreBtn:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <main class="container my-5">

        <div class="d-flex align-items-center mb-3">
                <a href="{{ url('/') }}">
                    <img src="{{ !empty($web->logoimg) ? asset('logo/' . $web->logoimg) : asset('img/logo/logo.jpg') }}" alt="logo"
                        style="max-width: 40px; height: 40px; margin-right: 20px; border-radius: 20%;">
                </a>
            </div>

        <ul class="news-list col-md-8 mx-auto">
            @foreach ($news as $item)
                <li>
                    <h5>{{ $item->title }}</h5>
                    <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid rounded mb-2"
                        style="max-height:400px;width:100%; object-fit:cover;" alt="သတင်းပုံ">

                    <p class="news-content" id="newsContent{{ $item->id }}">
                        {{ Str::limit($item->content, 120) }}
                    </p>

                    @if (Str::length($item->content) > 120)
                        <button class="btn btn-primary p-0 viewMoreBtn p-2" data-id="{{ $item->id }}"
                            data-full="{{ $item->content }}" style="color: #ffffff;font-size: 12px;">
                            အကုန်ဖက်မည်
                        </button>
                    @endif
                </li>
            @endforeach

        </ul>
    </main>

    <footer id="footer" class="footer position-relative light-background">
               {!! $web->footer !!}
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.viewMoreBtn').click(function() {
                let id = $(this).data('id');
                let contentElement = $('#newsContent' + id);
                let fullText = $(this).data('full'); // ✅ safer way
                let shortText = fullText.substring(0, 120) + '...';

                if ($(this).text() === 'အကုန်ဖက်မည်') {
                    contentElement.text(fullText);
                    $(this).text('အနည်းငယ်ဖက်မည်');
                } else {
                    contentElement.text(shortText);
                    $(this).text('အကုန်ဖက်မည်');
                }
            });
        });
    </script>

</body>

</html>
