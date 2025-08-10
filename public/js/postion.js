$(document).ready(function() {

    $('#positionTable').DataTable({
        lengthMenu: [
            [5, 10, 15, 20, 25, 30, 35],
            [5, 10, 15, 20, 25, 30, 35]
        ],
        pageLength: 5,
        pagingType: 'full_numbers',
        language: {
            search: "ရှာဖွေခြင်း:",
            lengthMenu: "စာရင်း _MENU_ ခုကိုပြပါ",
            info: "စာရင်း _START_ မှ _END_ အထိ ပြပါ",
            paginate: {
                first: "ပထမ",
                last: "နောက်ဆုံး",
                previous: "ရှေ့သို့",
                next: "နောက်သို့"
            },
            zeroRecords: "ရှာနေသော အရာမှာ Databaseတွင်မရှိပါ"
        }
    });

    $('#createPositionBtn').click(function() {
        $('#createPositionModal').modal('show');
    });

    $('#createPositionForm').submit(function(e) {
        e.preventDefault();

        // Clear previous errors if any
        $('#createPositionForm .is-invalid').removeClass('is-invalid');
        $('#createPositionForm .invalid-feedback').remove();

        $.ajax({
            url: window.positionsStoreUrl,
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createPositionModal').modal('hide');
                    $('#createPositionForm')[0].reset();
                    location.reload(); // simple approach
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Laravel validation error
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        var input = $('#createPositionForm').find('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        if (input.next('.invalid-feedback').length === 0) {
                            input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                        }
                    });
                } else {
                    alert('အမှားတစ်ခု ဖြစ်ပွားခဲ့ပါသည်။ နောက်မှ ထပ်မံ ကြိုးစားပါ။');
                }
            }
        });
    });

    $('.delete-btn').click(function() {
        var id = $(this).data('id');
        var url = '/positions/' + id;
        $('#deletePositionForm').attr('action', url);
        // Show delete modal
        $('#deleteConfirmModal').modal('show');
    });

    $('.edit-btn').click(function() {
        var id = $(this).data('id');

        // Fetch existing data via AJAX (optional, if data not in DOM)
        $.ajax({
            url: '/positions/' + id + '/edit', // You need to create this route & method
            method: 'GET',
            success: function(data) {
                $('#edit_position_id').val(data.position_id);
                $('#edit_position_title').val(data.title);
                $('#edit_position_salary').val(data.salary);

                $('#editPositionModal').modal('show');
            },
            error: function() {
                alert('ဒေတာယူရန် မအောင်မြင်ပါ။');
            }
        });
    });


    $('#editPositionForm').submit(function(e) {
        e.preventDefault();

        var id = $('#edit_position_id').val();
        var formData = $(this).serialize();

        // Replace ':id' with real id in URL
        var url = window.positionsUpdateUrl.replace(':id', id);
        console.log(url);

        $.ajax({
            url: url,
            method: 'POST', // Laravel expects POST + _method=PUT
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#editPositionModal').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Handle validation errors here
                } else {
                    alert('အမှားတစ်ခုဖြစ်ပွားပါသည်။');
                }
            }
        });
    });


});
