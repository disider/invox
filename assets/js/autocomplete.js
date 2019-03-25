(function ($) {
    $.widget('invox.autocomplete', {
        options: {
            field: null,
            minLength: 3,
            datasets: [],
            emptyMessage: null,
            templates: {}
        },

        _create: function () {
            var $self = this;
            var $element = this.element;

            var datasets = [];

            var templates = this.options.templates;

            $.each(this.options.datasets, function (index, el) {
                var dataset = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace,
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    remote: {
                        url: el.url,
                        type: 'POST',
                        prepare: function (query, settings) {
                            $element.addClass('loading');

                            return settings.url + query;
                        },
                        transform: function (data) {
                            $element.removeClass('loading');

                            return data[el.field];
                        }
                    }
                });

                datasets.push({
                    display: el.display,
                    source: dataset,
                    templates: templates,
                    limit: Infinity
                });
            });

            $element.typeahead({
                highlight: true,
                delay: 1000,
                minLength: this.options.minLength
            }, datasets).on('typeahead:select', function (event, item) {
                $self._trigger('afterSelect', event, item);
            });
        }
    });
})(jQuery);
