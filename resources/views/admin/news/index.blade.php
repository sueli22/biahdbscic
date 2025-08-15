@extends('admin.layout')

@section('content')
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">အသစ် တင်ရန်</button>
    <table class="table table-bordered" id="newsTable">
        <thead>
            <tr>
                <th>အမှတ်စဉ်</th>
                <th>ပုံ</th>
                <th>ခေါင်းစဉ်</th>
                <th>အကြောင်းအရာ</th>
                <th>လုပ်ဆောင်ချက်များ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($news as $item)
                <tr id="newsRow{{ $item->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td><img src="{{ asset('storage/' . $item->image) }}" width="50" alt="သတင်းပုံ" height="50"></td>
                    <td>{{ Str::limit($item->title, 30) }}</td>
                    <td>{{ Str::limit($item->content, 50) }}</td>
                    <td>
                        <!-- View button with eye icon -->
                        <button class="btn btn-info btn-sm viewBtn" data-title="{{ $item->title }}"
                            data-content="{{ $item->content }}" data-image="{{ $item->image }}">
                            <i class="bi bi-eye"></i>
                        </button>

                        <!-- Edit button with pencil icon -->
                        <button class="btn btn-warning btn-sm editBtn" data-id="{{ $item->id }}"
                            data-title="{{ $item->title }}" data-content="{{ $item->content }}"
                            data-image="{{ $item->image }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <!-- Delete button with trash icon -->
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $item->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>



    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="createForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">သတင်းအသစ် ထည့်ရန်</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>ခေါင်းစဉ်</label>
                            <input type="text" name="title" class="form-control">
                            <span class="text-danger error-text title_error"></span>
                        </div>
                        <div class="mb-3">
                            <label>အကြောင်းအရာ</label>
                            <textarea name="content" class="form-control"></textarea>
                            <span class="text-danger error-text content_error"></span>
                        </div>
                        <div class="mb-3">
                            <label>ပုံ</label>
                            <input type="file" name="image" class="form-control">
                            <span class="text-danger error-text image_error"></span>
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

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">သတင်းအသေးစိတ်ကြည့်ရန်</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>ခေါင်းစဉ်:</strong>
                        <p id="viewTitle"></p>
                    </div>
                    <div class="mb-3">
                        <strong>အကြောင်းအရာ:</strong>
                        <p id="viewContent"></p>
                    </div>
                    <div class="mb-3">
                        <img id="viewImage" src="" class="img-fluid" alt="News Image" width="100%">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်မည်</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="news_id" id="editNewsId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">သတင်းကို ပြင်ဆင်ရန်</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>ခေါင်းစဉ်</label>
                            <input type="text" name="title" id="editTitle" class="form-control">
                            <span class="text-danger error-text title_error"></span>
                        </div>
                        <div class="mb-3">
                            <label>အကြောင်းအရာ</label>
                            <textarea name="content" id="editContent" class="form-control"></textarea>
                            <span class="text-danger error-text content_error"></span>
                        </div>
                        <div class="mb-3">
                            <label>ပုံ</label>
                            <input type="file" name="image" class="form-control">
                            <img id="currentImage" src="" width="100" class="mt-2">
                            <span class="text-danger error-text image_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">ပြင်ဆင်မည်</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ပိတ်မည်</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $('#newsTable').DataTable({
                "pageLength": 10, // တစ်မျက်နှာမှာ ပြသမည့်ကြောင်းအရေအတွက်
                "lengthMenu": [5, 10, 25, 50, 100],
                "order": [
                    [0, 'desc']
                ], // ID အရ နောက်ဆုံးထည့်ထားသည့်စာများကို အပေါ်ဆုံး
                "language": {
                    "search": "ရှာဖွေရန်:",
                    "lengthMenu": "တစ်မျက်နှာကို _MENU_ အချက်အလက်များ ပြသမည်",
                    "info": "_TOTAL_ ခုထဲမှ _START_ မှ _END_ ကိုပြသနေသည်",
                    "paginate": {
                        "first": "ပထမ",
                        "last": "နောက်ဆုံး",
                        "next": "နောက်",
                        "previous": "နောက်ပြန်"
                    },
                    "zeroRecords": "မည်သည့်အချက်အလက်မှ မတွေ့ပါ"
                }
            });


            // Create
            $('#createForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $(this).find('span.error-text').text('');

                $.ajax({
                    url: "{{ route('news.store') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        location.reload();
                    },
                    error: function(err) {
                        if (err.status === 422) {
                            let errors = err.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#createForm').find('span.' + key + '_error').text(
                                    value[0]);
                            });
                        } else {
                            alert('သတင်းကို ထည့်သွင်းရာတွင် အမှားရှိပါသည်။');
                        }
                    }
                });
            });

            // Show edit modal and populate fields
            $('.editBtn').click(function() {
                let id = $(this).data('id');
                let title = $(this).data('title');
                let content = $(this).data('content');
                let image = $(this).data('image');

                $('#editNewsId').val(id);
                $('#editTitle').val(title);
                $('#editContent').val(content);

                if (image) {
                    $('#currentImage').attr('src', '/storage/' + image);
                } else {
                    $('#currentImage').attr('src', '');
                }

                $('#editModal').modal('show'); // trigger Bootstrap modal
            });


            // Edit
            $('#editForm').submit(function(e) {
                e.preventDefault();
                let id = $('#editNewsId').val();
                let formData = new FormData(this);

                $(this).find('span.error-text').text('');

                $.ajax({
                    url: "/news/" + id,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        location.reload();
                    },
                    error: function(err) {
                        if (err.status === 422) {
                            let errors = err.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#editForm').find('span.' + key + '_error').text(
                                    value[0]);
                            });
                        } else {
                            alert('သတင်းကို ပြင်ဆင်ရာတွင် အမှားရှိပါသည်။');
                        }
                    }
                });
            });

            // View
            $('.viewBtn').click(function() {
                let title = $(this).data('title');
                let content = $(this).data('content');
                let image = $(this).data('image');

                $('#viewTitle').text(title);
                $('#viewContent').text(content);

                if (image) {
                    $('#viewImage').attr('src', '/storage/' + image).show();
                } else {
                    $('#viewImage').hide();
                }

                var viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
                viewModal.show();
            });



            // Delete
            $('.deleteBtn').click(function() {
                if (confirm('Are you sure to delete?')) {
                    let id = $(this).data('id');

                    $.ajax({
                        url: "/news/" + id,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            $('#newsRow' + id).remove();
                        },
                        error: function(err) {
                            alert('Error deleting news');
                        }
                    });
                }
            });

        });
    </script>
@endsection
