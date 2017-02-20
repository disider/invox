describe('DynamicList', function () {

    beforeEach(function () {
        loadFixtures('dynamic_list.html');
        dom = $('.dynamic');
        plugin = dom.dynamicList({
            itemTemplateId: '#dynamic-list-li'
        });
    });

    it('to be defined', function () {
        expect(dom).toBeDefined();
        expect(plugin).toBeDefined();

        var $rows = dom.find('li');
        expect($rows.length).toEqual(4);
    });

    it('can add a new line', function () {
        var $addRow = dom.find('li:last-child');

        expect($addRow).toHaveHtml('<button class="dynamic-add">Add</button>');
    });

    it('adds a new line', function () {
        var $addBtn = dom.find('.dynamic-add');

        $addBtn.trigger('click');

        var $rows = dom.find('li');
        expect($rows.length).toEqual(5);

        var $newRow = dom.find('li:nth-child(4)');

        expect($newRow).toContainHtml('<input class="title" type="text" name="dynamic_3_title" value=""/>');
        expect($newRow).toContainHtml('<button class="dynamic-delete">Delete</button>');
    });

    it('add event is triggered', function () {
        var $addBtn = dom.find('.dynamic-add');
        var clicked = false;

        dom.on('dynamicList.itemAdded', function() {
            clicked = true;
        });

        $addBtn.trigger('click');

        expect(clicked).toBeTruthy();
    });

    it('add event is triggered when clicking on the row', function () {
        var $row = dom.find('.dynamic-add').parent();
        var clicked = false;

        dom.on('dynamicList.itemAdded', function() {
            clicked = true;
        });

        $row.trigger('click');

        expect(clicked).toBeTruthy();
    });

    it('can delete a line', function () {
        var $row = dom.find('li:first-child');

        expect($row).toContainHtml('<button class="dynamic-delete">Delete</button>');
    });

    it('removes a line', function () {
        var $deleteBtn = dom.find('li:first-child .dynamic-delete');

        $deleteBtn.trigger('click');

        var $rows = dom.find('li');
        expect($rows.length).toEqual(3);
    });

    it('delete event is triggered', function () {
        var $deleteBtn = dom.find('li:first-child .dynamic-delete');
        var clicked = false;

        dom.on('dynamicList.itemDeleted', function() {
            clicked = true;
        });

        $deleteBtn.trigger('click');

        expect(clicked).toBeTruthy();
    });

    it('buttons can be customized', function () {
        var dom = $('.dynamic');
        dom.data('plugin_dynamicList', null);

        var plugin = dom.dynamicList({
            itemTemplateId: '#dynamic-list-li',
            addButtonStyle: '<span class="fa fa-plus" />',
            deleteButtonStyle: '<span class="fa fa-trash" />'
        });

        expect(dom.find('li:last-child')).toContainHtml('<span class="fa fa-plus dynamic-add" />');
        expect(dom.find('li:first-child')).toContainHtml('<span class="fa fa-trash dynamic-delete" />');
    });

    it('delete button can be added to a container', function () {
        var dom = $('.dynamic');
        dom.data('plugin_dynamicList', null);

        var plugin = dom.dynamicList({
            itemTemplateId: '#dynamic-list-li',
            actionsContainer: '.actions'
        });

        var $row = dom.find('li:first-child .actions');
        expect($row).toContainHtml('<button class="dynamic-delete">Delete</button>');
    });
});
