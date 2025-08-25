@extends('admin.layout')

@section('content')
<div class="container card p-4">
    <h2>ခွင့် မှတ်တမ်း</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.leave_request.list') }}" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>အားလုံး</option>
                    <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>စောင့်ဆိုင်းဆဲ
                    </option>
                    <option value="approved" {{ ($status ?? '') === 'approved' ? 'selected' : '' }}>လက်ခံပြီး</option>
                    <option value="rejected" {{ ($status ?? '') === 'rejected' ? 'selected' : '' }}>ငြင်းပယ်</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Data Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>စဥ်</th>
                <th>ဝန်ထမ်းအမည်</th>
                <th>ခွင့်အမျိုးအစား</th>
                <th>ခွင့်စတင်ယူသည့်နေ့</th>
                <th>ခွင့်ပီးဆုံးသည့်နေ့</th>
                <th>ခွင့်ယူသည့် ကာလ</th>
                <th>အခြေအနေ</th>
                <th>တင်သွင်းရက်</th>
                <th>လုပ်ဆောင်ချက်</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaveRequests as $req)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $req->user->name ?? '---' }}</td>
                <td>
                        @if($req->req_type === 'shaung')
                        ရှောင်တခင်
                        @elseif($req->req_type === 'no-pay')
                        လစာမဲ့ခွင့်
                        @elseif($req->req_type === 'maternity')
                        မီးဖွားခွင့်
                        @elseif($req->req_type === 'medical-certificate')
                        ဆေးလက်မှတ်ခွင့်
                        @elseif($req->req_type === 'long-service')
                        လုပ်သက်ခွင့်
                        @elseif($req->req_type === 'contagious')
                        ကူးစက်ရောဂါ
                        @elseif($req->req_type === 'disability')
                        အထူးမသန်စွမ်းခွင့်
                        @elseif($req->req_type === 'volunteer-sick')
                        သဘောသားမနာကျန်းခွင့်
                        @elseif($req->req_type === 'study')
                        ပညာလေ့လာဆည်းပူခွင့်
                        @else
                        အခြား
                        @endif
                    </td>
                <td>{{ $req->from_date ?? '-' }}</td>
                <td>{{ $req->to_date ?? '-' }}</td>
                <td>{{ $req->duration ?? '-' }}</td>
                <td>
                    @if ($req->status == 'approved')
                    <span class="badge bg-success">လက်ခံပြီး</span>
                    @elseif($req->status == 'reject')
                    <span class="badge bg-danger">ငြင်းပယ်</span>
                    @else
                    <span class="badge bg-warning text-dark">စောင့်ဆိုင်းဆဲ</span>
                    @endif
                </td>
                <td>{{ $req->created_at->format('Y-m-d') }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" class="dropdown-item text-primary btn-view"
                                    data-id="{{ $req->id }}">
                                    👁 ကြည့်မည်
                                </a>
                            </li>
                            @if ($req->status == \App\Models\LeaveRequest::STATUS_PENDING)
                            <button type="button" class="dropdown-item text-success btn-approve"
                                data-id="{{ $req->id }}">
                                ✔ လက်ခံမည်
                            </button>

                            <form action="{{ route('admin.leave.reject', $req->id) }}" method="POST">
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

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ခွင့်လျှောက်လွှာ အသေးစိတ်</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>ဝန်ထမ်း:</strong> <span id="detail-name"></span></p>
                <p><strong>ခွင့်အမျိုးအစား:</strong> <span id="detail-leave-type"></span></p>
                <p><strong>စတင်နေ့:</strong> <span id="detail-from-date"></span></p>
                <p><strong>နောက်ဆုံးခွင်ံရက်:</strong> <span id="detail-to-date"></span></p>
                <p><strong>ကြာချိန်:</strong> <span id="detail-duration"></span></p>
                <p><strong>ဖော်ပြချက်:</strong> <span id="detail-description"></span></p>
                <img id="detail-image" src="" class=" mb-2" alt="" width="100%" height="400px">
                <a id="download-image" href="#" download="image.jpg" class="btn btn-primary" style="display:none;">
                    📥 ပုံကို ဒေါင်းလုပ်လုပ်မည်
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('js/admin/leaveRequest.js') }}"></script>
@endsection
