$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#housingTable').DataTable({
        pageLength: 10,
        language: {
            search: "ရှာဖွေရန်:",
            lengthMenu: "တစ်စာမျက်နှာလျှင် _MENU_ စာရင်း",
            info: "_TOTAL_ မှ _START_ မှ _END_ အထိ ပြထားသည်",
            paginate: {
                first: "ပထမ",
                last: "နောက်ဆုံး",
                next: "နောက်",
                previous: "ရှေ့"
            }
        }
    });

    $(document).on('click', '.btn-view', function(e) {
        e.preventDefault();
        let id = $(this).data('id');

        $.get(`/admin/housing/${id}/show`, function(data) {
            $('#detail-name').text(data.user.name);
            $('#detail-family').text(data.family_member);
            $('#detail-township').text(data.township);
            $('#detail-approve-date').text(data.approved_date);
            $('#detail-submit-date').text(data.submit_date);
            $('#detail-description').text(data.description);

            if (data.house_hold_img) {
                let imgUrl = `/storage/${data.house_hold_img}`;
                $('#detail-image').attr('src', imgUrl);
                $('#download-image').attr('href', imgUrl).show();
            } else {
                $('#detail-image').attr('src', '');
                $('#download-image').hide();
            }

            $('#viewModal').modal('show');
        }).fail(function() {
            alert('Failed to load housing details.');
        });
    });

     $('.btn-show-approve-modal').click(function() {
        let id = $(this).data('id');

        // Set form action URL dynamically
        $('#approveDateForm').attr('action', `/admin/housing/${id}/approve`);

        // Reset date input (optional)
        $('#approved_date').val('');

        // Show the modal
        let approveModal = new bootstrap.Modal(document.getElementById('approveDateModal'));
        approveModal.show();
    });
});
