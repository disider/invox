describe('Editable', function () {
    var dom;
    var plugin;
    var $li;
    var $toggle;
    var $placeholder;
    var $controls;
    var $input;

    function expectTitle($input, $placeholder, val) {
        expect($input).toHaveValue(val);
        expect($placeholder).toHaveValue(val);
    }

    beforeEach(function () {
        loadFixtures('editable.html');
        dom = $('#items');
        dom.find('input').editable();

        plugin = dom.find('input').data('plugin_editable');

        $row = dom.find('li:first-child');
        $input = $row.find('.editable');
        $toggle = $row.find('.editable-toggle');
        $placeholder = $row.find('.editable-placeholder');
        $controls = $row.find('.editable-controls');
    });

    it('to be defined', function () {
        expect(dom).toBeDefined();
        expect(plugin).toBeDefined();

        expect($toggle).toHaveClass('active');
    });

    it('buttons can be customized', function () {
        loadFixtures('editable.html');
        var dom = $('#items');
        var $input = dom.find('li:first-child input');

        $input.editable({
            toggleButtonStyle: '<span class="fa fa-bars" />',
            saveButtonStyle: '<span class="fa fa-check" />',
            cancelButtonStyle: '<span class="fa fa-close" />'
        });

        expect(dom).toContainHtml('<span class="fa fa-bars editable-toggle active" />');
        expect(dom).toContainHtml('<span class="fa fa-check editable-save" />');
        expect(dom).toContainHtml('<span class="fa fa-close editable-cancel" />');
    });

    it ('input toggle is shown', function () {
        expect($row).not.toHaveClass('editing');
        expect($toggle).toEqual('button');
        expect($toggle).not.toHaveClass('hidden');
        expect($controls).toHaveClass('hidden');
    });

    it('shows a preview instead of a toggle', function () {
        loadFixtures('editable.html');
        var dom = $('#items');
        var $row = dom.find('li:first-child');
        var $input = $row.find('input');

        $input.editable({
            toggleType: 'preview'
        });
        var $toggle = $row.find('.editable-toggle');

        expect(dom).toContainHtml('<span class="editable-toggle active">' + $input.val() + '</span>');

        expect($toggle).toHaveText($input.val());
    });

    it('when toggle type is preview, can be triggered', function () {
        loadFixtures('editable.html');
        var dom = $('#items li:first-child');
        var $input = dom.find('input').editable({
            toggleType: 'preview'
        });

        var $toggle = dom.find('.editable-toggle');
        $toggle.trigger('click');

        var $controls = dom.find('.editable-controls');
        expect($controls).not.toHaveClass('hidden');
    });

    it ('when toggle is clicked, title is shown and focused', function () {
        $toggle.trigger('click');

        expect($row.find('.editable-wrapper')).toHaveClass('editing');
        expect($toggle).toHaveClass('hidden');
        expect($controls).not.toHaveClass('hidden');
        expect($input).toBeFocused();
    });

    it ('when toggle is clicked, shown event is triggerd', function () {
        $toggle.trigger('click');

        expect($row.find('.editable-wrapper')).toHaveClass('editing');
        expect($toggle).toHaveClass('hidden');
        expect($controls).not.toHaveClass('hidden');
        expect($input).toBeFocused();
    });

    it ('when title editing is canceled, restore toggle', function () {
        var $cancelBtn = $controls.find('.editable-cancel');

        var originalTitle = $placeholder.val();

        $toggle.trigger('click');
        $input.val('new title');
        $cancelBtn.trigger('click');

        expect($toggle).not.toHaveClass('hidden');
        expect($controls).toHaveClass('hidden');

        expectTitle($input, $placeholder, originalTitle);
    });

    it ('when editing title and ESC is pressed, restore toggle', function () {
        var originalTitle = $placeholder.val();

        $toggle.trigger('click');
        $input.val('new title');

        var e = $.Event('keydown', { which: 27});
        $input.trigger(e);

        expect($toggle).not.toHaveClass('hidden');
        expect($controls).toHaveClass('hidden');

        expectTitle($input, $placeholder, originalTitle);
    });

    it ('when title is saved, update toggle and keep text', function () {
        var $saveBtn = $controls.find('.editable-save');

        var newTitle = 'new title';

        $toggle.trigger('click');
        $input.val(newTitle);
        $saveBtn.trigger('click');

        expect($toggle).not.toHaveClass('hidden');
        expect($controls).toHaveClass('hidden');

        expectTitle($input, $placeholder, newTitle);
    });

    it ('when editing title and ENTER is pressed, update toggle and keep text', function () {
        var newTitle = 'new title';

        $toggle.trigger('click');
        $input.val(newTitle);

        var e = $.Event('keydown', { which: 13});
        $input.trigger(e);

        expect($toggle).not.toHaveClass('hidden');
        expect($controls).toHaveClass('hidden');

        expectTitle($input, $placeholder, newTitle);
    });

    it ('when key is pressed, key pressed event is triggered', function () {
        var triggered = false;
        dom.on('editable.keyPressed', function() {
            triggered = true;
        });

        $input.trigger('keydown', { which: 13});

        expect(triggered).toBeTruthy();
    });

    it('toggle is hidden when editing', function () {
        $toggle.trigger('click');

        expect($toggle).toHaveClass('hidden');
    });

    it('when toggle is clicked twice, controls are hidden', function () {
        $toggle.trigger('click');
        $toggle.trigger('click');

        expect($controls).toHaveClass('hidden');
    });

    it('when input is saved empty, toggle is not active', function () {
        $toggle.trigger('click');
        $input.val('');

        var $saveBtn = $controls.find('.editable-save');
        $saveBtn.trigger('click');

        expect($controls).toHaveClass('hidden');
        expect($toggle).not.toHaveClass('active');
    });

    it('when input is not saved, toggle is not active', function () {
        $toggle.removeClass('active');
        $placeholder.val('');

        $toggle.trigger('click');
        $input.val('12345678');

        var $cancelBtn = $controls.find('.editable-cancel');
        $cancelBtn.trigger('click');

        expect($controls).toHaveClass('hidden');
        expect($toggle).not.toHaveClass('active');
        expect($input).toHaveValue('');
    });

    it('when input is changed and toggle is clicked, value is restored', function () {
        $toggle.removeClass('active');
        $placeholder.val('');

        $toggle.trigger('click');
        $input.val('12345678');

        $toggle.trigger('click');

        expect($controls).toHaveClass('hidden');
        expect($toggle).not.toHaveClass('active');
        expect($input).toHaveValue('');
    });

    it('when input is not saved and not empty, toggle is active and value restored', function () {
        $toggle.addClass('active');
        $placeholder.val('12345678');

        $toggle.trigger('click');

        var $cancelBtn = $controls.find('.editable-cancel');
        $cancelBtn.trigger('click');

        expect($controls).toHaveClass('hidden');
        expect($toggle).toHaveClass('active');
        expect($input).toHaveValue('12345678');
    });

    describe('with two items', function() {
        var $firstRow;
        var $secondRow;

        var $firstInput;
        var $secondInput;

        var $firstPlaceholder;
        var $secondPlaceholder;

        var $firstToggle;
        var $secondToggle;

        beforeEach(function() {
            $firstRow = dom.find('li:nth-child(1)');
            $secondRow = dom.find('li:nth-child(2)');

            $firstActions = $firstRow.find('.editable-controls');
            $secondActions = $secondRow.find('.editable-controls');

            $firstPlaceholder = $firstRow.find('.editable-placeholder');
            $secondPlaceholder = $secondRow.find('.editable-placeholder');

            $firstToggle = $firstRow.find('.editable-toggle');
            $secondToggle = $secondRow.find('.editable-toggle');
        });

        it ('when editing a title, and focus is lost, first toggle is restored', function () {
            $firstToggle.trigger('click');
            $secondToggle.trigger('click');

            expect($firstActions).toHaveClass('hidden');
            expect($secondActions).not.toHaveClass('hidden');
        });

        it ('when editing, trigger showingControls event', function () {
            var triggered = false;
            $firstRow.on('editable.showingControls', function() {
                triggered = true;
            });

            $firstToggle.trigger('click');

            expect(triggered).toBeTruthy();
        });

        it ('when editing an empty title, and focus is lost, keep text and trigger input_changed event', function () {
            var triggered = false;
            $firstRow.on('editable.inputChanged', function() {
                triggered = true;
            });

            var $firstInput = $firstRow.find('.editable');
            $firstPlaceholder.val('');
            $firstInput.val('');

            var newTitle = 'new title';

            $firstToggle.trigger('click');
            $firstInput.val(newTitle);

            $secondToggle.trigger('click');

            expectTitle($firstInput, $firstPlaceholder, newTitle);

            expect(triggered).toBeTruthy();
        });

        it ('when editing an empty title with an empty title, and focus is lost, do not trigger input_changed event', function () {
            var triggered = false;
            $firstRow.on('editable.inputChanged', function() {
                triggered = true;
            });

            var $firstInput = $firstRow.find('.editable');
            $firstPlaceholder.val('');
            $firstInput.val('');

            $firstPlaceholder.trigger('click');
            $secondPlaceholder.trigger('click');

            expect(triggered).toBeFalsy();
        });

        it ('focus_lost event is triggered', function () {
            var triggered = false;
            dom.on('editable.focusLost', function() {
                triggered = true;
            });

            $firstToggle.trigger('click');
            $secondToggle.trigger('click');

            expect(triggered).toBeTruthy();
        });
    })

    describe('with toggle always shown', function () {
        var dom;
        var $li;
        var $toggle;
        var $placeholder;
        var $wrapper;
        var $controls;
        var $input;

        beforeEach(function () {
            loadFixtures('editable.html');
            dom = $('#items');
            $row = dom.find('li:first-child');
            $input = $row.find('input');

            $input.editable({
                showToggle: 'always'
            });

            $toggle = $row.find('.editable-toggle');
            $placeholder = $row.find('.editable-placeholder');
            $controls = $row.find('.editable-controls');
            $wrapper = $row.find('.editable-wrapper');
        });

        it('toggle is visible when editing', function () {
            expect($toggle).not.toHaveClass('hidden');
            expect($controls).toHaveClass('hidden');
            expect($wrapper).not.toHaveClass('editing');
        });

        it('when input is saved, toggle is active', function () {
            $toggle.trigger('click');
            $input.val('12345678');

            var $saveBtn = $controls.find('.editable-save');
            $saveBtn.trigger('click');

            expect($controls).toHaveClass('hidden');
            expect($toggle).toHaveClass('active');

            expectTitle($input, $placeholder, '12345678');
        });
    });

    it('when controls type is popup, controls position is calculated', function () {
        loadFixtures('editable.html');
        dom = $('#items');
        $row = dom.find('li:first-child');
        $input = $row.find('input');

        $input.editable({
            controlsType: 'popup'
        });

        var $toggle = dom.find('.editable-toggle');
        var $wrapper = dom.find('.editable-wrapper');
        var $controls = dom.find('.editable-controls');

        $toggle.trigger('click');

        var left = ($wrapper.width() - $controls.width())/2;
        var top = -($controls.height() + 3);

        expect($wrapper).toHaveCss({'position': 'relative'});
        expect($controls).toHaveCss({'position': 'absolute', 'left': left + 'px', 'top': top + 'px'});
    });

});
