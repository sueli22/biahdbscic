@extends('admin.layout')

@section('content')
    <button class="btn btn-primary mb-3" id="btnAdd">ခွင့်အမျိုးအစား အသစ်ထည့်မည်</button>

    <table class="table table-bordered" id="leaveTypesTable" style="width:100%">
        <thead>
            <tr>
                <th>ခေါင်းစဉ်</th>
                <th>ဖော်ပြချက်</th>
                <th>အများဆုံးရက်ပမာဏ</th>
                <th>အရေးယူမှုများ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaveTypes as $leaveType)
                <tr>
                    <td>{{ $leaveType->title }}</td>
                    <td>{{ $leaveType->description }}</td>
                    <td>{{ $leaveType->max_days }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                id="actionMenu{{ $leaveType->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                &#x22EE; <!-- vertical ellipsis unicode character -->
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="actionMenu{{ $leaveType->id }}">
                                <li><a class="dropdown-item btn-edit" href="#"
                                        data-id="{{ $leaveType->id }}">ပြင်ဆင်ရန်</a></li>
                                <li><a class="dropdown-item btn-delete" href="#"
                                        data-id="{{ $leaveType->id }}">ဖျက်ရန်</a></li>
                            </ul>
                        </div>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>


    <!-- Create Modal -->
    <div class="modal fade" id="createLeaveTypeModal" tabindex="-1" role="dialog"
        aria-labelledby="createLeaveTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="createLeaveTypeForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createLeaveTypeModalLabel">ခွင့်အမျိုးအစား အသစ်ထည့်ရန်</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="ပိတ်ရန်">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="create_title">ခေါင်းစဉ်</label>
                            <input type="text" class="form-control" id="create_title" name="title">
                        </div>
                        <div class="form-group">
                            <label for="create_description">ဖော်ပြချက်</label>
                            <textarea class="form-control" id="create_description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="create_max_days">အများဆုံးရက်ပမာဏ</label>
                            <input type="number" class="form-control" id="create_max_days" name="max_days" min="1">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်ရန်</button>
                        <button type="submit" class="btn btn-primary">သိမ်းမည်</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editLeaveTypeModal" tabindex="-1" role="dialog" aria-labelledby="editLeaveTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editLeaveTypeForm">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLeaveTypeModalLabel">ခွင့်အမျိုးအစား ပြင်ဆင်ရန်</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="ပိတ်ရန်">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_title">ခေါင်းစဉ်</label>
                            <input type="text" class="form-control" id="edit_title" name="title">
                        </div>
                        <div class="form-group">
                            <label for="edit_description">ဖော်ပြချက်</label>
                            <textarea class="form-control" id="edit_description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_max_days">အများဆုံးရက်ပမာဏ</label>
                            <input type="number" class="form-control" id="edit_max_days" name="max_days" min="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်ရန်</button>
                        <button type="submit" class="btn btn-primary">သိမ်းမည်</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var leaveTypesIndexUrl = "{{ route('leave_types.index') }}";
        var leaveTypesStoreUrl = "{{ route('leave_types.store') }}";
        var leaveTypesShowUrl = "{{ route('leave_types.show', ':id') }}";
        var leaveTypesUpdateUrl = "{{ route('leave_types.update', ':id') }}";
        var leaveTypesDestroyUrl = "{{ route('leave_types.destroy', ':id') }}";
    </script>
    <script src="{{ asset('js/leave.js') }}"></script>
@endsection
