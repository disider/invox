(function ($) {
    $.widget('invox.invoicesPerNote', {
        options: {
            decimalPoint: '.',
            thousandSep: ',',
            url: null,
            maxAmount: 0,
            emptyListTitle: ''
        },

        _create: function () {
            var self = this;
            var $element = this.element;
            var $invoices = $element.find('.invoice-row');

            this.addRow($invoices);

            var $summary = $('.summary');
            this.$totals = $summary.find('.totals');

            this.$invoicedTotal = this.$totals.find('.invoiced-total');
            this.$notInvoicedTotal = this.$totals.find('.not-invoiced-total');

            $element.on('invoice-change', '.invoice-row', function () {
                self.updateTotals();
            });

            $invoices.invoicePerNote('updateAmount');
        },

        addRow: function($row) {
            $row.invoicePerNote({
                decimalPoint: this.options.decimalPoint,
                thousandSep: this.options.thousandSep,
                url: this.options.url,
                maxAmount: this.options.maxAmount,
                emptyListTitle: this.options.emptyListTitle
            });
        },

        setMaxAmount: function (value) {
            this.options.maxAmount = value;

            var $invoices = this.element.find('.invoice-row');
            $invoices.invoicePerNote('setMaxAmount', value);
            this.updateTotals();
        },

        updateTotals: function () {
            var $invoices = this.element.find('.invoice-row');
            
            var invoicedAmount = this._calculateTotal($invoices, '.amount');

            this.$invoicedTotal.text(this._formatMoney(invoicedAmount));
            this.$notInvoicedTotal.text(this._formatMoney(this.options.maxAmount - invoicedAmount));
        },

        _calculateTotal: function ($invoices, $class) {
            var self = this;
            var total = 0;
            $invoices.find($class).each(function () {
                total += this.value.parseMoney(self.options.decimalPoint, self.options.thousandSep);
            });

            return total;
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
