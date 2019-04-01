import $ from 'jquery';
import AddressAutoComplete from "../../components/AddressAutoComplete";
import WorkingNote from "../../components/WorkingNote";

class FakeTypeahead {
    constructor() {
        this.listeners = {}
    }

    on(eventName, listener) {
        this.listeners[eventName] = [];
        this.listeners[eventName].push(listener);

        return this;
    }

    trigger(eventName, data) {
        this.listeners[eventName].forEach((listener) => {
            listener(new Event(eventName), data);
        })
    }
}

class FakeSelectize {
    constructor($element) {
        this.$element = $element;
        $element[0].selectize = this;
    }

    setValue(value) {
        this.$element.val(value);
    }
}


describe('AddressAutoComplete', function () {
    let $city;
    let $zipCode;
    let $province;
    let $country;
    let typeahead;

    beforeAll(() => {
        fixture.setBase('tests/fixtures');
    });

    beforeEach(() => {
        fixture.load('AddressAutoComplete.html');

        $city = $('.city');
        $zipCode = $('.zip-code');
        $province = $('.province');
        $country = $('.country');
        new FakeSelectize($country);

        typeahead = new FakeTypeahead();

        spyOn($.fn, 'typeahead').and.returnValue(typeahead);
    });

    it('builds the DOM', function () {
        new AddressAutoComplete($city, $zipCode, $province, $country);

        expect($city.typeahead).toBeDefined();

        expect($city.hasClass('loading')).toBeFalsy();
    });

    it('raises if the DOM is invalid', function () {
        const $empty = $('.unknown');
        expect(function() { new AddressAutoComplete($empty, $zipCode, $province, $country); }).toThrow(new Error('No city defined.'));
        expect(function() { new AddressAutoComplete($city, $empty, $province, $country); }).toThrow(new Error('No zip code defined.'));
        expect(function() { new AddressAutoComplete($city, $zipCode, $empty, $country); }).toThrow(new Error('No province defined.'));
        expect(function() { new AddressAutoComplete($city, $zipCode, $province, $empty); }).toThrow(new Error('No country defined.'));
    });

    it('raises an error if no search URL is defined', () => {
        $city.removeData('search-url').removeAttr('data-search-url');

        expect(function() { new AddressAutoComplete($city, $zipCode, $province, $country); }).toThrow(new Error('No search URL defined.'));
    });

    it('triggers loading events', function () {
        new AddressAutoComplete($city, $zipCode, $province, $country);

        typeahead.trigger('typeahead:asyncrequest');
        expect($city.hasClass('loading')).toBeTruthy();

        typeahead.trigger('typeahead:asyncreceive');
        expect($city.hasClass('loading')).toBeFalsy();

        typeahead.trigger('typeahead:asyncrequest');
        expect($city.hasClass('loading')).toBeTruthy();

        typeahead.trigger('typeahead:asynccancel');
        expect($city.hasClass('loading')).toBeFalsy();
    });

    it('triggers selected event', function () {
        const autocomplete = new AddressAutoComplete($city, $zipCode, $province, $country, { emitDelay: 0});
        let selectedItem = {};

        autocomplete.on('selected', function(item) {
            selectedItem = item;
        });

        typeahead.trigger('typeahead:select', { code: '12345', city: { province: { code: 'PR', countryId: 123 }}} );
        expect($zipCode.val()).toBe('12345');
        expect($province.val()).toBe('PR');
        expect($country.val()).toBe('123');
    });
});