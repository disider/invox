(function ($) {
    $.widget('invox.zipCodeTypeahead', {
        options: {
            url: null,
            provinceId: null,
            zipCodeId: null,
            countryId: null
        },

        _create: function () {
            var $city = this.element;
            var $self = this;

            $city.autocomplete({
                datasets: [{
                    url: this.options.url + '?term=',
                    field: 'zipCodes',
                    display: function (item) {
                        return item.city.name;
                    }
                }],
                templates: {
                    suggestion: function (item) {
                        return '<div>' + item.city.name + ' - ' + item.code + '</div>';
                    }
                }
            }).on('typeahead:select', function (event, item) {
                $($self.options.provinceId).val(item.city.province.code);
                $($self.options.zipCodeId).val(item.code);

                var selectize = $($self.options.countryId)[0].selectize;
                selectize.setValue(item.city.province.countryId);
            });
        }
    });
})(jQuery);