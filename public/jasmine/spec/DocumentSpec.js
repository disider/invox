describe('Document', function () {
    var dom;
    var plugin;
    var initialItems = 3;

    beforeEach(function () {
        loadFixtures('document.html');
        dom = $('.rows');
        dom.document();

        plugin = dom.data('plugin_document');
    });

    it('to be defined', function () {
        expect(dom).toBeDefined();
        expect(plugin).toBeDefined();

        var $row = dom.find('tr');

        expect($row.find('.position')).toHaveClass('hidden');
    });

    it('items are sortable', function () {
        expect(dom.data('plugin_sortableList')).not.toBeUndefined();
        expect(dom.find('.drag-handle').length).toEqual(initialItems);
    });

    it('items are dynamic', function () {
        expect(dom.data('plugin_dynamicList')).not.toBeUndefined();
        expect(dom.find('.dynamic-delete').length).toEqual((initialItems));
    });

    it('shows actions with mouse over', function () {
        var $row = dom.find('tr:first-child');

        $row.trigger('mouseenter');

        expect($row).toHaveClass('hover');
    });

    it('when item is added, sort is updated', function () {
        var triggered = false;
        dom.on('document.itemUpdated', function () {
            triggered = true;
        });

        var $addButton = dom.find('.dynamic-add');

        $addButton.trigger('click');
        expect(dom.find('.drag-handle').length).toEqual(initialItems + 1);
        expect(dom.find('.dynamic-delete').length).toEqual((initialItems + 1));

        expect(triggered).toBeTruthy();
    });

    it('when item is added and moved, sort is updated', function () {
        var $addButton = dom.find('.dynamic-add');

        $addButton.trigger('click');
        var sortableList = dom.data('plugin_sortableList');
        sortableList.moveUp(4);

        expect(dom.find('tr .position').length).toEqual(4);
        expect(dom.find('tr:nth-child(3) .position')).toHaveValue('2');
    });

    it('when item is deleted, sort is updated', function () {
        var triggered = false;
        dom.on('document.itemUpdated', function () {
            triggered = true;
        });

        var $deleteBtn = dom.find('tr:first-child .dynamic-delete');

        $deleteBtn.trigger('click');
        expect(dom.find('.drag-handle').length).toEqual(initialItems - 1);
        expect(dom.find('.dynamic-delete').length).toEqual(initialItems - 1);

        expect(triggered).toBeTruthy();
    });
});
