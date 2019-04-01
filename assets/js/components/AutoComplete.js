'use strict';

import 'corejs-typeahead/dist/typeahead.jquery';
import Bloodhound from 'corejs-typeahead/dist/bloodhound';
import EventEmitter from 'event-emitter-es6';

class AutoComplete extends EventEmitter {
    constructor($element, options = {}) {
        super(options);

        this.options = {
            minLength: 3,
            datasets: [],
            templates: {}
        };

        Object.assign(this.options, options);

        let datasets = [];

        let templates = this.options.templates;

        this.options.datasets.forEach(function (el) {
            const dataset = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: el.url,
                    type: 'POST',
                    prepare: function (query, settings) {
                        return settings.url + query;
                    },
                    transform: function (data) {
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
        }, datasets)
            .on('typeahead:asyncrequest', function () {
                $element.addClass('loading');
            })
            .on('typeahead:asyncreceive', function () {
                $element.removeClass('loading');
            })
            .on('typeahead:asynccancel', function () {
                $element.removeClass('loading');
            })
            .on('typeahead:select', this.onSelected.bind(this));
    }

    onSelected(event, item) {
        this.emit('selected', item);
    }
}

export default AutoComplete;
