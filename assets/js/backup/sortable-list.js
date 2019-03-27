;
(function ($, window, document, undefined) {

    var pluginName = "sortableList",
        defaults = {
            itemSelector: '> li',
            draggable: '> li',
            dragHandleContainer: null,
            sortableWrapper: null,
            hideDragHandle: false
        };

    function Plugin(element, options) {
        this.element = element;

        this.options = $.extend({}, defaults, options);

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype.init = function () {
        var $self = this;

        var $element = $(this.element);
        var $sortable = this.options.sortableWrapper ? $element.find(this.options.sortableWrapper) : $element;

        $element.find(this.options.itemSelector).each(function() {
            var $row = $(this);
            $self.addDragHandle($row);
            $self.handleEvents($row);
        });

        $sortable.sortable({
            items: this.options.itemSelector,
            handle: '.drag-handle',
            placeholder: 'sortable-placeholder',
            update: function() {
                $self.updatePositions();
            }
        });
    };

    Plugin.prototype.handleEvents = function($row) {
        var $self = this;
        $row.on('mouseover', function () {
            $self.refreshUI($row, true);
        });

        $row.on('mouseout', function () {
            $self.refreshUI($row, false);
        });
    };

    Plugin.prototype.refreshUI = function($row, hovered) {
        if(this.options.hideDragHandle) {
            var $dragHandle = $row.find('.drag-handle');
            hovered ? $dragHandle.removeClass('invisible') : $dragHandle.addClass('invisible');
        }
    };

    Plugin.prototype.addDragHandle = function ($row) {
        var $element = $(this.element);

        var $dragHandle = this.buildDragHandle();
        if(this.options.dragHandleContainer) {
            $row.find(this.options.dragHandleContainer).prepend($dragHandle);
        }
        else
            $row.prepend($dragHandle);

        this.handleEvents($row);
        $element.trigger(pluginName + '.dragHandleAdded', $row);
    };


    Plugin.prototype.buildButton = function(type, cls, title) {
        var style = type + 'Style';

        var $button = this.options[style] ? $(this.options[style]) : $('<button></button>').text(title);

        return $button.addClass(cls);
    };


    Plugin.prototype.buildDragHandle = function() {
        var $dragHandle = $('<span />').addClass('drag-handle fa fa-bars');

        if(this.options.dragHandleStyle) {
            $dragHandle = $(this.options.dragHandleStyle).addClass('drag-handle');

            $dragHandle.on('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });

        }

        if(this.options.hideDragHandle) {
            $dragHandle.addClass('invisible');
        }

        return $dragHandle;
    };

    Plugin.prototype.updatePositions = function () {
        var $element = $(this.element);
        $element.find('.position').each(function (i) {
            this.value = i;

            $element.trigger(pluginName + '.itemUpdated', this);
        });

        $element.trigger(pluginName + '.listUpdated');
    };

    Plugin.prototype.moveUp = function (i) {
        var $element = $(this.element);
        $element.find(this.options.itemSelector + ':nth-child(' + i + ')').after($element.find(this.options.itemSelector + ':nth-child(' + (i - 1) + ')'));

        this.updatePositions();
    };

    Plugin.prototype.moveDown = function (i) {
        var $element = $(this.element);
        $element.find(this.options.itemSelector + ':nth-child(' + i + ')').before($element.find(this.options.itemSelector + ':nth-child(' + (i + 1) + ')'));

        this.updatePositions();
    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);