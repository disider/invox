import $ from 'jquery';
import WorkingNote from '../../components/WorkingNote';

describe('WorkingNote', () => {
    let $input, $linkedTo, $link, $emptyList;
    beforeAll(() => {
        fixture.setBase('tests/fixtures');
    });

    beforeEach(() => {
        fixture.load('WorkingNote.html');

        $input = $('.autocomplete');
        $linkedTo = $('.document');
        $link = $('.document-link');
        $emptyList = $('#empty-list');
    });

    it('builds the DOM', () => {
        const workingNote = new WorkingNote($input, $linkedTo, $link, $emptyList);

        expect(workingNote).toBeDefined();
        expect(workingNote.autocomplete.options.dataSets[0].url).toBe($input.data('search-url') + '?term=');
        expect(workingNote.autocomplete.options.templates.empty).toBe($emptyList.html());
    });

    it('raises an error if no search URL is defined', () => {
        $input.removeData('search-url').removeAttr('data-search-url');

        expect(function() { new WorkingNote($input, $linkedTo, $link, $emptyList) }).toThrow(new Error('No search URL defined.'));
    });

    it('clears the input if the document is unlinked', () => {
        $input.val('123');

        const workingNote = new WorkingNote($input, $linkedTo, $link, $emptyList);

        workingNote.documentLink.emit('unlinked');

        expect($input.val()).toBe('')
    });

    it('sets the linked document when an item is selected', () => {
        const workingNote = new WorkingNote($input, $linkedTo, $link, $emptyList);

        workingNote.autocomplete.emit('selected', {id: 123});

        expect($linkedTo.val()).toBe('123');
    });
});