(function ($) {
    $.widget('invox.documentRows', {
        options: {
            decimalPoint: '.',
            thousandSep: ',',
            productUrl: null,
            serviceUrl: null,
            productTitle: '',
            serviceTitle: '',
            currency: '€'
        },

        _create: function () {
            var self = this;
            var $element = this.element;
            var $rows = $element.find('.document-row');

            this.addRow($rows, false);

            var $summary = $('.summary');
            this.$taxesContainer = $summary.find('.taxes');
            this.$totals = $summary.find('.totals');
            this.$taxesTemplate = $('#taxes-template');

            this.$netTotal = this.$totals.find('.net-total');
            this.$grossTotal = this.$totals.find('.gross-total');
            this.$discount = this.$totals.find('.discount');
            this.$discountPercent = this.$totals.find('.discount-percent');
            this.$rounding = this.$totals.find('.rounding');

            var $discountPercentToggle = this.$totals.find('.discount-percent-toggle');
            $discountPercentToggle.discountPercentToggle({
                currency: this.options.currency,
                percentChanged: function () {
                    self.updateTotals();
                }
            });


            $element.on('row-change', '.document-row', function () {
                self.updateTotals();
            });

            this.$discount.on('change', function () {
                self.updateTotals();
            });

            this.$rounding.on('change', function () {
                self.updateTotals();
            });

            $rows.documentRow('updateTotal');
        },

        addRow: function ($row, updateTotal) {
            $row.documentRow({
                decimalPoint: this.options.decimalPoint,
                thousandSep: this.options.thousandSep,
                productUrl: this.options.productUrl,
                serviceUrl: this.options.serviceUrl,
                productTitle: this.options.productTitle,
                serviceTitle: this.options.serviceTitle,
                currency: this.options.currency,
                updateTotal: updateTotal
            });
        },

        updateTotals: function () {
            var self = this;
            var $element = this.element;
            var $rows = $element.find('.document-row');
            var $taxesContainer = this.$taxesContainer;
            $taxesContainer.html('');

            this.$netTotal.text(this._formatMoney(this._calculateTotal($rows, '.net-total')));
            this.$grossTotal.text(this._formatMoney(
                this._calculateDiscount($rows) + this._parseVal(this.$rounding)
            ));

            var taxes = {};
            $rows.each(function () {
                var $row = $(this);
                var netTotal = self._parseVal($row.find('.net-total'));
                var taxesTotal = self._parseVal($row.find('.taxes-total'));
                var $currentTaxRate = $row.find('.tax-rate :selected');

                var taxRateAmount = parseFloat($currentTaxRate.data('amount'));

                if (isNaN(taxRateAmount)) {
                    return;
                }

                var rate = taxRateAmount.toFixed(2);

                // console.log(netTotal, taxesTotal, rate);

                if (!taxes[rate]) {
                    taxes[rate] = {
                        'taxes': taxesTotal,
                        'net': netTotal
                    };
                }
                else {
                    taxes[rate]['taxes'] += taxesTotal;
                    taxes[rate]['net'] += netTotal;
                }
            });

            $.each(taxes, function (rate, el) {
                var taxesRow = self.$taxesTemplate.html();
                taxesRow = taxesRow.replace(/%amount%/g, rate.replace('.', ''));
                taxesRow = taxesRow.replace(/%taxRate%/g, self._formatMoney(rate));
                taxesRow = taxesRow.replace(/%netTotal%/g, self._formatMoney(el.net));
                taxesRow = taxesRow.replace(/%taxesTotal%/g, self._formatMoney(el.taxes));
                $taxesContainer.append(taxesRow);
            });
        },

        _calculateTotal: function ($rows, $class) {
            var self = this;
            var total = 0;
            $rows.find($class).each(function () {
                total += this.value.parseMoney(self.options.decimalPoint, self.options.thousandSep);
            });

            return total;
        },

        _calculateDiscount: function ($rows) {
            var total = this._calculateTotal($rows, '.total');
            var discount = this._parseVal(this.$discount);
            var isPercent = this.$discountPercent.is(':checked');

            if (isPercent) {
                return total - (total * discount) / 100;
            }
            
            return total - discount;
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
