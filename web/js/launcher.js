$(function(){
    var $description = $('#description');

    $description.on('hide.bs.collapse', toggleArrows);
    $description.on('show.bs.collapse', toggleArrows);

    function toggleArrows() {
        var $showButton = $('#show-more');

        var arrowUp = $showButton.find('.fa.fa-chevron-up');
        var arrowDown = $showButton.find('.fa.fa-chevron-down');

        arrowUp.toggleClass('hidden');
        arrowDown.toggleClass('hidden');
    }
});