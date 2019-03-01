describe('SortableList', function () {

    beforeEach(function () {
        loadFixtures('sortable_list.html');
        dom = $('.sortable');
        dom.sortableList();

        plugin = dom.data('plugin_sortableList');
    });

    it('to be defined', function () {
        expect(dom).toBeDefined();
        expect(plugin).toBeDefined();

        var $row = dom.find('li');
        var $dragHandler = $row.find('.drag-handle');

        expect($dragHandler).toHaveClass('invisible');
        expect(dom.data('uiSortable')).not.toBeNull();
        expect(dom.data('sortable-item')).not.toBeNull();
    });

    it('shows handle with mouse over', function () {
        var $row = dom.find('li:first-child');
        var $dragHandler = $row.find('.drag-handle');

        $row.trigger('mouseover');

        expect($dragHandler).not.toHaveClass('invisible');
    });

    it('hides actions with mouse out', function () {
        var $row = dom.find('li:first-child');
        var $dragHandler = $row.find('.drag-handle');

        $row.trigger('mouseover');
        $row.trigger('mouseout');

        expect($dragHandler).toHaveClass('invisible');
    });

    it('drag handle added event is triggered', function () {
        var count = 0;

        dom.data('plugin_sortableList', null);

        dom.on('sortableList.dragHandleAdded', function () {
            count++;
        });

        dom.sortableList();

        expect(count).toEqual(3);
    });

    it('when moving item up, items are swapped', function () {
        plugin.moveUp(2);

        expect(dom.find('li:nth-child(1) .title')).toHaveText('Item 2');
        expect(dom.find('li:nth-child(2) .title')).toHaveText('Item 1');
    });

    it('when moving item up and items is first, nothing changed', function () {
        plugin.moveUp(1);

        expect(dom.find('li:nth-child(1) .title')).toHaveText('Item 1');
        expect(dom.find('li:nth-child(2) .title')).toHaveText('Item 2');
    });

    it('when moving item down, items are swapped', function () {
        plugin.moveDown(1);

        expect(dom.find('li:nth-child(1) .title')).toHaveText('Item 2');
        expect(dom.find('li:nth-child(2) .title')).toHaveText('Item 1');
    });

    it('item updated event is triggered', function () {
        var count = 0;

        dom.on('sortableList.itemUpdated', function () {
            count++;
        });

        plugin.moveUp(3);
        plugin.moveDown(1);

        expect(count).toEqual(6);
    });

    it('list updated event is triggered', function () {
        var triggered = false;

        dom.on('sortableList.listUpdated', function () {
            triggered = true
        });

        plugin.moveUp(3);
        plugin.moveDown(1);

        expect(triggered).toBeTruthy();
    });
});
