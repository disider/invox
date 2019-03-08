describe('FileInput', function () {
    var dom;
    var plugin;
    var $wrapper;
    var $element;
    var $input;
    var $controls;
    var $toggle;
    var $preview;
    var $deleteCheckbox;

    beforeEach(function () {
        loadFixtures('file_input.html');
        dom = $('#file-inputs li:first-child');
        $element = dom.find('.file-image');

        $element.fileInput();

        plugin = $element.data('plugin_fileInput');

        $input = dom.find('.file-input');
        $wrapper = dom.find('.file-input-wrapper');
        $controls = dom.find('.file-input-controls');
        $toggle = dom.find('.file-input-toggle');
        $preview = dom.find('.file-input-preview');
        $deleteCheckbox = dom.find('.file-input-delete-check');

        // Disable click on file input for testing purposes
        $input.on('click', function (ev) {
            ev.preventDefault();
            ev.stopPropagation();
        });
    });

    it('to be defined', function () {
        expect(dom).toBeDefined();
        expect(plugin).toBeDefined();

        expect($controls).toHaveClass('hidden');
        expect($element).toHaveClass('hidden');
        expect($input).toHaveClass('file-input');

        expect(dom).toContainHtml('<button class="file-input-toggle">Toggle</button>');
        expect($controls).toContainHtml('<button class="file-input-change">Change</button>');
        expect($controls).toContainHtml('<button class="file-input-delete">Delete</button>');
        expect($deleteCheckbox).not.toBeChecked();
    });

    it('buttons can be customized', function () {
        loadFixtures('file_input.html');
        dom = $('#file-inputs li:first-child');
        $input = dom.find('input[type="file"]');

        $input.fileInput({
            toggleButtonStyle: '<span class="fa fa-image" />',
            changeButtonStyle: '<span class="fa fa-refresh" />',
            deleteButtonStyle: '<span class="fa fa-trash" />'
        });

        expect(dom).toContainHtml('<span class="fa fa-image file-input-toggle" />');
        expect(dom).toContainHtml('<span class="fa fa-refresh file-input-change" />');
        expect(dom).toContainHtml('<span class="fa fa-trash file-input-delete" />');
    });

    it('when toggle is clicked and preview is empty, controls are not visible', function () {
        $preview.text('');
        $toggle.trigger('click');

        expect($wrapper).not.toHaveClass('editing');
        expect($controls).toHaveClass('hidden');
    });

    it('when toggle is clicked and preview is not empty, controls are visible', function () {
        $preview.text('./test.png');
        $toggle.trigger('click');

        expect($wrapper).toHaveClass('editing');
        expect($controls).not.toHaveClass('hidden');
        expect($preview).not.toHaveClass('hidden');
        expect($controls.find('.file-input-change')).not.toHaveClass('hidden');
        expect($controls.find('.file-input-delete')).not.toHaveClass('hidden');
    });

    it('when toggle is clicked, showing controls event is triggered', function () {
        var triggered = false;
        $element.on('fileInput.showingControls', function () {
            triggered = true;
        });
        $toggle.trigger('click');

        expect(triggered).toBeTruthy();
    });

    it('when toggle is clicked, clicked event is triggered', function () {
        var triggered = false;
        $element.on('fileInput.clicked', function () {
            triggered = true;
        });
        $toggle.trigger('click');

        expect(triggered).toBeTruthy();
    });

    it('when toggle is clicked and preview is empty, file selection is triggered', function () {
        $preview.text('');
        var triggered = false;
        $element.on('fileInput.selectingFile', function () {
            triggered = true;
        });
        $toggle.trigger('click');

        expect(triggered).toBeTruthy();
    });

    it('when toggle is clicked and preview is not empty, file selection is not triggered', function () {
        $preview.text('test.png');

        var triggered = false;
        $element.on('file_input.selectingFile', function () {
            triggered = true;
        });
        $toggle.trigger('click');

        expect(triggered).toBeFalsy();
    });

    it('when toggle is clicked twice and preview is not empty, controls are hidden', function () {
        $preview.text('test.png');

        $toggle.trigger('click');
        $toggle.trigger('click');

        expect($controls).toHaveClass('hidden');
    });

    it('when input is selected, toggle is active', function () {
        $preview.text('test.png');
        $preview.trigger('change');

        expect($toggle).toHaveClass('active');
    });

    it('when input has preview, toggle is active', function () {
        loadFixtures('file_input.html');
        var dom = $('#file-inputs li:nth-child(2)');

        dom.find('.file-image').fileInput();

        var $toggle = dom.find('.file-input-toggle');

        expect($toggle).toHaveClass('active');
    });

    it('when preview type is image and input changes, toggle is active', function () {
        loadFixtures('file_input.html');
        dom = $('#file-inputs li:nth-child(1)');
        $input = dom.find('input[type="file"]').fileInput({
            previewType: 'image'
        });
        $toggle = dom.find('.file-input-toggle');

        var plugin = $input.data('plugin_fileInput');

        plugin.loadPreviewImage('test.png');

        expect($toggle).toHaveClass('active');
    });

    it('when input is selected, controls are not visible', function () {
        $toggle.trigger('click');

        $preview.text('test.png');
        $preview.trigger('change');

        expect($controls).toHaveClass('hidden');
    });

    it('when input is changed, selecting_file event is triggered', function () {
        $toggle.trigger('click');

        $input.trigger('change');

        var triggered = false;
        $element.on('fileInput.selectingFile', function () {
            triggered = true;
        });

        var $changeBtn = $controls.find('.file-input-change');
        $changeBtn.trigger('click');

        expect(triggered).toBeTruthy();
        expect($deleteCheckbox).toHaveValue('');
    });

    it('when input is deleted, toggle is not active, controls are hidden and preview is empty', function () {
        $preview.text('test.png');
        $preview.trigger('change');

        $toggle.trigger('click');

        var $deleteBtn = $controls.find('.file-input-delete');
        $deleteBtn.trigger('click');

        expect($toggle).not.toHaveClass('active');
        expect($controls).toHaveClass('hidden');
        expect($preview).toHaveText('');
        expect($deleteCheckbox).toBeChecked();
    });

    it('when controls type is popup, preview position is calculated', function () {
        loadFixtures('file_input.html');
        var dom = $('#file-inputs li:nth-child(2)');
        var $element = dom.find('.file-image');

        $element.fileInput({
            controlsType: 'popup'
        });

        var $toggle = dom.find('.file-input-toggle');
        var $wrapper = dom.find('.file-input-wrapper');
        var $controls = dom.find('.file-input-controls');

        $toggle.trigger('click');

        var left = ($wrapper.width() - $controls.width()) / 2;
        var top = -$controls.height() - 3; // Arrow height

        expect($wrapper).toHaveCss({'position': 'relative'});
        expect($controls).toHaveCss({'position': 'absolute', 'left': left + 'px', 'top': top + 'px'});
    });

    it('when preview type is image and input is empty, toggler is not active', function () {
        loadFixtures('file_input.html');
        var dom = $('#file-inputs li:nth-child(1)');
        var $element = dom.find('.file-image');

        $element.fileInput({
            previewType: 'image'
        });

        var $toggle = dom.find('.file-input-toggle');

        expect($toggle).not.toHaveClass('active');
    });

    it('when preview type is image, preview has valid source', function () {
        loadFixtures('file_input.html');
        var dom = $('#file-inputs li:nth-child(2)');
        var $element = dom.find('.file-image');

        $element.fileInput({
            previewType: 'image'
        });

        var $preview = dom.find('.file-input-preview');

        expect($preview).toHaveAttr('src', './test.png');
    });

});