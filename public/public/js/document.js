;
(function ($, window, document, undefined) {

    var pluginName = "document",
        defaults = {
            addButtonStyle: null,
            deleteButtonStyle: null,
            dragHandleStyle: null
        };

    function Plugin(element, options) {
        this.element = element;
        this.$self = this;
        this.$element = $(this.element);

        this.options = $.extend({}, defaults, options);

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype.init = function () {
        var $self = this.$self;
        var $element = this.$element;

        $element.find('.document-row').each(function () {
            $self.handleEvents($(this));
        });

        $element.sortableList({
            itemSelector: '.document-row',
            dragHandleContainer: '.actions',
            sortableWrapper: 'tbody',
            dragHandleStyle: this.options.dragHandleStyle
        });

        $element.dynamicList({
            itemTemplateId: '#row-template',
            itemSelector: '.document-row',
            addButtonStyle: this.options.addButtonStyle,
            deleteButtonStyle: this.options.deleteButtonStyle,
            actionsContainer: '.actions',
            rowTemplate: '<tr></tr>',
            addNewRowTemplate: '<td colspan="2"></td>'
        });

        var sortableList = $element.data('plugin_sortableList');

        $element.on('sortableList.listUpdated', function () {
            $element.find('.document-row').each(function () {
                $row = $(this);
                var hovered = $row.is(':hover');
                sortableList.refreshUI($row, hovered);

                $self.refreshUI($row, hovered);
            });
        });

        $element.on('dynamicList.itemAdded', function (event, row) {
            var $row = $(row);
            $row.addClass('document-row');
            sortableList.addDragHandle($row);
            sortableList.updatePositions();

            $self.handleEvents($row);

            $element.trigger(pluginName + '.itemUpdated', row);
        });

        $element.on('dynamicList.itemDeleted', function (event, row) {
            sortableList.updatePositions();

            $element.trigger(pluginName + '.itemUpdated', row);
        });

        $element.on(pluginName + '.movingRow', function (ev) {
            (ev.direction == 'up') ? sortableList.moveUp(ev.position): sortableList.moveDown(ev.position);
        });

        $element.removeClass('initial-view');
    };

    Plugin.prototype.handleEvents = function ($row) {
        var $self = this.$self;

        $row.find('.position').addClass('hidden');

        $row.on('mouseenter', function () {
            $self.refreshUI($row, true);
        });

        $row.on('mouseleave', function () {
            $self.refreshUI($row, false);
        });
    };

    Plugin.prototype.refreshUI = function ($row, hovered) {
        hovered ? $row.addClass('hover') : $row.removeClass('hover');
    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };

    $[pluginName] = Plugin;

})(jQuery, window, document);