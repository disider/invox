(function ($) {
    $.widget('invox.pettyCashNote', {
        options: {
            decimalPoint: '.',
            thousandSep: ',',
            amountId: null,
            invoicesId: null,
            documentUrl: null,
            emptyListTitle: '',
            currentNoteId: null
        },

        _create: function () {
            var self = this;
            var $element = this.element;
            var $amount = $element.find('#' + this.options.amountId);
            var $invoices = $element.find('#' + this.options.invoicesId);

            $invoices.invoicesPerNote({
                decimalPoint: this.options.decimalPoint,
                thousandSep: this.options.thousandSep,
                url: this.options.documentUrl + self._formatQueryString(),
                maxAmount: self._parseVal($amount),
                emptyListTitle: this.options.emptyListTitle
            });

            $amount.on('change', function() {
                $invoices.invoicesPerNote('setMaxAmount', self._parseVal($amount));
            });
        },
        
        _formatQueryString: function() {
            if(this.options.currentNoteId) {
                return '?type=invoice&currentNoteId=' + this.options.currentNoteId + '&term=';
            }
            
            return '?type=invoice&status=unpaid&term='
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
