$(document).ready(function () {
        // Smooth scroll to the login section
        $('html, body').animate({
            scrollTop: $('#login').offset().top
        }, 200);

        // Optional: focus on the email field after scrolling
        setTimeout(function() {
            $('#email').focus();
        }, 300);
    });
