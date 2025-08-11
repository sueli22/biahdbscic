@extends('employee.layout')

@section('content')
<div class="container">
<button class="btn btn-primary mb-3" id="btnCasualLeave">
  <i class="bi bi-calendar-check-fill"></i> ပုံမှန်ခွင့်
</button>

<button class="btn btn-secondary mb-3" id="btnSpecialLeave">
  <i class="bi bi-star-fill"></i> အထူးခွင့်
</button>


    <!-- Casual Leave Modal -->
    <div class="modal fade" id="casualLeaveModal" tabindex="-1" aria-labelledby="casualLeaveModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="casualLeaveForm" enctype="multipart/form-data">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="casualLeaveModalLabel">ပုံမှန်ခွင့်လျှောက်လွှာ</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ပိတ်ရန်"></button>
            </div>
            <div class="modal-body">

              <input type="hidden" name="leave_type" value="casual">

              <div class="mb-3">
                <label for="from_date" class="form-label">အစပြုရက်</label>
                <input type="date" name="from_date" id="from_date" class="form-control" required>
                <div class="invalid-feedback" id="error-from_date"></div>
              </div>

              <div class="mb-3">
                <label for="to_date" class="form-label">အဆုံးရက်</label>
                <input type="date" name="to_date" id="to_date" class="form-control" required>
                <div class="invalid-feedback" id="error-to_date"></div>
              </div>

              <div class="mb-3">
                <label for="description_casual" class="form-label">ဖော်ပြချက်</label>
                <textarea name="description" id="description_casual" class="form-control"></textarea>
                <div class="invalid-feedback" id="error-description"></div>
              </div>

              <div class="mb-3">
                <label for="img_casual" class="form-label">စာရွက်စာတမ်းတင်ရန်</label>
                <input type="file" name="img" id="img_casual" class="form-control" accept="image/*">
                <div class="invalid-feedback" id="error-img"></div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်ရန်</button>
              <button type="submit" class="btn btn-primary">တင်သွင်းရန်</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Special Leave Modal -->
    <div class="modal fade" id="specialLeaveModal" tabindex="-1" aria-labelledby="specialLeaveModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="specialLeaveForm" enctype="multipart/form-data">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="specialLeaveModalLabel">အထူးခွင့်လျှောက်လွှာ</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ပိတ်ရန်"></button>
            </div>
            <div class="modal-body">

              <input type="hidden" name="leave_type" value="special">

              <div class="mb-3">
                <label for="leave_type_id" class="form-label">ခွင့်အမျိုးအစား ရွေးချယ်ရန်</label>
                <select name="leave_type_id" id="leave_type_id" class="form-select" required>
                  <option value="">-- ရွေးချယ်ပါ --</option>
                  @foreach($leaveTypes as $leaveType)
                    <option value="{{ $leaveType->id }}">{{ $leaveType->title }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback" id="error-leave_type_id"></div>
              </div>

              <div class="mb-3">
                <label for="description_special" class="form-label">ဖော်ပြချက်</label>
                <textarea name="description" id="description_special" class="form-control"></textarea>
                <div class="invalid-feedback" id="error-description"></div>
              </div>

              <div class="mb-3">
                <label for="img_special" class="form-label">စာရွက်စာတမ်းတင်ရန်</label>
                <input type="file" name="img" id="img_special" class="form-control" accept="image/*">
                <div class="invalid-feedback" id="error-img"></div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်ရန်</button>
              <button type="submit" class="btn btn-primary">တင်သွင်းရန်</button>
            </div>
          </div>
        </form>
      </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Show modals
    $('#btnCasualLeave').click(function() {
        $('#casualLeaveModal').modal('show');
    });

    $('#btnSpecialLeave').click(function() {
        $('#specialLeaveModal').modal('show');
    });

    // Reset validation errors
    function resetErrors(formId) {
        $(`${formId} .invalid-feedback`).text('');
        $(`${formId} .form-control, ${formId} .form-select`).removeClass('is-invalid');
    }

    // AJAX form submission helper
    function ajaxFormSubmit(formId, modalId) {
        $(formId).submit(function(e) {
            e.preventDefault();

            resetErrors(formId);

            var form = $(this)[0];
            var formData = new FormData(form);

            $.ajax({
                url: "",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $(modalId).modal('hide');
                    alert('ခွင့်လျှောက်လွှာအောင်မြင်စွာတင်သွင်းပြီးပါပြီ။');
                    location.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 422) { // Validation error
                        let errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            $(`${formId} [name="${key}"]`).addClass('is-invalid');
                            $(`${formId} #error-${key}`).text(errors[key][0]);
                        }
                    } else {
                        alert('အမှားတစ်ခုဖြစ်ပွားပါသည်။');
                    }
                }
            });
        });
    }

    ajaxFormSubmit('#casualLeaveForm', '#casualLeaveModal');
    ajaxFormSubmit('#specialLeaveForm', '#specialLeaveModal');
});
</script>
@endsection
