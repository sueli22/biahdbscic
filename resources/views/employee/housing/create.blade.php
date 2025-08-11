@extends('employee.layout')

@section('content')
    @if ($hasRequest)
        <h3>သင့် အိမ်ယာလျှောက်ထားမှုများ</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>မိသားစုဝင်</th>
                    <th>တင်သွင်းသည့်ရက်</th>
                    <th>ဖော်ပြချက်</th>
                    <th>အတည်ပြုသည့်ရက်</th>
                    <th>အခြေအနေ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $req)
                    <tr>
                        <td>{{ $req->family_member }}</td>
                        <td>{{ $req->submit_date }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($req->description, 50) }}</td>
                        <td>{{ $req->approved_date ?? 'အတည်ပြုရက် မရှိပါ' }}</td>
                        <td>
                            @if ($req->status === 'approved')
                                လျှောက်ထားမှုကို လက်ခံပါသည်
                            @elseif ($req->status === 'reject')
                                လျှောက်ထားမှုကို ငြင်းပယ်ထားပါသည်
                            @elseif ($req->status === 'pending')
                                လျှောက်ထားမှုသည် စောင့်ဆိုင်းနေဆဲဖြစ်သည်
                            @else
                                ---
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <h3>ဝန်ထမ်းအိမ်ယာ လျှောက်လွှာ တင်ရန်</h3>
        <form action="{{ route('employee_housing_request.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="family_member" class="form-label">မိသားစုဝင်</label>
                <input type="text" name="family_member" id="family_member" class="form-control"
                    value="{{ old('family_member') }}">
                @error('family_member')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">အိမ်ထောင်စုဇယားထည့်ရန်</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                @error('image')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="township" class="form-label">လက်ရှိမြို့နယ်</label>
                <input type="text" name="township" id="township" class="form-control" value="{{ old('township') }}">
                @error('township')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">ဖော်ပြချက်</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="submit_date" class="form-label">တင်သွင်းသည့်ရက်စွဲ</label>
                <input type="date" name="submit_date" id="submit_date" class="form-control"
                    value="{{ old('submit_date') }}">
                @error('submit_date')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">တောင်းဆိုမှု တင်ပြရန်</button>
        </form>
    @endif

@endsection

@yield('scripts')
@section('scripts')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'အောင်မြင်ပါသည်',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endsection
