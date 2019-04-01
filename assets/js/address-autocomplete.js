import $ from 'jquery';
import AddressAutoComplete from "./components/AddressAutoComplete";

$(function () {
    const $address = $('.js-address');

    $address.each(() => {
        const $this = $(this);
        const $city = $this.find('.js-city');
        const $zipCode = $this.find('.js-zip-code');
        const $province = $this.find('.js-province');
        const $country = $this.find('.js-country');
        const $emptyList = $('#empty-cities-template');
        // console.log($emptyList.html());

        new AddressAutoComplete($city, $zipCode, $province, $country, { templates: { empty: $emptyList.html() } });
    });
});
