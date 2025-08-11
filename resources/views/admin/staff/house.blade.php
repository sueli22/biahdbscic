@extends('admin.layout')

@section('content')
    <div class="container card p-4">
        <h2>အိမ်ယာလျှောက်လွှာ စာရင်း</h2>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('employee_housing_request.list') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>အားလုံး</option>
                        <option value="{{ \App\Models\EmployeeHousing::STATUS_PENDING }}"
                            {{ ($status ?? '') === \App\Models\EmployeeHousing::STATUS_PENDING ? 'selected' : '' }}>
                            စောင့်ဆိုင်းဆဲ</option>
                        <option value="{{ \App\Models\EmployeeHousing::STATUS_APPROVED }}"
                            {{ ($status ?? '') === \App\Models\EmployeeHousing::STATUS_APPROVED ? 'selected' : '' }}>
                            လက်ခံပြီး</option>
                        <option value="{{ \App\Models\EmployeeHousing::STATUS_REJECT }}"
                            {{ ($status ?? '') === \App\Models\EmployeeHousing::STATUS_REJECT ? 'selected' : '' }}>ငြင်းပယ်
                        </option>
                    </select>
                </div>
            </div>
        </form>

        <!-- Data Table -->
        <table id="housingTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>အမည်</th>
                    <th>မိသားစုဝင်</th>
                    <th>မြို့နယ်</th>
                    <th>ဖော်ပြချက်</th>
                    <th>ပုံ</th>
                    <th>အခြေအနေ</th>
                    <th>တင်သွင်းရက်</th>
                    <th>လုပ်ဆောင်ချက်</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($housings as $req)
                    <tr>
                        <td>{{ $req->user->name ?? '---' }}</td>
                        <td>{{ $req->family_member }}</td>
                        <td>{{ $req->township }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($req->description, 100) }}</td>

                        <td>
                            @if ($req->house_hold_img)
                                <img src="{{ asset('storage/' . $req->house_hold_img) }}" width="80">
                            @else
                                ---
                            @endif
                        </td>
                        <td>
                            @if ($req->status == \App\Models\EmployeeHousing::STATUS_APPROVED)
                                <span class="badge bg-success">လက်ခံပြီး</span>
                            @elseif($req->status == \App\Models\EmployeeHousing::STATUS_REJECT)
                                <span class="badge bg-danger">ငြင်းပယ်</span>
                            @else
                                <span class="badge bg-warning text-dark">စောင့်ဆိုင်းဆဲ</span>
                            @endif
                        </td>
                        <td>{{ $req->submit_date }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#" class="dropdown-item text-primary btn-view"
                                            data-id="{{ $req->id }}">👁 ကြည့်မည်</a>
                                    </li>
                                    @if ($req->status == \App\Models\EmployeeHousing::STATUS_PENDING)
                                        <button type="button" class="dropdown-item text-success btn-show-approve-modal"
                                            data-id="{{ $req->id }}">
                                            ✔ လက်ခံမည်
                                        </button>

                                        <form action="{{ route('employee_housing.reject', $req->id) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure to reject this request?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="dropdown-item text-danger">✖ ငြင်းပယ်မည်</button>
                                        </form>
                                    @endif

                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">အိမ်ယာလျှောက်လွှာ အသေးစိတ်</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>အမည်:</strong> <span id="detail-name"></span></p>
                    <p><strong>မိသားစုဝင်:</strong> <span id="detail-family"></span></p>
                    <p><strong>ဖောင်တင်နေ့စွဲ:</strong> <span id="detail-submit-date"></span></p>
                    <p><strong>လက်ခံမည့်ရက်စွဲ:</strong> <span id="detail-approve-date"></span></p>
                    <p><strong>မြို့နယ်:</strong> <span id="detail-township"></span></p>
                    <p><strong>ဖော်ပြချက်:</strong> <span id="detail-description"></span></p>
                    <p><strong>ပုံ:</strong></p>
                    <img id="detail-image" src="" class="img-fluid mb-2 w-100" alt="">
                    <a id="download-image" class="btn btn-outline-primary" download>📥‌ေဒါင်းမည်</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Date Modal -->
<div class="modal fade" id="approveDateModal" tabindex="-1" aria-labelledby="approveDateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="approveDateForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveDateModalLabel">လက်ခံသည့်ရက် ရွေးချယ်ပါ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ပိတ်ရန်"></button>
                </div>
                <div class="modal-body">
                    <label for="approved_date" class="form-label">လက်ခံသည့်ရက်</label>
                    <input type="date" id="approved_date" name="approved_date" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ဖျက်ရန်</button>
                    <button type="submit" class="btn btn-success">လက်ခံမည်</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@yield('scripts')
@section('scripts')
    <script src="{{ asset('js/house.js') }}"></script>
@endsection
