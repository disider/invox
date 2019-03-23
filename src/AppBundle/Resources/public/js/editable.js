;
(function ($, window, document, undefined) {

    var pluginName = "editable",
        defaults = {
            controlsType: 'inline', // values: inline|popup
            toggleType: 'toggle', // values: toggle|preview
            showToggle: 'whenNotEditing', // values: always, notEditingOnly, never
            controlsClass: '',
            toggleButtonStyle: null,
            saveButtonStyle: null,
            cancelButtonStyle: null
        };

    function Plugin(element, options) {
        this.element = element;
        this.$element = $(this.element);

        this.options = $.extend({}, defaults, options);

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype.init = function () {
        var $self = this;
        var $wrapper = $('<div></div>').addClass('editable-wrapper');
        var $controls = $('<div></div>').addClass('editable-controls hidden ' + this.options.controlsClass);

        this.$element.wrap($controls);
        this.$element.addClass('editable');

        this.$controls = this.$element.parent();

        this.$controls.wrap($wrapper);
        this.$wrapper = this.$controls.parent();

        if (this.options.controlsType == 'popup') {
            this.$controls.css('position', 'absolute');
            this.$wrapper.css('position', 'relative');
        }

        this.$saveBtn = this.buildButton('save', 'editable-save', 'Save', this.$controls, 'save');
        this.$cancelBtn = this.buildButton('cancel', 'editable-cancel', 'Cancel', this.$controls, 'cancel');
        this.$toggleBtn = this.buildToggle(this.$element.val(), this.$wrapper, 'toggleControls');
        this.$placeholder = this.buildPlaceholder(this.$element.val(), this.$wrapper);

        if (this.$element.val())
            this.$toggleBtn.addClass('active');

        this.$element.on(pluginName + '.focusChanged', function () {
            $self.removeFocus();
        });

        this.$element.on('keydown', function (ev) {
            $self.handleKeyDown(ev);
        });
    };

    Plugin.prototype.handleKeyDown = function(ev) {
        this.$element.trigger($.Event(pluginName + '.keyPressed', {which: ev.which, metaKey: ev.metaKey, ctrlKey: ev.ctrlKey}));

        if (ev.which == 27) {
            this.$cancelBtn.trigger('click');
            ev.preventDefault();
            ev.stopPropagation();
        }

        if (ev.which == 13) {
            this.$saveBtn.trigger('click');
            ev.preventDefault();
            ev.stopPropagation();
        }

        // Prevent default for arrows,
        if(ev.metaKey && [38, 40].indexOf(ev.which) != -1) {
            ev.preventDefault();
            ev.stopPropagation();
        }
    }

    Plugin.prototype.removeFocus = function() {
        if (this.$wrapper.hasClass('editing')) {
            if (!this.$placeholder.val() && this.$element.val())
                this.$saveBtn.trigger('click');
            else
                this.$cancelBtn.trigger('click');
        }
    }

    Plugin.prototype.save = function() {
        this.$toggleBtn.removeClass('hidden');
        this.$controls.addClass('hidden');
        this.$controls.parent().removeClass('editing');

        if(this.options.toggleType == 'preview') {
            this.$toggleBtn.text(this.$element.val());
        }
        this.$placeholder.val(this.$element.val());

        this.$element.val() ? this.$toggleBtn.addClass('active') : this.$toggleBtn.removeClass('active');

        this.$element.trigger(pluginName + '.inputChanged');
    };

    Plugin.prototype.cancel = function() {
        this.$toggleBtn.removeClass('hidden');
        this.$controls.addClass('hidden');
        this.$controls.parent().removeClass('editing');

        if(this.options.toggleType == 'preview') {
            this.$toggleBtn.text(this.$placeholder.val());
        }
        this.$element.val(this.$placeholder.val());

        this.$element.trigger(pluginName + '.focusLost', this.$element);
    };

    Plugin.prototype.toggleControls = function() {
        if (!this.$wrapper.hasClass('editing')) {
            this.$element.trigger(pluginName + '.showingControls');

            // This event must be triggered before changing current object
            // in order to close all the others.
            $('.editable').trigger(pluginName + '.focusChanged');

            if(this.options.showToggle != 'always') this.$toggleBtn.addClass('hidden');

            this.$controls.removeClass('hidden');
            this.$wrapper.addClass('editing');
            this.$element.focus();

            this.refreshControlsUI();
        }
        else {
            if(this.options.showToggle != 'never') this.$toggleBtn.removeClass('hidden');

            this.$cancelBtn.trigger('click');
        }
    };

    Plugin.prototype.buildButton = function (type, cls, title, $container, callback) {
        var $self = this;
        var style = type + 'ButtonStyle';

        var $button = this.options[style] ? $(this.options[style]) : $('<button></button>').text(title);

        $button.on('click', function(ev) {
            $self[callback]();

            ev.preventDefault();
            ev.stopPropagation();
        });

        return $button.addClass(cls).appendTo($container);
    };

    Plugin.prototype.buildToggle = function(val, $container, callback) {
        var $self = this;

        if(this.options.toggleType == 'preview') {
            var $preview = $('<span/>').text(val).addClass('editable-toggle').insertAfter($container);

            $preview.on('click', function(ev) {
                $self[callback]();

                ev.preventDefault();
                ev.stopPropagation();
            });

            return $preview;
        }
        else {
            return this.buildButton('toggle', 'editable-toggle', 'Toggle', $container, callback);
        }
    }

    Plugin.prototype.refreshControlsUI = function () {
        if (this.options.controlsType == 'popup') {
            var left = (this.$wrapper.width() - this.$controls.width()) / 2;
            var top = -(this.$controls.height() + 3);

            this.$controls.css('left', left);
            this.$controls.css('top', top);
        }
    };

    Plugin.prototype.buildPlaceholder = function(val, $container) {
        return $('<input />')
            .attr('type', 'hidden')
            .val(val)
            .addClass('editable-placeholder')
            .appendTo($container);
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