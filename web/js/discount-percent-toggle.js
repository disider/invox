(function ($) {
    $.widget('invox.discountPercentToggle', {
        options: {
            discountId: null,
            currency: '€'
        },

        _create: function () {
            var self = this;
            var $element = this.element;

            this.$discountPercent = $element.parent().find('.discount-percent');

            $element.on('click', function (event) {
                self.$discountPercent.prop('checked', !self.$discountPercent.prop('checked'));
                self.update();

                self._trigger('percentChanged');

                event.preventDefault();
                event.stopPropagation();
            });

            this.update();
        },

        update:function() {
            this.element.html(this.$discountPercent.is(':checked') ? '%' : this.options.currency);
        }
    });
})(jQuery);
