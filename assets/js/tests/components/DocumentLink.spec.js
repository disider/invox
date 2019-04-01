import $ from 'jquery';
import DocumentLink from "../../components/DocumentLink";

describe('DocumentLink', () => {
    let $link;
    let $icon;
    let $document;

    beforeAll(() => {
        fixture.setBase('tests/fixtures');
    });

    beforeEach(() => {
        fixture.load('DocumentLink.html');

        $link = $('.document-link');
        $icon = $link.find('span');
        $document = $('.document');
    });

    it('builds the DOM', () => {
        new DocumentLink($link, $document);

        expect($document.val()).toEqual('');
        expect($icon.attr('class')).toEqual('fa-search');
    });

    it('builds the DOM with a predefined value', () => {
        $document.val('123');

        new DocumentLink($link, $document);

        expect($document.val()).toEqual('123');
        expect($icon.attr('class')).toEqual('fa-link');
    });

    it('changes the icon on mouse hover', () => {
        $document.val('123');

        new DocumentLink($link, $document);

        $link.trigger('mouseenter');
        expect($icon.attr('class')).toEqual('fa-chain-broken');

        $link.trigger('mouseleave');
        expect($icon.attr('class')).toEqual('fa-link');
    });

    it('unlinks the document', () => {
        $document.val('123');

        new DocumentLink($link, $document);

        $link.trigger('click');

        expect($document.val()).toEqual('');
        expect($icon.attr('class')).toEqual('fa-search');
    });

    it('triggers events', () => {
        let linked = false;
        let unlinked = false;

        const documentLink = new DocumentLink($link, $document, { emitDelay: 0});
        documentLink.on('linked', (itemId) => {
            linked = true;
        });
        documentLink.on('unlinked', () => {
            unlinked = true
        });

        documentLink.link(123);
        documentLink.unlink();

        expect(linked).toBeTruthy();
        expect(unlinked).toBeTruthy();
    });
});