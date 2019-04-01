import $ from 'jquery';
import WorkingNote from './components/WorkingNote';

$(function () {
    const $customerName = $('.js-customer-name');
    const $customer = $('.js-customer');
    const $customerLink = $('.js-customer-link');
    const $customersEmptyList = $('#empty-customers-template');

    new WorkingNote($customerName, $customer, $customerLink, $customersEmptyList);
});
