import EventEmitter from 'event-emitter-es6';

class DocumentLink extends EventEmitter {
    constructor($element, $linkedTo, options = {}) {
        super(options);

        this.$element = $element;
        this.$linkedTo = $linkedTo;
        this.$icon = $element.find('span');

        this.$linkedTo.attr('type', 'hidden');

        if(this.$linkedTo.val()) {
            this.link(this.$linkedTo.val());
        }
        else {
            this.unlink();
        }

        this.$element.hover(this.onMouseEnter.bind(this), this.onMouseLeave.bind(this));

        this.$element.on('click', this.onClick.bind(this));
    }

    onMouseEnter() {
        if (this.$linkedTo.val()) {
            this.$icon.removeClass('fa-link');
            this.$icon.addClass('fa-chain-broken');
        }
    }

    onMouseLeave() {
        if (this.$linkedTo.val()) {
            this.$icon.addClass('fa-link');
            this.$icon.removeClass('fa-chain-broken');
        }
    }

    onClick(event) {
        this.unlink();

        event.preventDefault();
        event.stopPropagation();
    }

    link(itemId) {
        this.$linkedTo.val(itemId);

        this.$icon.addClass('fa-link');
        this.$icon.removeClass('fa-search');

        this.emit('linked', itemId);
    }

    unlink() {
        this.$element.val('');
        this.$linkedTo.val('');

        this.$icon.removeClass('fa-link');
        this.$icon.removeClass('fa-chain-broken');
        this.$icon.addClass('fa-search');

        this.emit('unlinked');
    }
}

export default DocumentLink;