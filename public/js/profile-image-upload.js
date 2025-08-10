$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#staffTable').DataTable({
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

    $('.edit-btn').on('click', function() {
        let staffId = $(this).data('id');

        // AJAX GET request to fetch staff data
        $.ajax({
            url: '/staff/' + staffId + '/edit', // Route to get staff data (you create this)
            type: 'GET',
            success: function(data) {
                // Fill the form inputs with returned data
                $('#staff_id').val(data.id);
                $('#staff_eid').val(data.eid);
                $('#staff_name').val(data.name);
                $('#staff_email').val(data.email);
                $('#staff_position_id').val(data.position_id);
                $('#staff_dob').val(data.dob);
                $('#staff_currentaddress').val(data.currentaddress);
                $('#staff_phno').val(data.phno);
                $('#staff_department').val(data.department);
                $('#staff_gender').val(data.gender);
                $('#staff_married_status').prop('checked', data.married_status == 1);
                if (data.image) {
                    $('#preview_image').attr('src', '/storage/' + data.image).show();
                } else {
                    $('#preview_image').hide();
                }

                $('#editStaffModal').modal('show');
            },
            error: function() {
                alert('Failed to fetch staff data.');
            }
        });
    });

    // Handle form submission for updating staff
    $('#editStaffForm').on('submit', function(e) {
        e.preventDefault();

        let staffId = $('#staff_id').val();
        let formData = $(this).serialize();

        $.ajax({
            url: '/staff/' + staffId,
            type: 'PUT',
            data: formData,
            success: function(response) {
                alert('Staff updated successfully!');
                $('#editStaffModal').modal('hide');

                // Optionally, reload the page or update the table row via JS
                location.reload();
            },
            error: function() {
                alert('Failed to update staff.');
            }
        });
    });

    $.ajax({
        url: '/api/positions', // the API route defined above
        method: 'GET',
        success: function(positions) {
            var $select = $('#staff_position_id');
            $select.empty().append('<option value="" disabled selected>ရာထူးရွေးပါ</option>');
            $.each(positions, function(i, pos) {
                $select.append('<option value="' + pos.position_id + '">' + pos.title + '</option>');
            });
            $select.val(currentStaffPositionId);
        },
        error: function() {
            alert('Positions load failed!');
        }
    });
});
