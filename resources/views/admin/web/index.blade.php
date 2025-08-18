@extends('admin.layout')

@section('content')
<div class="container">
    <h2>Web Settings</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Color</th>
                <th>Logo</th>
                <th>Background</th>
                <th>Footer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="webTable">
            @foreach ($webs as $web)
            <tr id="row-{{ $web->id }}">
                <td>
                    <span style="display:inline-block;width:30px;height:30px;background-color:{{ $web->color }};border:1px solid #ccc;border-radius:50%;"></span>
                    <small class="ms-1">{{ $web->color }}</small>
                </td>
                <td>
                    @if ($web->logoimg)
                    <img src="{{ asset('logo/' . $web->logoimg) }}" width="50" alt="Logo">
                    @endif
                </td>
                <td>
                    @if ($web->bgimg)
                    <img src="{{ asset('bg/' . $web->bgimg) }}" width="50" alt="Background">
                    @endif
                </td>
                <td>{!! $web->footer !!}</td>
                <td>
                    <button class="btn btn-warning btn-sm editBtn"
                        data-id="{{ $web->id }}"
                        data-color="{{ $web->color }}"
                        data-footer="{{ $web->footer }}"
                        data-logo="{{ $web->logoimg }}"
                        data-bg="{{ $web->bgimg }}">
                        Edit
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">

                <div class="modal-body">
                    {{-- Color Picker --}}
                    <div class="mb-3 d-flex align-items-center">
                        <label class="me-3 mb-0">Color</label>
                        <input type="text" name="color" id="select-place-color" class="form-control me-2" style="width:120px;" readonly>
                        <div id="color-picker"></div>
                        <span class="text-danger error-text color_error ms-2"></span>
                    </div>

                    {{-- Logo --}}
                    <div class="mb-3">
                        <label>Logo Image</label>
                        <input type="file" name="logoimg" id="editLogo" class="form-control">
                        <img id="currentLogo" src="" width="80" class="mt-2" style="display:none;">
                        <span class="text-danger error-text logoimg_error"></span>
                    </div>

                    {{-- Background --}}
                    <div class="mb-3">
                        <label>Background Image</label>
                        <input type="file" name="bgimg" id="editBg" class="form-control">
                        <img id="currentBg" src="" width="80" class="mt-2" style="display:none;">
                        <span class="text-danger error-text bgimg_error"></span>
                    </div>

                    {{-- Footer --}}
                    <div class="mb-3">
                        <label>Footer</label>
                        <textarea name="footer" id="editFooter" class="form-control"></textarea>
                        <span class="text-danger error-text footer_error"></span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Pickr Color Picker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" />
<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>

<!-- CKEditor 5 Classic -->
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

<script>
$(document).ready(function() {
    // Initialize CKEditor 5
    let editor;
    ClassicEditor
        .create(document.querySelector('#editFooter'), {
            toolbar: [
                'undo', 'redo', '|',
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'fontColor', 'fontBackgroundColor', 'fontSize', '|',
                'bulletedList', 'numberedList', '|',
                'link', 'blockQuote', '|',
                'alignment', 'indent', 'outdent', '|',
                'removeFormat'
            ],
            fontSize: {
                options: [9, 11, 13, 'default', 17, 19, 21, 25, 30]
            }
        })
        .then(newEditor => { editor = newEditor; })
        .catch(error => console.error(error));

    // Initialize Pickr
    var pickr = Pickr.create({
        el: '#color-picker',
        theme: 'classic',
        default: '#ffffff',
        components: {
            preview: true,
            opacity: true,
            hue: true,
            interaction: { hex: true, input: true, save: true }
        }
    });

    pickr.on('save', function(color) {
        var hexColor = color.toHEXA().toString();
        $('#select-place-color').val(hexColor);
        pickr.hide();
    });

    // Open Edit Modal
    $(document).on('click', '.editBtn', function() {
        var id = $(this).data('id');
        var color = $(this).data('color');
        var footer = $(this).data('footer');
        var logo = $(this).data('logo');
        var bg = $(this).data('bg');

        $('#editId').val(id);
        $('#select-place-color').val(color);
        pickr.setColor(color);

        if(editor){
            editor.setData(footer || '');
        }

        if(logo){
            $('#currentLogo').attr('src', '/logo/' + logo).show();
        } else { $('#currentLogo').hide(); }

        if(bg){
            $('#currentBg').attr('src', '/bg/' + bg).show();
        } else { $('#currentBg').hide(); }

        $('#editModal').modal('show');
    });

    // AJAX Submit
    $('#editForm').submit(function(e) {
        e.preventDefault();
        var id = $('#editId').val();
        var formData = new FormData(this);
        formData.set('footer', editor.getData());

        $('.error-text').text('');
        $('#editForm input, #editForm textarea').removeClass('is-invalid');

        $.ajax({
            url: '/webs/' + id,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                var row = $('#row-' + id);
                row.find('td:nth-child(4)').html(res.footer); // update footer in table
                $('#editModal').modal('hide');
            },
            error: function(xhr) {
                if(xhr.status === 422){
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value){
                        $('.' + key + '_error').text(value[0]);
                        $('#edit' + key.charAt(0).toUpperCase() + key.slice(1)).addClass('is-invalid');
                    });
                }
            }
        });
    });
});
</script>
@endsection
