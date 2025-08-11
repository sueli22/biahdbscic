$(document).ready(function () {
    function translate(lang) {
    var text = $('#input-text').val();

    $.ajax({
        url: "{{ route('translate') }}",
        method: "POST",
        data: JSON.stringify({
            text: text,
            target: lang,
            source: 'my', // original is Myanmar
        }),
        contentType: "application/json",
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function(data) {
            $('#result').text(data.translated);
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('Translation failed');
        }
    });
}

});
