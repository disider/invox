;
(function ($, window, document, undefined) {

    var pluginName = "fileInput",
        defaults = {
            controlsType: 'popup', // values: inline|popup
            controlsClass: '',
            previewType: 'text', // values: text|image
            toggleButtonStyle: null,
            changeButtonStyle: null,
            deleteButtonStyle: null,
            arrowHeight: 3
        };

    function Plugin(element, options) {
        this.$element = $(element);

        this.options = $.extend({}, defaults, options);

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype.init = function () {
        var $self = this;
        var $element = this.$element;

        var $wrapper = $('<div></div>').addClass('file-input-wrapper');

        $element.wrap($wrapper);
        this.$wrapper = $element.parent();

        this.$input = $element.find('input[type=file]');
        this.$deleteCheckbox = $element.find('input[type=checkbox]');

        $element.addClass('file-input-control hidden');
        this.$input.addClass('file-input');
        this.$deleteCheckbox.addClass('file-input-delete-check');

        this.$controls = $('<div></div>').addClass('file-input-controls hidden ' + this.options.controlsClass).appendTo(this.$wrapper);
        if (this.options.controlsType == 'popup') {
            this.$controls.css('position', 'absolute');
            this.$wrapper.css('position', 'relative');
        }

        this.$preview = this.buildPreview();

        this.$toggleBtn = this.buildButton('toggle', 'file-input-toggle', 'Toggle', this.$wrapper, 'toggleControls');
        this.$changeBtn = this.buildButton('change', 'file-input-change', 'Change', this.$controls, 'changeMedia');
        this.$deleteBtn = this.buildButton('delete', 'file-input-delete', 'Delete', this.$controls, 'deleteMedia');

        if (this.hasPreview())
            this.$toggleBtn.addClass('active');

        this.$input.on('click', function () {
            $element.trigger(pluginName + '.selectingFile');
        });

        this.$input.on('change', function () {
            $self.updatePreview(this.value, this.files[0]);
        });
    };

    Plugin.prototype.toggleControls = function () {
        if (!this.hasPreview()) {
            this.$element.trigger(pluginName + '.showingControls');
            this.$input.trigger('click');
        }
        else {
            if(this.$controls.hasClass('hidden')) {
                this.showControls();
            }
            else {
                this.hideControls();
            }
        }

        this.$element.trigger(pluginName + '.clicked');
    }

    Plugin.prototype.showControls = function() {
        this.$element.trigger(pluginName + '.showingControls');

        this.$wrapper.addClass('editing');
        this.$controls.removeClass('hidden');
        this.refreshControlsUI();
    }

    Plugin.prototype.hideControls = function() {
        this.$wrapper.removeClass('editing');
        this.$controls.addClass('hidden');
    }

    Plugin.prototype.hasPreview = function () {
        if (this.isImagePreview())
            return this.$preview.attr('src') != null;
        else
            return this.$preview.text() != '';
    }

    Plugin.prototype.isImagePreview = function () {
        return this.options.previewType == 'image';
    }

    Plugin.prototype.changeMedia = function () {
        this.$input.trigger('click');
    }

    Plugin.prototype.deleteMedia = function () {
        this.$input.val('');
        this.resetPreview();
        this.$toggleBtn.removeClass('active');
        this.$controls.addClass('hidden');
        this.$deleteCheckbox.prop('checked', true);
    }

    Plugin.prototype.resetPreview = function () {
        if (this.isImagePreview())
            this.$preview.attr('src', '');
        else
            this.$preview.text('');
    }

    Plugin.prototype.buildButton = function (type, cls, title, $container, callback) {
        var $self = this;
        var style = type + 'ButtonStyle';

        var $button = this.options[style] ? $(this.options[style]) : $('<button></button>').text(title);

        $button.on('click', function (ev) {
            $self[callback]();

            ev.preventDefault();
            ev.stopPropagation();
        });

        return $button.addClass(cls).appendTo($container);
    };

    Plugin.prototype.buildPreview = function () {
        var $self = this;
        var $preview = null;

        if (this.isImagePreview()) {
            $preview = $('<img />')
                .addClass('file-input-preview')
                .attr('src', this.$input.data('preview'))
                .appendTo(this.$controls);
        }
        else {
            $preview = $('<a />')
                .addClass('file-input-preview')
                .attr('href', '#')
                .text(this.$input.data('preview'))
                .appendTo(this.$controls);
        }

        $preview.on('change', function () {
            $self.onPreviewChanged();
        });

        return $preview;
    }

    Plugin.prototype.onPreviewChanged = function () {
        if (this.hasPreview())
            this.$toggleBtn.addClass('active');
        else
            this.$toggleBtn.removeClass('active');

        this.$controls.addClass('hidden');
    }

    Plugin.prototype.refreshControlsUI = function () {
        if (this.options.controlsType == 'popup') {
            var left = (this.$wrapper.width() - this.$controls.width()) / 2;
            var top = -this.$controls.height() - this.options.arrowHeight;

            this.$controls.css('left', left);
            this.$controls.css('top', top);
        }
    };

    Plugin.prototype.updatePreview = function (fileName, file) {
        if (this.isImagePreview())
            this.loadFile(fileName, file);
        else {
            this.$preview.text(fileName);
            this.$preview.trigger('change');
        }
    };

    Plugin.prototype.loadFile = function (fileName, file) {
        var $self = this;

        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
        if (regex.test(fileName.toLowerCase())) {
            //if ($.browser.msie && parseFloat(jQuery.browser.version) <= 9.0) {
            //    this.$preview.get(0).filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = fileName;
            //}
            //else {
            if (typeof (FileReader) != "undefined") {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $self.loadPreviewImage(e.target.result);
                }
                reader.readAsDataURL(file);
            } else {
                alert("This browser does not support FileReader.");
            }
            //}
        } else {
            alert("Please upload a valid image file: " + fileName);
        }
    }

    Plugin.prototype.loadPreviewImage = function (image) {
        this.$preview.attr('src', image);
        this.$preview.trigger('change');
    }

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    }

    $[pluginName] = Plugin;

})(jQuery, window, document);