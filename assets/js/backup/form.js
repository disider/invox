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

$('.colorpicker-component').colorpicker();

$('[data-toggle="popover"]').on('click', function(e){
    e.preventDefault();
}).popover();

$('[data-toggle="tooltip"]').on('click', function(e){
    e.preventDefault();
}).tooltip();

Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

String.prototype.parseMoney = function (d, t) {
    var n = this,
        d = d == undefined ? '\\.' : d.replace('.', '\\.'),
        t = t == undefined ? ',' : t.replace('.', '\\.');

    return parseFloat(n.replace(new RegExp(t, 'g'), '').replace(new RegExp(d, 'g'), '.'));
};
