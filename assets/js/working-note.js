import $ from 'jquery';
import DocumentLink from './components/document-link';
import AutoComplete from './components/autocomplete';

$(function () {
    const $customerName = $('.js-customer-name');
    const $customer = $('.js-customer');
    const $customerLink = $('.js-customer-link');
    const $customersEmptyList = $('#customers-empty-list-template');

    const documentLink = new DocumentLink($customerLink, {
        linkedTo: $customer
    });

    const autocomplete = new AutoComplete($customerName, {
        dataSets: [{
            url: $customerName.data('search-url') + '?term=',
            field: 'customers',
            display: function (item) {
                return item.name;
            }
        }],
        templates: {
            empty: $customersEmptyList.html()
        },
    });

    documentLink.on('afterUnlink', function () {
        $customerName.val('');
    });

    autocomplete.on('afterSelect', function (item) {
        documentLink.link(item.id);
    });
});
