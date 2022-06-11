// :: Loading
$(window).on('load', function() {
    $('.loading').fadeOut();
    let form = document.querySelector('#excel-file-form');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        $('.loading').fadeIn();
        form.submit();
    })
});