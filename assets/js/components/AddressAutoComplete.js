'use strict';

import 'corejs-typeahead/dist/typeahead.jquery';
import EventEmitter from 'event-emitter-es6';
import AutoComplete from "./AutoComplete";

class AddressAutoComplete extends EventEmitter {
    constructor($city, $zipCode, $province, $country, options = {}) {
        super(options);

        if ($city.length === 0) throw new Error('No city defined.');
        if ($zipCode.length === 0) throw new Error('No zip code defined.');
        if ($province.length === 0) throw new Error('No province defined.');
        if ($country.length === 0) throw new Error('No country defined.');

        const searchUrl = $city.data('search-url');
        if (!searchUrl) {
            throw new Error('No search URL defined.');
        }

        this.$zipCode = $zipCode;
        this.$province = $province;
        this.$country = $country;


        let datasets = [{
            url: searchUrl + '?term=',
            field: 'zipCodes',
            display: function (item) {
                return item.city.name;
            },
        }];

        this.options = {
            minLength: 3,
            datasets: datasets,
            templates: {
                empty: '<div>---</div>',
                suggestion: function (item) {
                    return '<div>' + item.city.name + ' - ' + item.code + '</div>';
                }
            }
        };

        Object.assign(this.options, options);

        // let templates = this.options.templates;
        // console.log(datasets);

        const autocomplete = new AutoComplete($city, this.options);
        autocomplete.on('selected', this.onSelected.bind(this));
        // $city.typeahead({
        //     datasets: datasets,
        //     templates: templates,
        //     highlight: true,
        //     delay: 1000,
        //     minLength: this.options.minLength
        // }, datasets)
        //     .on('typeahead:asyncrequest', function () {
        //         $city.addClass('loading');
        //     })
        //     .on('typeahead:asyncreceive', function () {
        //         $city.removeClass('loading');
        //     })
        //     .on('typeahead:asynccancel', function () {
        //         $city.removeClass('loading');
        //     })
        //     .on('typeahead:select', );
    }

    onSelected(item) {
        this.$zipCode.val(item.code);
        this.$province.val(item.city.province.code);
        this.$country.val(item.city.province.countryId);//[0].selectize.setValue(item.city.province.countryId);
        this.$country[0].selectize.setValue(item.city.province.countryId);
    }
}

export default AddressAutoComplete;
