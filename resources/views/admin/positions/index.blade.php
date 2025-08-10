@extends('admin.layout')

@section('content')
<div class="container mt-3">
    <!-- Create Button -->

    <div class="card">
        <div class="card-body">
            <table id="positionTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ရာထူးခေါင်းစဉ်</th>
                        <th>လစာ</th>
                        <th>လုပ်ဆောင်ချက်</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($positions as $position)
                    <tr data-id="{{ $position->position_id }}">
                        <td>{{ $position->position_id }}</td>
                        <td>{{ $position->title }}</td>
                        <td>{{ number_format($position->salary, 2) }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm" type="button" id="dropdownMenuButton{{ $position->position_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $position->position_id }}">
                                    <li>
<a href="javascript:void(0);" class="dropdown-item edit-btn" data-id="{{ $position->position_id }}">ပြင်ဆင်ရန်</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item text-danger delete-btn" data-id="{{ $position->position_id }}">ဖျက်ရန်</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
        <button class="btn btn-primary mb-3 mt-5" id="createPositionBtn">ရာထူးအသစ်ထည့်မည်</button>

</div>

<!-- Create Position Modal -->
<div class="modal fade" id="createPositionModal" tabindex="-1" aria-labelledby="createPositionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createPositionForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createPositionLabel">ရာထူးအသစ်ထည့်မည်</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ပိတ်ရန်"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="create_title" class="form-label">ရာထူးခေါင်းစဉ်</label>
            <input type="text" class="form-control" id="create_title" name="title" required>
          </div>
          <div class="mb-3">
            <label for="create_salary" class="form-label">လစာ</label>
            <input type="number" step="0.01" class="form-control" id="create_salary" name="salary" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">သိမ်းမည်</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်မည်</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Position Modal -->
<div class="modal fade" id="editPositionModal" tabindex="-1" aria-labelledby="editPositionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editPositionForm">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPositionLabel">ရာထူး ပြင်ဆင်ခြင်း</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ပိတ်ရန်"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_position_id" name="position_id">

          <div class="mb-3">
            <label for="edit_position_title" class="form-label">ရာထူးခေါင်းစဉ်</label>
            <input type="text" class="form-control" id="edit_position_title" name="title" required>
          </div>

          <div class="mb-3">
            <label for="edit_position_salary" class="form-label">လစာ</label>
            <input type="number" step="0.01" class="form-control" id="edit_position_salary" name="salary" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">သိမ်းမည်</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်မည်</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- Delete Confirm Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="deletePositionForm" method="POST">
      @csrf
      @method('DELETE')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteConfirmLabel">ဖျက်ရန် အတည်ပြုချက်</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ပိတ်ရန်"></button>
        </div>
        <div class="modal-body">
          သင်သည် ရာထူးကို ဖျက်လိုက်မည်မှာ သေချာပါသလား?
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">ဖျက်မည်</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">မဖျက်တော့ပါ</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
    window.positionsStoreUrl = "{{ route('positions.store') }}";
    window.positionsUpdateUrl = "{{ route('positions.update', ':id') }}";
</script>
@endsection
@section('scripts')
    <script src="{{ asset('js/postion.js') }}"></script>
@endsection
