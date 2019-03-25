(function ($) {
    $.widget('invox.textEditor', {
        options: {
            browsePath: null,
            uploadPath: null
        },

        _create: function () {
            this.id = this.element.attr('id');

            this.rebuild();
        },
        
        rebuild: function() {
            this.destroy();
            this.create();
        },

        destroy: function() {
            if (CKEDITOR.instances[this.id]) {
                CKEDITOR.instances[this.id].destroy();

                delete CKEDITOR.instances[this.id];
            }
        },

        create: function() {
            CKEDITOR.replace(this.id, {
                'toolbar': [
                    ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
                    ['Scayt'],
                    ['Link', 'Unlink', 'Anchor'],
                    ['Image', 'Table', 'HorizontalRule', 'SpecialChar'],
                    ['Maximize'],
                    ['Source'],
                    "\/'",
                    ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat'],
                    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'],
                    ['Styles', 'Format', 'About']
                ],
                'filebrowserImageBrowseUrl': this.options.browsePath,
                'filebrowserImageUploadUrl': this.options.uploadPath
            });
        }

    });
})(jQuery);
