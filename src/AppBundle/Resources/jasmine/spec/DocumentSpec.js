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

    //it('removes initial view', function () {
    //    loadFixtures('document.html');
    //    var dom = $('#checklist-template ul');
    //
    //    expect(dom).toHaveClass('initial-view');
    //
    //    dom.document();
    //
    //    expect(dom).not.toHaveClass('initial-view');
    //});

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
    ////
    ////it('when editing, actions are not hidden', function() {
    ////    var $li = dom.find('li:first-child');
    ////    var $taskActions = $li.find('.task-actions');
    ////
    ////    $li.trigger('mouseenter');
    ////
    ////    var $titleToggle = $li.find('.editable-toggle').eq(0);
    ////    $titleToggle.trigger('click');
    ////
    ////    $li.trigger('mouseleave');
    ////
    ////    expect($taskActions).not.toHaveClass('invisible');
    ////});
    //
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

    //it('when item is added, title has focus', function () {
    //    var $addButton = dom.find('.dynamic-add');
    //
    //    $addButton.trigger('click');
    //    var $input = dom.find('tr:nth-child(4) .title');
    //
    //    expect($input).toBeFocused();
    //});
    //
    //it('when task is added and focus is lost, task is removed', function () {
    //    var $addButton = dom.find('.dynamic-add');
    //    var $toggle = dom.find('> li:first-child .editable-toggle');
    //
    //    $addButton.trigger('click');
    //    expect(dom.find('li').length).toEqual(5);
    //
    //    $toggle.trigger('click');
    //
    //    expect(dom.find('li').length).toEqual(4);
    //});
    //
    //it('when task is added, task actions are hidden', function () {
    //    var $addButton = dom.find('.dynamic-add');
    //    var $toggle = dom.find('li:first-child .editable-toggle');
    //
    //    $addButton.trigger('click');
    //
    //    expect(dom.find('.task-actions')).toHaveClass('hidden');
    //});
    //
    //it('when task is added and title is set, task actions are visible', function () {
    //    var $addButton = dom.find('.dynamic-add');
    //    var $toggle = dom.find('li:first-child .editable-toggle');
    //
    //    $addButton.trigger('click');
    //    var $input = dom.find('li:nth-child(4) .title');
    //    $input.val('Task 4');
    //
    //    var $saveButton = dom.find('li:nth-child(4) .editable-save').eq(0);
    //    $saveButton.trigger('click');
    //
    //    expect(dom.find('.task-actions')).not.toHaveClass('hidden');
    //});
    //
    //it('when task is added and title is set, and focus is lost, task is kept', function () {
    //    var $addButton = dom.find('.dynamic-add');
    //    var $toggle = dom.find('li:first-child .editable-toggle');
    //
    //    $addButton.trigger('click');
    //    var $input = dom.find('li:nth-child(4) .title');
    //    $input.val('Task 4');
    //
    //    $toggle.trigger('click');
    //
    //    expect(dom.find('li').length).toEqual(5);
    //});

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
    //
    //it('when row is clicked, title is edited', function () {
    //    var $row = dom.find('li:first-child');
    //    var $editableControls = $row.find('.editable-controls:first');
    //    expect($editableControls).toHaveClass('hidden');
    //
    //    $row.trigger('click');
    //
    //    expect($editableControls).not.toHaveClass('hidden');
    //});
    //
    //it('when CTRL+DOWN is pressed on title, row is moved down', function () {
    //    var $row = dom.find('> li:first-child');
    //    var $input = $row.find('.editable');
    //
    //    // trigger CTRL+DOWN
    //    $input.trigger($.Event('editable.keyPressed', {metaKey: true, which: 40}));
    //
    //    expect(dom.find('li:nth-child(1) .title')).toHaveValue('Task #2');
    //    expect(dom.find('li:nth-child(2) .title')).toHaveValue('Task #1');
    //});
    //
    //it('when CTRL+UP is pressed on title, row is moved up', function () {
    //    var $row = dom.find('> li:nth-child(2)');
    //    var $input = $row.find('.editable');
    //
    //    // trigger CTRL+UP
    //    $input.trigger($.Event('editable.keyPressed', {metaKey: true, which: 38}));
    //
    //    expect(dom.find('li:nth-child(1) .title')).toHaveValue('Task #2');
    //    expect(dom.find('li:nth-child(2) .title')).toHaveValue('Task #1');
    //});
    //
    //// The following cannot be tested because :hover cannot be set programmatically
    ////it('when row is hovered and item is moved, UI is refreshed', function() {
    ////    var $firstRow = dom.find('li:nth-child(1)');
    ////    var $secondRow = dom.find('li:nth-child(2)');
    ////
    ////    $firstRow.dynamic-addClass('hover');
    ////    var $input = $secondRow.find('.editable');
    ////
    ////    // trigger CTRL+UP
    ////    $input.trigger($.Event('editable.key_pressed', {metaKey: true, which: 38}));
    ////});

});
