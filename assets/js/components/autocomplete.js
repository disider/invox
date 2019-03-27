'use strict';

import 'jquery';
import 'corejs-typeahead/dist/typeahead.jquery';
import Bloodhound from 'corejs-typeahead/dist/bloodhound';
import EventEmitter from 'event-emitter-es6';

class AutoComplete extends EventEmitter {
    constructor($element, options) {
        super();

        this.options = {
            field: null,
            minLength: 3,
            dataSets: [],
            emptyMessage: null,
            templates: {}
        };

        Object.assign(this.options, options);

        let dataSets = [];

        let templates = this.options.templates;

        this.options.dataSets.forEach(function (el) {
            const dataSet = new Bloodhound({
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

            dataSets.push({
                display: el.display,
                source: dataSet,
                templates: templates,
                limit: Infinity
            });
        });

        $element.typeahead({
            highlight: true,
            delay: 1000,
            minLength: this.options.minLength
        }, dataSets)
            .on('typeahead:asyncrequest', function () {
                $element.addClass('loading');
            })
            .on('typeahead:asyncreceive', function () {
                $element.removeClass('loading');
            })
            .on('typeahead:asynccancel', function () {
                $element.removeClass('loading');
            })
            .on('typeahead:select', this.onAfterSelect.bind(this));
    }

    onAfterSelect(event, item) {
        this.emit('afterSelect', item);
    }
}

export default AutoComplete;
