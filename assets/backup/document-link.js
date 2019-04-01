(function ($) {
    $.widget('invox.documentLink', {
        options: {
            linkedTo: null
        },

        _create: function () {
            var $element = this.element;
            var $linkedElement = this.options.linkedTo;
            var $span = $element.find('span');

            $linkedElement.attr('type', 'hidden');

            $element.hover(function () {
                if ($linkedElement.val()) {
                    $span.removeClass('fa-link');
                    $span.addClass('fa-chain-broken');
                }
            }, function () {
                if ($linkedElement.val()) {
                    $span.addClass('fa-link');
                    $span.removeClass('fa-chain-broken');
                }
            });

            $element.on('click', function(event) {
                $linkedElement.val('');

                $span.removeClass('fa-link');
                $span.removeClass('fa-chain-broken');
                $span.addClass('fa-search');

                event.preventDefault();
                event.stopPropagation();
            });
        },

        setLink: function() {
            var $span = this.element.find('span');
            $span.addClass('fa-link');
            $span.removeClass('fa-search');
        }
    });
})(jQuery);
