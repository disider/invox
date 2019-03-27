import $ from 'jquery';

global.$ = global.jQuery = $;

$(function () {
    $('html').addClass('js');
});
