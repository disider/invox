(function ($) {

    $.widget('invox.paragraphs', {
        options: {
            add_at_the_end: true,
            allow_duplicate: true,
            form_name: null,
            form_prefix: null
        },

        _create: function () {
            var $element = this.element;
            var $self = this;

            $.extend(true, this.options, {
                after_add: $.proxy(this._addCollection, this),
                before_up: $.proxy(this._destroyEditors, this),
                before_down: $.proxy(this._destroyEditors, this),
                after_up: $.proxy(this._createEditors, this),
                after_down: $.proxy(this._createEditors, this),
                after_init: $.proxy(this._initEditors, this)
            });

            this._collectionize($element);

            $element.find('.collection').each(function () {
                $self._collectionize($(this));
            });

            // $('.collection-hide').on('click', function(event) {
            //     $self._initHideElement($(this).attr('id'));
            //     event.stopPropagation();
            // });
        },

        _collectionize: function (element) {
            var opts = {
                add: element.siblings('.actions').find('.add-placeholder')
            };

            $.extend(true, opts, this.options, opts);
            element.collection(opts);
        },

        _addCollection: function (collection, element) {
            var $self = this;
            var $subCollection = element.find('.collection');

            this._collectionize($subCollection);

            var level = collection.data('level');
            $subCollection.data('level', level + 1);

            var paragraph = element.find('.paragraph');
            paragraph.removeClass('odd').removeClass('even');
            paragraph.addClass(level % 2 ? 'odd' : 'even');

            this._initEditors(collection, element);

            collection.trigger('update', [element]);

            // element.find('.collection-hide').on('click', function(event) {
            //     var el = $(this)[0].children[0];
            //     $self._initHideElement($(el).attr('id'));
            //     event.stopPropagation();
            // });
        },

        _destroyEditors: function (collection, element) {
            collection.find('.text-editor').each(function () {
                $(this).textEditor('destroy');
            });
        },

        _createEditors: function (collection, element) {
            console.log('create editors');

            collection.find('.text-editor').each(function () {
                $(this).textEditor('create');
            });
        },

        _initEditors: function (collection, element) {
            collection.find('.text-editor').each(function () {
                $(this).textEditor(textEditorOptions);
            });
        },

        _initHideElement: function onHideElement(id) {
            $('.collection-hide#' + id + ' span').toggleClass('fa-eye-slash').toggleClass('fa-eye');
            var $titleField = $('#' + id + '_title');
            var $descriptionField = $('#cke_' + id + '_description');

            var iframe = $descriptionField.find('iframe');
            if (iframe == undefined)
                return;

            var description = $(iframe[0].contentWindow.document.body).html();

            $titleField.toggleClass('hidden');
            $descriptionField.toggleClass('hidden');

            $('#' + id + '_title_text').toggleClass('hidden').html($titleField.val());
            $('#' + id + '_description_text').toggleClass('hidden').html(description);
        }

    });
})(jQuery);
