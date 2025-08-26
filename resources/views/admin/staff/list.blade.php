@extends('admin.layout')

@section('content')
    <div class="view-toggle mb-3 d-flex gap-2">
        <button id="btnShowTable" class="active">Show List (Table)</button>
        <button id="btnShowGrid">Show Grid (Cards)</button>
    </div>

    <div class="staff-list-container">
        <div class="card shadow-sm staff-list">
            <table id="staffTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>စဥ်</th>
                        <th>အမည်</th>
                        <th>အီးမေးလ်</th>
                        <th>ဝန်ထမ်းအမှတ်</th>
                        <th>ဖုန်း</th>
                        <th>ရာထူး</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staffs as $index => $staff)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $staff->name }}</td>
                            <td>{{ $staff->email }}</td>
                            <td>{{ $staff->eid ?? 'N/A' }}</td>
                            <td>{{ $staff->phno ?? 'N/A' }}</td>
                            <td>{{ $staff->getPositionName() }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a href="javascript:void(0);" class="dropdown-item edit-btn"
                                                data-id="{{ $staff->id }}">Edit</a>
                                        </li>
                                        <li>
                                            <form action="{{ route('staff.destroy', $staff->id) }}" method="POST"
                                                onsubmit="return confirm('ဖျက်မှာ သေချာလား?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit">Delete</button>
                                            </form>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class="dropdown-item view-btn"
                                                data-id="{{ $staff->id }}">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mb-3">
                <button class="btn btn-primary staff-add" id="btnCreateStaff">ဝန်ထမ်းအသစ်ထည့်မည်</button>
            </div>
        </div>
        <div class="staff-grid-container row row-cols-1 row-cols-md-3 g-3" style="display:none;">
            @foreach ($staffs as $staff)
                <div class="card  staff-card shadow-sm h-100 text-center p-3 position-relative staff-card">
                    <div class="dropdown position-absolute top-0 end-0 m-2">
                        <button class="btn btn-light btn-sm p-1" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false" style="min-width: 30px;">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item edit-btn"
                                    data-id="{{ $staff->id }}">Edit</a>
                            </li>
                            <li>
                                <form action="{{ route('staff.destroy', $staff->id) }}" method="POST"
                                    onsubmit="return confirm('ဖျက်မှာ သေချာလား?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item text-danger" type="submit">Delete</button>
                                </form>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item view-btn" data-id="{{ $staff->id }}">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </li>

                        </ul>
                    </div>

                    {{-- Staff image --}}
                    @if ($staff->image)
                        <img src="{{ asset('storage/' . $staff->image) }}" alt="{{ $staff->name }}"
                            class="card-img-top mx-auto" style="width: 120px; height: 120px; border-radius: 50%;">
                    @else
                        <img src="https://via.placeholder.com/120" alt="No Image" class="card-img-top mx-auto"
                            style="max-width: 120px; border-radius: 50%;">
                    @endif

                    {{-- Staff name --}}
                    <div class="card-body s">
                        <p class="card-title">{{ $staff->name }}</p>
                        <p class="card-title">{{ $staff->getPositionName() }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Edit Staff Modal -->
    <div class="modal fade" id="editStaffModal" tabindex="-1" aria-labelledby="editStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editStaffForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStaffModalLabel">ဝန်ထမ်းအချက်အလက် ပြင်ဆင်ခြင်း</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ပိတ်ရန်"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="staff_id" name="staff_id">

                        <!-- ဓာတ်ပုံ -->
                        <div class="mb-3">
                            <label for="staff_image" class="form-label">ဓာတ်ပုံ</label>
                            <input type="file" class="form-control" id="staff_image" name="image">
                            <div class="invalid-feedback" id="error-image"></div>
                            <img id="preview_image" src="" alt="ယခင်ဓာတ်ပုံ"
                                style="max-width:100px; margin-top: 10px;">
                        </div>

                        <!-- အဆင့် (Position ID) -->
                        <div class="mb-3">
                            <label for="edit_staff_position_id" class="form-label">ရာထူး</label>
                            <select class="form-select" id="edit_staff_position_id" name="position_id">
                                <option value="" disabled selected>ရာထူးရွေးပါ</option>
                            </select>
                            <div class="invalid-feedback" id="edit-error-position_id"></div>
                        </div>

                        <!-- ဝန်ထမ်းနံပါတ် (EID) -->
                        <div class="mb-3">
                            <label for="staff_eid" class="form-label">ဝန်ထမ်းနံပါတ်</label>
                            <input type="text" class="form-control" id="staff_eid" name="eid">
                            <div class="invalid-feedback" id="edit-error-eid"></div>
                        </div>

                        <!-- အမည် -->
                        <div class="mb-3">
                            <label for="staff_name" class="form-label">အမည်</label>
                            <input type="text" class="form-control" id="staff_name" name="name">
                            <div class="invalid-feedback" id="edit-error-name"></div>
                        </div>

                        <!-- အီးမေးလ် -->
                        <div class="mb-3">
                            <label for="staff_email" class="form-label">အီးမေးလ်</label>
                            <input type="" class="form-control" id="staff_email" name="email">
                            <div class="invalid-feedback" id="edit-error-email"></div>
                        </div>

                        <!-- မွေးသက္ကရာဇ် -->
                        <div class="mb-3">
                            <label for="staff_dob" class="form-label">မွေးသက္ကရာဇ်</label>
                            <input type="date" class="form-control" id="staff_dob" name="dob">
                            <div class="invalid-feedback" id="edit-error-dob"></div>
                        </div>

                        <!-- လက်ရှိလိပ်စာ -->
                        <div class="mb-3">
                            <label for="staff_currentaddress" class="form-label">လက်ရှိလိပ်စာ</label>
                            <input type="text" class="form-control" id="staff_currentaddress" name="currentaddress">
                            <div class="invalid-feedback" id="edit-error-currentaddress"></div>
                        </div>

                        <!-- ဖုန်းနံပါတ် -->
                        <div class="mb-3">
                            <label for="staff_phno" class="form-label">ဖုန်းနံပါတ်</label>
                            <input type="text" class="form-control" id="staff_phno" name="phno">
                            <div class="invalid-feedback" id="edit-error-phno"></div>
                        </div>

                        <!-- ဌာန -->
                        <div class="mb-3">
                            <label for="staff_department" class="form-label">ဌာန</label>
                            <input type="text" class="form-control" id="staff_department" name="department">
                            <div class="invalid-feedback" id="edit-error-department"></div>
                        </div>

                        <!-- အိမ်ထောင်ဖက် အခြေအနေ -->
                        <div class="mb-3">
                            <label for="staff_married_status" class="form-label">အိမ်ထောင်ဖက် အခြေအနေ</label>
                            <select class="form-select" id="staff_married_status" name="married_status">
                                <option value="" disabled selected>ရွေးပါ</option>
                                <option value="0">အိမ်ထောင်မရှိပါ</option>
                                <option value="1">အိမ်ထောင်ရှိပါသည်</option>
                            </select>
                            <div class="invalid-feedback" id="edit-error-married_status"></div>
                        </div>

                        <!-- ကျား/မ/အခြား -->
                        <div class="mb-3">
                            <label for="staff_gender" class="form-label">ကျား/မ/အခြား</label>
                            <select class="form-select" id="staff_gender" name="gender">
                                <option value="0">မ</option>
                                <option value="1">ကျား</option>
                                <option value="2">အခြား</option>
                            </select>
                            <div class="invalid-feedback" id="edit-error-gender"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">သိမ်းဆည်းမည်</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်မည်</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Create Staff Modal -->
    <div class="modal fade" id="createStaffModal" tabindex="-1" aria-labelledby="createStaffModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="createStaffForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createStaffModalLabel">ဝန်ထမ်းအသစ် ဖန်တီးခြင်း</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ပိတ်ရန်"></button>
                    </div>
                    <div class="modal-body">

                        <!-- ဓာတ်ပုံ -->
                        <div class="mb-3">
                            <label for="staff_image" class="form-label">ဓာတ်ပုံ</label>
                            <input type="file" class="form-control" id="staff_image" name="image">
                            <div class="invalid-feedback" id="error-image"></div>
                            <img id="preview_image" src="" alt="ဓာတ်ပုံကြိုတင်ကြည့်ရှုခြင်း"
                                style="max-width:100px; margin-top: 10px; display:none;">
                        </div>

                        <div class="mb-3">
                            <label for="staff_password" class="form-label">စကားဝှက်</label>
                            <input type="password" class="form-control" id="staff_password" name="password">
                            <div class="invalid-feedback" id="error-password"></div>
                        </div>

                        <!-- အဆင့် (Position ID) -->
                        <div class="mb-3">
                            <select class="form-select" id="create_staff_position_id" name="position_id">
                                <option value="" disabled selected>ရာထူးရွေးပါ</option>
                            </select>
                            <div class="invalid-feedback" id="error-position_id"></div>
                        </div>

                        <!-- ဝန်ထမ်းနံပါတ် (EID) -->
                        <div class="mb-3">
                            <label for="staff_eid" class="form-label">ဝန်ထမ်းနံပါတ်</label>
                            <input type="text" class="form-control" id="staff_eid" name="eid">
                            <div class="invalid-feedback" id="error-eid"></div>
                        </div>

                        <!-- အမည် -->
                        <div class="mb-3">
                            <label for="staff_name" class="form-label">အမည်</label>
                            <input type="text" class="form-control" id="staff_name" name="name">
                            <div class="invalid-feedback" id="error-name"></div>
                        </div>

                        <!-- အီးမေးလ် -->
                        <div class="mb-3">
                            <label for="staff_email" class="form-label">အီးမေးလ်</label>
                            <input type="" class="form-control" id="staff_email" name="email">
                            <div class="invalid-feedback" id="error-email"></div>
                        </div>

                        <!-- မွေးသက္ကရာဇ် -->
                        <div class="mb-3">
                            <label for="staff_dob" class="form-label">မွေးသက္ကရာဇ်</label>
                            <input type="date" class="form-control" id="staff_dob" name="dob">
                            <div class="invalid-feedback" id="error-dob"></div>
                        </div>

                        <!-- လက်ရှိလိပ်စာ -->
                        <div class="mb-3">
                            <label for="staff_currentaddress" class="form-label">လက်ရှိလိပ်စာ</label>
                            <input type="text" class="form-control" id="staff_currentaddress" name="currentaddress">
                            <div class="invalid-feedback" id="error-currentaddress"></div>
                        </div>

                        <!-- ဖုန်းနံပါတ် -->
                        <div class="mb-3">
                            <label for="staff_phno" class="form-label">ဖုန်းနံပါတ်</label>
                            <input type="text" class="form-control" id="staff_phno" name="phno">
                            <div class="invalid-feedback" id="error-phno"></div>
                        </div>

                        <!-- ဌာန -->
                        <div class="mb-3">
                            <label for="staff_department" class="form-label">ဌာန</label>
                            <input type="text" class="form-control" id="staff_department" name="department">
                            <div class="invalid-feedback" id="error-department"></div>
                        </div>

                        <!-- မင်္ဂလာပါသည်/မဟုတ်ပါသည် -->
                        <div class="mb-3">
                            <label for="staff_married_status" class="form-label">အိမ်ထောင်ဖက် အခြေအနေ</label>
                            <select class="form-select" id="staff_married_status" name="married_status">
                                <option value="" disabled selected>ရွေးပါ</option>
                                <option value="0">အိမ်ထောင်မရှိပါ</option>
                                <option value="1">အိမ်ထောင်ရှိပါသည်</option>
                            </select>
                            <div class="invalid-feedback" id="error-married_status"></div>
                        </div>

                        <!-- ကျား/မ/အခြား -->
                        <div class="mb-3">
                            <label for="staff_gender" class="form-label">ကျား/မ/အခြား</label>
                            <select class="form-select" id="staff_gender" name="gender">
                                <option value="0">မ</option>
                                <option value="1">ကျား</option>
                                <option value="2">အခြား</option>
                            </select>
                            <div class="invalid-feedback" id="error-gender"></div>
                        </div>

                    </div>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">သိမ်းဆည်းမည်</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်မည်</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Show Staff Detail Modal -->
    <!-- Show Staff Detail Modal -->
    <div class="modal fade" id="showStaffModal" tabindex="-1" aria-labelledby="showStaffModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ဝန်ထမ်းအချက်အလက် ကြည့်ရန်</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ပိတ်ရန်"></button>
                </div>
                <div class="modal-body p-0">

                    <!-- Full width image -->
                    <img id="staff_image_show" src="https://via.placeholder.com/600x300" alt="Staff Image"
                        style="width: 100%; height: 300px; object-fit: cover;">

                    <!-- List of details with lines -->
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>အမည်:</strong> <span id="staff_name_show"></span></li>
                        <li class="list-group-item"><strong>အီးမေးလ်:</strong> <span id="staff_email_show"></span></li>
                        <li class="list-group-item"><strong>ဝန်ထမ်းနံပါတ် (EID):</strong> <span
                                id="staff_eid_show"></span></li>
                        <li class="list-group-item"><strong>မွေးသက္ကရာဇ်:</strong> <span id="staff_dob_show"></span></li>
                        <li class="list-group-item"><strong>လက်ရှိလိပ်စာ:</strong> <span
                                id="staff_currentaddress_show"></span></li>
                        <li class="list-group-item"><strong>ဖုန်းနံပါတ်:</strong> <span id="staff_phno_show"></span></li>
                        <li class="list-group-item"><strong>ဌာန:</strong> <span id="staff_department_show"></span></li>
                        <li class="list-group-item"><strong>ရာထူး:</strong> <span id="staff_position_show"></span></li>
                        <li class="list-group-item"><strong>အိမ်ထောင်ဖက် အခြေအနေ:</strong> <span
                                id="staff_married_status_show"></span></li>
                        <li class="list-group-item"><strong>လိင် :</strong> <span id="staff_gender_show"></span></li>
                    </ul>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်မည်</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var staffStoreUrl = "{{ route('staff.store') }}";
    </script>

    <script src="{{ asset('js/staff.js') }}"></script>
@endsection
