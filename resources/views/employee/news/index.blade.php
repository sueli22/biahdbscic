@extends('employee.layout')

@section('content')
@if($news->isNotEmpty())
 <ul class="news-list col-md-10 mx-auto card p-5">
            @foreach ($news as $item)
                <li>
                     <p>{{ $item->created_at }} တွင် တင်ခဲ့သည်</p>
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
        @else
            <h2 class="text-center">သတင်းမရှိပါ</h2>
        @endif
@endsection
@section('scripts')
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
@endsection
