import EventEmitter from 'event-emitter-es6';

class DocumentLink extends EventEmitter {
    constructor($element, options) {
        super();

        this.$element = $element;
        this.$linkedElement = options.linkedTo;
        this.$icon = $element.find('span');

        this.$linkedElement.attr('type', 'hidden');
        if(this.$linkedElement.val()) {
            this.link(this.$linkedElement.val());
        }
        else {
            this.unlink();
        }

        this.$element.hover(this.onMouseEnter.bind(this), this.onMouseLeave.bind(this));

        this.$element.on('click', this.onClick.bind(this));
    }

    onMouseEnter() {
        if (this.$linkedElement.val()) {
            this.$icon.removeClass('fa-link');
            this.$icon.addClass('fa-chain-broken');
        }
    }

    onMouseLeave() {
        if (this.$linkedElement.val()) {
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
        this.$linkedElement.val(itemId);

        this.$icon.addClass('fa-link');
        this.$icon.removeClass('fa-search');

        this.emit('afterLink');
    }

    unlink() {
        this.$element.val('');
        this.$linkedElement.val('');

        this.$icon.removeClass('fa-link');
        this.$icon.removeClass('fa-chain-broken');
        this.$icon.addClass('fa-search');

        this.emit('afterUnlink');
    }
}

export default DocumentLink;