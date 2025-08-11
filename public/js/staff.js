$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.staff-list').show();
    $('.staff-grid-container').hide();
     $('#btnShowTable').on('click', function() {
            $('.staff-list').show();
            $('.staff-grid-container').hide();
            $(this).addClass('active');
            $('#btnShowGrid').removeClass('active');
        });

        $('#btnShowGrid').on('click', function() {
            $('.staff-list').hide();
            $('.staff-grid-container').show();
            $(this).addClass('active');
            $('#btnShowTable').removeClass('active');
        });

    // Initialize DataTable
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

    // Load positions into select box
    function loadPositions(selectId, currentPositionId = null) {
        $.ajax({
            url: '/api/positions',
            method: 'GET',
            success: function(positions) {
            var $select = $('#' + selectId);
            $select.empty().append('<option value="" disabled selected>ရာထူးရွေးပါ</option>');
            $.each(positions, function(i, pos) {
                $select.append('<option value="' + pos.position_id + '">' + pos.title + '</option>');
            });
            if (currentPositionId !== null) {
                $select.val(currentPositionId); // Set the value AFTER options are loaded
            }
        },
            error: function() {
                alert('Positions load failed!');
            }
        });
    }

    // Create Staff button click to open modal and load positions
    $('#btnCreateStaff').click(function() {
        $('#createStaffForm')[0].reset();
        $('#preview_image').hide();
        loadPositions('create_staff_position_id');
        $('#createStaffModal').modal('show');
    });

    $('#createStaffForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        $('#createFormErrors').hide().html('');

        $.ajax({
            url: staffStoreUrl,
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#createStaffModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                $('.invalid-feedback').html('');
                $('.form-control, .form-select, .form-check-input').removeClass('is-invalid');

                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#error-' + field).html(messages[0]);
                        $('[name="' + field + '"]').addClass('is-invalid');
                    });
                } else {
                    alert('ဝန်ထမ်း ဖန်တီးခြင်း မအောင်မြင်ပါ');
                }
            }
        });

    });

    // Open Edit Modal and populate form
    $('#staffTable, .staff-grid-container').on('click', '.edit-btn', function() {
        let staffId = $(this).data('id');

        $.ajax({
            url: '/staff/' + staffId + '/edit',
            method: 'GET',
            success: function(response) {

                const staff = response.staff;
                const positions = response.positions;
                loadPositions('edit_staff_position_id', staff.position_id);

                $('#staff_id').val(staff.id);

                // Preview image or hide if no image
                if (staff.image) {
                    $('#preview_image').attr('src', `/storage/${staff.image}`).show();
                } else {
                    $('#preview_image').hide();
                }

                $('#staff_eid').val(staff.eid);
                $('#staff_name').val(staff.name);
                $('#staff_email').val(staff.email);
                $('#edit_staff_position_id').val(staff.position_id);
                $('#staff_dob').val(staff.dob ? staff.dob.split('T')[0] : '');
                $('#staff_currentaddress').val(staff.currentaddress);
                $('#staff_phno').val(staff.phno);
                $('#staff_department').val(staff.department);
                $('#staff_gender').val(staff.gender);

                $('#staff_married_status').prop('checked', staff.married_status == 1);
                $('#staff_super_user').prop('checked', staff.super_user == 1);

                // Load positions with current position selected
                var $posSelect = $('#edit_staff_position_id');
                $posSelect.empty().append('<option value="" disabled>ရာထူးရွေးပါ</option>');
                $.each(positions, function(i, pos) {
                    $posSelect.append(
                        `<option value="${pos.position_id}" ${pos.position_id == staff.position_id ? 'selected' : ''}>${pos.title}</option>`
                    );
                });

                $('#editStaffModal').modal('show');
            },
            error: function() {
                alert('Failed to fetch staff data.');
            }
        });
    });


    $('#editStaffForm').on('submit', function(e) {
        e.preventDefault();

        var staffId = $('#staff_id').val();
        var formData = new FormData(this);

        $('.invalid-feedback').html('');
        $('.form-control, .form-select, .form-check-input').removeClass('is-invalid');

        $.ajax({
            url: '/staff/' + staffId, // Assumes RESTful update route
            method: 'POST',          // Laravel expects POST + _method=PUT for PUT
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                $('#editStaffModal').modal('hide');
                location.reload(); // Reload to show updated data; adjust if you want to update table dynamically
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#error-' + field).html(messages[0]);
                        $('[name="' + field + '"]').addClass('is-invalid');
                    });
                } else {
                    alert('ဝန်ထမ်း ပြင်ဆင်ခြင်း မအောင်မြင်ပါ');
                }
            }
        });
    });

    $(document).on('click', '.view-btn', function() {
    var staffId = $(this).data('id');

    $.ajax({
        url: '/staff/' + staffId,  // Adjust to your route for fetching staff JSON data
        type: 'GET',
        success: function(data) {
            // Image URL or placeholder
            let imageUrl = data.image ? '/storage/' + data.image : 'https://via.placeholder.com/120';
            $('#staff_image_show').attr('src', imageUrl);

            // Fill in all the fields
            $('#staff_name_show').text(data.name);
            $('#staff_email_show').text(data.email);
            $('#staff_eid_show').text(data.eid || 'N/A');
            $('#staff_dob_show').text(data.dob || 'N/A');
            $('#staff_currentaddress_show').text(data.currentaddress || 'N/A');
            $('#staff_phno_show').text(data.phno || 'N/A');
            $('#staff_department_show').text(data.department || 'N/A');
            $('#staff_position_show').text(data.position_name || 'N/A');  // Adjust if your data has position name

            // Married status display
            $('#staff_married_status_show').text(data.married_status == 1 ? 'လက်ထပ်ထားပါသည်' : 'လူလွတ်');

            // Gender display
            let genderText = 'Other';
            if(data.gender == 0) genderText = 'အမျိုးသမီး';
            else if(data.gender == 1) genderText = 'အမျိုးသား';
            $('#staff_gender_show').text(genderText);

            // Show the modal
            $('#showStaffModal').modal('show');
        },
        error: function() {
            alert('Failed to load staff details.');
        }
    });
});

});
