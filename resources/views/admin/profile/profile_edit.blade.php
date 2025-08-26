@extends('admin.layout')

@section('content')
    <!-- resources/views/admin/profile.blade.php -->

    <div class="card" style="max-width: 500px; margin: 100px auto;">
        <div class="card-body ">
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="">
                @csrf
                @method('PATCH')
                <div class="text-center mb-3">
                    <img src="{{ asset('storage/images/' . ($user->image ?? 'default.jpg')) }}" alt="User Image"
     style="width: 180px; height: 180px; object-fit: cover;border-radius: 50%;">

                </div>

                <div class="mb-3">
                    @if ($user->image)
                        <div id="file-name-display" class="mb-1">လက်ရှိ ပုံ: {{ $user->image }}</div>
                    @else
                        <div id="file-name-display" class="mb-1">တင်ထားသောပုံမရှိပါ</div>
                    @endif
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">နာမည်</label>
                    <input type="text" name="name" id="name" class="form-control "
                        value="{{ old('name', $user->name) }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">အီးမေးလ်</label>
                    <input type="email" name="email" id="email" class="form-control "
                        value="{{ old('email', $user->email) }}">
                </div>

                <div class="mb-3">
                    <label for="dob" class="form-label">မွေးသက္ကရာဇ်</label>
                    <input type="date" name="dob" id="dob" class="form-control "
                        value="{{ old('dob', $user->dob->format('Y-m-d')) }}">
                </div>

                <div class="mb-3">
                    <label for="currentaddress" class="form-label">လက်ရှိနေရပ်လိပ်စာ</label>
                    <input type="text" name="currentaddress" id="currentaddress" class="form-control "
                        value="{{ old('currentaddress', $user->currentaddress) }}">
                </div>

                <div class="mb-3">
                    <label for="phno" class="form-label">ဖုန်းနံပါတ်</label>
                    <input type="text" name="phno" id="phno" class="form-control "
                        value="{{ old('phno', $user->phno) }}">
                </div>

                <div class="mb-3">
                    <label for="department" class="form-label">ဌာန</label>
                    <input type="text" name="department" id="department" class="form-control "
                        value="{{ old('department', $user->department) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">လက်ထပ်မှုအခြေအနေ</label>
                    <select name="married_status" class="form-select ">
                        <option value="1" {{ old('married_status', $user->married_status) == 1 ? 'selected' : '' }}>
                            အိမ်ထောင်ရှိပါသည်</option>
                        <option value="0" {{ old('married_status', $user->married_status) == 0 ? 'selected' : '' }}>
                            အိမ်ထောင်မရှိပါ</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">ကျား/မ</label>
                    <select name="gender" class="form-select ">
                        <option value="1" {{ old('gender', $user->gender) == 1 ? 'selected' : '' }}>ကျား</option>
                        <option value="0" {{ old('gender', $user->gender) == 0 ? 'selected' : '' }}>မ</option>
                    </select>
                </div>
                <button type="submit" class="btn admin-btn">အချက်အလက်ပြင်ဆင်ရန်</button>
            </form>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="{{ asset('js/profile-image-upload.js') }}"></script>
        </div>
    </div>
@endsection
