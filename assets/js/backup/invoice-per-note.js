(function ($) {
    $.widget('invox.invoicePerNote', {
        options: {
            maxAmount: 0,
            url: '',
            emptyListTitle: ''
        },

        _create: function () {
            var self = this;
            var $element = this.element;

            $element.on('change', 'select, input', function () {
                self.updateAmount();
            });

            var $amount = $element.find('.amount');
            var $invoice = $element.find('.invoice');
            var $invoiceTitle = $element.find('.invoice-title');

            $invoiceTitle.autocomplete({
                datasets: [{
                    url: self.options.url,
                    field: 'documents',
                    display: function (item) {
                        return item.fullRef;
                    }
                }],
                templates: {
                    empty: '<div class="empty-list">' + this.options.emptyListTitle + '</div>',
                    suggestion: function (item) {
                        return '<div>' + item.fullTitle + '</div>';
                    }
                }
            }).on('typeahead:select', function (event, item) {
                $invoice.val(item.id);

                var amount = self._formatMoney(Math.min(item.unpaidAmount, self.options.maxAmount));
                console.log(amount);
                $amount.val(amount);

                self.updateAmount();
            });

            $invoice.hide();
        },

        setMaxAmount: function (value) {
            this.options.maxAmount = value;
        },

        updateAmount: function () {
            this.element.trigger('invoice-change');
        },

        _parseVal: function ($input) {
            return $input.val().parseMoney(this.options.decimalPoint, this.options.thousandSep);
        },

        _formatMoney: function (val) {
            if (!(val instanceof Number)) {
                val = parseFloat(val);
            }

            return val.formatMoney(2, this.options.decimalPoint, this.options.thousandSep);
        }
    });
})(jQuery);
