$(document).ready(function () {
    console.log('Leave Request JS loaded');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // View leave request details
    $(document).on('click', '.btn-view', function(e) {
        e.preventDefault();
        let id = $(this).data('id');

        $.get(`/admin/leave/${id}/show`, function(data) {
            $('#detail-name').text(data.user.name);
            $('#detail-leave-type').text(data.leave_type_name ? data.leave_type_name : 'လစာမဲ့ခွင့်');
            $('#detail-from-date').text(data.from_date ?? '-');
            $('#detail-to-date').text(data.to_date ?? '-');
            $('#detail-duration').text(data.duration ? data.duration + ' ရက်' : '-');
            $('#detail-description').text(data.description ?? '-');

            if (data.img) {
                let imgUrl = `/storage/${data.img}`;
                $('#detail-image').attr('src', imgUrl).show();
                $('#download-image').attr('href', imgUrl).show();
            } else {
                $('#detail-image').attr('src', '').hide();
                $('#download-image').hide();
            }

            $('#viewModal').modal('show');
        }).fail(function() {
            alert('Failed to load leave details.');
        });
    });

$(document).on('click', '.btn-approve', function() {
        let id = $(this).data('id');

        $.ajax({
            url: `/admin/leave/${id}/approve`,
            type: 'PUT',
            data: {
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Failed to approve leave request.');
            }
        });
    });




});
