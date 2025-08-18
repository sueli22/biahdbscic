@extends('admin.layout')

@section('content')
    <!-- resources/views/admin/profile.blade.php -->

    <div class="card" style="max-width: 800px; margin: 100px auto;">
        <div class="card-body text-center">
            <img src="{{ asset('storage/images/' . ($user->image ?? 'default.jpg')) }}" alt="User Image"
    class="mb-3" style="width: 100%; height: 140px; object-fit: cover;">

            <ul class="list-group list-group-flush text-start">
                <li class="list-group-item"><strong>နာမည်:</strong> {{ $user->name }}</li>
                <li class="list-group-item"><strong>အီးမေးလ်:</strong> {{ $user->email }}</li>
                <li class="list-group-item"><strong>မွေးသက္ကရာဇ်:</strong> {{ $user->dob->format('d M, Y') }}</li>
                <li class="list-group-item"><strong>လက်ရှိနေရပ်လိပ်စာ:</strong> {{ $user->currentaddress }}</li>
                <li class="list-group-item"><strong>ဖုန်းနံပါတ်:</strong> {{ $user->phno }}</li>
                <li class="list-group-item"><strong>ဌာန:</strong> {{ $user->department }}</li>
                <li class="list-group-item"><strong>လက်ထပ်မှုအခြေအနေ:</strong>
                    {{ $user->getMarriedStatusName() }}</li>
                <li class="list-group-item"><strong>ကျား/မ:</strong> {{ $user->getGenderName() }}</li>
                <li class="list-group-item"><strong>အထက်တန်းအရာရှိ (Super User):</strong>
                    {{ $user->super_user ? 'ဟုတ်သည်' : 'မဟုတ်ပါ' }}</li>
                 <a href="{{route('admin.profile.edit')}}" class="btn admin-btn mt-3">အချက်အလက် ပြင်မည်</a>
            </ul>

        </div>
    </div>
@endsection
