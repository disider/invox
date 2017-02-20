(function ($) {
    $.widget('invox.documentRow', {
        options: {
            decimalPoint: '.',
            thousandSep: ',',
            productUrl: null,
            serviceUrl: null,
            productTitle: '',
            serviceTitle: '',
            isDuplicate: false,
            updateTotal: false,
            currency: '€'
        },

        _create: function () {
            var self = this;
            var $element = this.element;

            this.$unitPrice = $element.find('.unit-price');
            this.$quantity = $element.find('.quantity');
            this.$taxRate = $element.find('.tax-rate');
            this.$discount = $element.find('.discount');
            this.$discountPercent = $element.find('.discount-percent');

            var $discountPercentToggle = $element.find('.discount-percent-toggle');
            $discountPercentToggle.discountPercentToggle({
                currency: this.options.currency,
                percentChanged: function () {
                    self.updateTotal();
                }
            });

            this.$total = $element.find('.total');

            this.$netTotal = $('<input type="hidden" class="net-total" />');
            this.$taxesTotal = $('<input type="hidden" class="taxes-total" />');
            this.$netTotal.insertAfter(this.$total);
            this.$taxesTotal.insertAfter(this.$total);

            $element.on('change', 'select, input', function () {
                self.updateTotal();
            });

            var isDuplicate = $element.hasClass('ui-document-row');

            if (isDuplicate) {
                var $title = $element.find('.title:not(.tt-hint)');

                $title.attr('style', '');
                $title.removeClass('tt-input');

                var $parent = $title.parent();
                $title.appendTo($title.parents('.input-group'));

                $parent.remove();
            }
            else {
                $element.addClass('ui-document-row');
            }

            this.$title = $element.find('.title');
            this.setupAutocomplete();

            if (this.options.updateTotal) {
                this.updateTotal();
            }
        },

        updateTotal: function () {
            var vat = this.$taxRate.find(':selected').data('amount');
            var net = this._calculateDiscountedPrice() * this._parseVal(this.$quantity);

            if (isNaN(net)) {
                net = 0;
            }

            var taxes = net * (vat / 100);
            var total = net + taxes;

            this.$netTotal.val(this._formatMoney(net));
            this.$taxesTotal.val(this._formatMoney(taxes));
            this.$total.val(this._formatMoney(total));

            this.element.trigger('row-change');
        },

        setupAutocomplete: function () {
            if (!(this.options.productUrl || this.options.serviceUrl)) {
                return;
            }

            var self = this;
            var $element = this.element;

            var $addon = this.$title.siblings('.input-group-addon');
            var $linkedProduct = $element.find('.linked-product');
            var $linkedService = $element.find('.linked-service');
            $linkedProduct.hide();
            $linkedService.hide();

            $element.on('click', '.unlink-product', function (event) {
                $linkedProduct.val('');
                event.preventDefault();
                event.stopPropagation();

                $addon.html(' ');
            });

            $element.on('click', '.unlink-service', function (event) {
                $linkedService.val('');
                event.preventDefault();
                event.stopPropagation();

                $addon.html(' ');
            });

            var datasets = [];

            if (this.options.productUrl) {
                var dataset = {
                    url: self.options.productUrl + '?term=',
                    field: 'products',
                    display: function (item) {
                        return item.name;
                    }
                };

                datasets.push(dataset);
            }

            if (this.options.serviceUrl) {
                var dataset = {
                    url: self.options.serviceUrl + '?term=',
                    field: 'services',
                    display: function (item) {
                        return item.name;
                    }
                };

                datasets.push(dataset);
            }

            this.$title.autocomplete({
                datasets: datasets
            }).on('typeahead:select', function (event, item) {
                var addon = '';
                if (item.type == 'product') {
                    $linkedProduct.val(item.id);
                    $linkedService.val('');
                    addon = '<a href="#" class="unlink-product">' + self.options.productTitle + '</a>';
                }
                else {
                    $linkedProduct.val('');
                    $linkedService.val(item.id);
                    addon = '<a href="#" class="unlink-service">' + self.options.serviceTitle + '</a>';
                }
                $addon.html(addon);

                if (item.unitPrice) {
                    self.$unitPrice.val(self._formatMoney(item.unitPrice));
                }
                else {
                    self.$unitPrice.val('');
                }

                if (item.taxRate) {
                    self.$taxRate.val(item.taxRate);
                }

                self.updateTotal();
            });
        },

        _calculateDiscountedPrice: function () {
            var discount = this._parseVal(this.$discount);
            var unitPrice = this._parseVal(this.$unitPrice);
            var isPercent = this.$discountPercent.is(':checked');

            if (isPercent) {
                return unitPrice - (unitPrice * discount) / 100;
            }

            return unitPrice - discount;
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
