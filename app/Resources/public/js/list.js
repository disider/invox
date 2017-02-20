$('.selectize').selectize({
    plugins: ['remove_button'],
    selectOnTab: true,
    closeAfterSelect: true
});

$('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true,
    todayHighlight: true
});

$('[data-toggle="popover"]').on('click', function(e){
    e.preventDefault();
}).popover();

$('[data-toggle="tooltip"]').on('click', function(e){
    e.preventDefault();
}).tooltip();
