;
(function ($, window, document, undefined) {

    var pluginName = "dynamicList",
        defaults = {
            addButtonStyle: null,
            deleteButtonStyle: null,
            actionsContainer: null,
            itemSelector: '> li',
            itemClass: 'item',
            itemWrapper: null,
            rowTemplate: '<li></li>',
            addNewRowTemplate: null,
            itemTemplateId: null
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

        var $rows = $element.find(this.options.itemSelector);
        this.totalRows = $rows.length;

        $rows.each(function () {
            var $row = $(this);
            $row.addClass($self.options.itemClass);

            $self.addDeleteButton($row, $self.options.itemClass);
        });

        this.$addRow = this.buildAddNewRow().appendTo($element);

        $.each([this.$addRow], function() {
            this.on('click', function (ev) {
                var $newRow = $self.addNewRow();

                $element.trigger(pluginName + '.itemAdded', $newRow);

                ev.preventDefault();
                ev.stopPropagation();
            });
        });
    };

    Plugin.prototype.buildAddNewRow = function () {
        var $row = this.buildRow();

        if(this.options.addNewRowTemplate) {
            var $addNewRow = $(this.options.addNewRowTemplate);
            this.buildButton('add', 'dynamic-add', 'Add', $addNewRow);
            $row.append($addNewRow);
        }
        else
            this.buildButton('add', 'dynamic-add', 'Add', $row);

        return $row;
    };

    Plugin.prototype.buildRow = function () {
        return $(this.options.rowTemplate);
    };

    Plugin.prototype.addNewRow = function () {
        var template = $(this.options.itemTemplateId).html();

        var newRow = template.replace(/__name__/g, this.totalRows++);

        var $row = this.buildRow().append(newRow);
        $row.addClass(this.options.itemClass);

        this.$addRow.before($row);

        this.addDeleteButton($row, this.options.itemClass);

        return $row;
    };

    Plugin.prototype.addDeleteButton = function ($row, itemClass) {
        var $element = $(this.element);

        var $container = this.options.actionsContainer ? $row.find(this.options.actionsContainer) : $row;
        var $deleteBtn = this.buildButton('delete', 'dynamic-delete', 'Delete', $container);

        $deleteBtn.on('click', function () {
            $(this).closest('.' + itemClass).remove();

            $element.trigger(pluginName + '.itemDeleted');
        });
    };

    Plugin.prototype.buildButton = function(type, cls, title, $container) {
        var style = type + 'ButtonStyle';

        var $button = this.options[style] ? $(this.options[style]) : $('<button></button>').text(title);

        return $button.addClass(cls).appendTo($container);
    }

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    }

})(jQuery, window, document);