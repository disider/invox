import $ from 'jquery';
import AutoComplete from "../../components/AutoComplete";

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


describe('AutoComplete', function () {
    let $input;
    let typeahead;

    beforeAll(() => {
        fixture.setBase('tests/fixtures');
    });

    beforeEach(() => {
        fixture.set('<input type="text" class="autocomplete" value="" />');

        $input = $('.autocomplete');

        typeahead = new FakeTypeahead();

        spyOn($.fn, 'typeahead').and.returnValue(typeahead);
    });

    it('builds the DOM', function () {
        new AutoComplete($input);

        expect($input.typeahead).toBeDefined();

        expect($input.hasClass('loading')).toBeFalsy();
    });

    it('triggers loading events', function () {
        new AutoComplete($input);

        typeahead.trigger('typeahead:asyncrequest');
        expect($input.hasClass('loading')).toBeTruthy();

        typeahead.trigger('typeahead:asyncreceive');
        expect($input.hasClass('loading')).toBeFalsy();

        typeahead.trigger('typeahead:asyncrequest');
        expect($input.hasClass('loading')).toBeTruthy();

        typeahead.trigger('typeahead:asynccancel');
        expect($input.hasClass('loading')).toBeFalsy();
    });

    it('triggers selected event', function () {
        const autocomplete = new AutoComplete($input, { emitDelay: 0});
        let selectedItem = {};

        autocomplete.on('selected', function(item) {
            selectedItem = item;
        });

        typeahead.trigger('typeahead:select', { id: 123, value: 'item'} );
        expect(selectedItem.id).toBe(123);
    });
});