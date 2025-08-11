$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#leaveTypesTable').DataTable({
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
                previous: "ရှေ့သို့",
                next: "နောက်သို့"
            },
            zeroRecords: "ရှာနေသော အရာမှာ Databaseတွင်မရှိပါ"
        }
    });



    $('#btnAdd').click(function() {
        $('#createLeaveTypeForm')[0].reset();
        $('#createLeaveTypeModal').modal('show');
    });

    $('#createLeaveTypeForm').submit(function(e) {
        e.preventDefault();

        // Clear previous errors
        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');

        var formData = {
            title: $('#create_title').val(),
            description: $('#create_description').val(),
            max_days: parseInt($('#create_max_days').val(), 10),
        };

        $.ajax({
            url: leaveTypesStoreUrl,
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#createLeaveTypeModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422) { // Laravel validation error status code
                    var errors = xhr.responseJSON.errors;

                    // Loop through each error field
                    $.each(errors, function(field, messages) {
                        var input = $('#create_' + field);
                        input.addClass('is-invalid'); // bootstrap invalid class

                        // Show the first error message
                        if (input.next('.invalid-feedback').length === 0) {
                            input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                        }
                    });
                } else {
                    alert('Error: ' + (xhr.responseJSON ? xhr.responseJSON.message || xhr.responseText : xhr.responseText));
                }
            }
        });
    });




    // When clicking Edit button: fill and show modal
    $('#leaveTypesTable').on('click', '.btn-edit', function(e) {
        e.preventDefault();
        var id = $(this).data('id');

        var row = $(this).closest('tr');
        var title = row.find('td').eq(0).text().trim();
        var description = row.find('td').eq(1).text().trim();
        var max_days = row.find('td').eq(2).text().trim();

        $('#edit_id').val(id);
        $('#edit_title').val(title);
        $('#edit_description').val(description);
        $('#edit_max_days').val(max_days);

        // Clear previous validation errors
        $('#editLeaveTypeForm .invalid-feedback').remove();
        $('#editLeaveTypeForm .is-invalid').removeClass('is-invalid');

        $('#editLeaveTypeModal').modal('show');
    });


    // Submit edit form with validation error handling
    $('#editLeaveTypeForm').submit(function(e) {
        e.preventDefault();

        // Clear previous errors
        $('#editLeaveTypeForm .invalid-feedback').remove();
        $('#editLeaveTypeForm .is-invalid').removeClass('is-invalid');

        var id = $('#edit_id').val();
        var formData = {
            title: $('#edit_title').val(),
            description: $('#edit_description').val(),
            max_days: parseInt($('#edit_max_days').val(), 10),
        };

        $.ajax({
            url: leaveTypesUpdateUrl.replace(':id', id),
            method: 'PUT',
            data: formData,
            success: function(response) {
                $('#editLeaveTypeModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422) { // Validation error
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        var input = $('#edit_' + field);
                        input.addClass('is-invalid');
                        if (input.next('.invalid-feedback').length === 0) {
                            input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                        }
                    });
                } else {
                    alert('Error: ' + (xhr.responseJSON ? xhr.responseJSON.message || xhr.responseText : xhr.responseText));
                }
            }
        });
    });
    $('#leaveTypesTable').on('click', '.btn-delete', function(e) {
        e.preventDefault();

        var id = $(this).data('id');

        Swal.fire({
            title: 'ဖျက်မှာ သေချာပြီလား?',
            text: "ဒီခွင့်အမျိုးအစားကို မရနိုင်တော့ပါ!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ဖျက်မည်',
            cancelButtonText: 'မဖျက်ပါ'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: leaveTypesDestroyUrl.replace(':id', id),
                    method: 'DELETE',
                    success: function(response) {
                        Swal.fire(
                            'ဖျက်ပြီးပါပြီ!',
                            'ခွင့်အမျိုးအစားကို ဖျက်ပြီးပါပြီ။',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'အမှားတက်နေပါသည်',
                            'ဖျက်မှု မအောင်မြင်ပါ။',
                            'error'
                        );
                    }
                });
            }
        });
    });

});
