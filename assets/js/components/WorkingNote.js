import DocumentLink from './DocumentLink';
import AutoComplete from './AutoComplete';

class WorkingNote {
    constructor($input, $linkedTo, $link, $emptyList) {
        this.documentLink = new DocumentLink($link, $linkedTo, { emitDelay: 0});

        const searchUrl = $input.data('search-url');
        if (!searchUrl) {
            throw new Error('No search URL defined.');
        }

        this.autocomplete = new AutoComplete($input, {
            emitDelay: 0,
            dataSets: [{
                url: searchUrl + '?term=',
                field: 'customers',
                display: function (item) {
                    return item.name;
                }
            }],
            templates: {
                empty: $emptyList.html()
            },
        });

        this.documentLink.on('unlinked', function () {
            $input.val('');
        });

        this.autocomplete.on('selected', this.onSelected.bind(this));
    }

    onSelected(item) {
        this.documentLink.link(item.id);
    }
}

export default WorkingNote;