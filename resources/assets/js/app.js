/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const app = new Vue({
//     el: '#app'
// });

const $doc = jQuery(document);
const $window = jQuery(window);
const $body = jQuery('body');

require('eonasdan-bootstrap-datetimepicker');

class App {

    constructor() {
        this.domReady();
    }

    domReady() {
        this.syncCityList();
        this.datePicker();
        this.toggleBookingActions();
        this.toggleChildAge();
        this.toggleRoomGuests();
        this.stickyHeader();
    }

    syncCityList() {
        $doc.on('change', '.country', function(e) {
            let $country = jQuery(this);
            let $form = $country.parents('form');
            let $city = $form.find('.city');
            let $options = $city.find('option');
            $options.hide();
            $options.each(function(index) {
                let $this = jQuery(this);
                if ($this.data('country-code') == $country.val()) {
                    $this.show();
                }
            });
            $city.val('');
        });
        jQuery('.country').change();
    }

    datePicker() {
        jQuery('.date-picker').datetimepicker({
            format: 'DD/MM/YYYY',
        });
    }

    toggleBookingActions() {
        $doc.on('change', '._bookingForm__checkbox', function(e) {
            let $form = jQuery('._bookingForm');
            $form.find('._bookingForm__item__info').hide();
            jQuery(this).parents('._bookingForm__item:first').find('._bookingForm__item__info').show();
        })
    }

    toggleRoomGuests() {
        $doc.on('change', '.noOfRooms', function(e) {
            let $this = jQuery(this),
                count = $this.val(),
                $childFields = $this.parents('form:first').find('.roomGuests');

            $childFields.hide();
            for (let i = 0; i < count; i++) {
                $childFields.eq(i).show();
            }
        })
        jQuery('.noOfRooms').change();
    }

    toggleChildAge() {
        $doc.on('change', '.noOfChilds', function(e) {
            let $this = jQuery(this),
                count = $this.val(),
                $childFields = $this.parents('.roomGuests:first').find('.childAge');

            $childFields.hide();
            for (let i = 0; i < count; i++) {
                $childFields.eq(i).show();
            }
        })
    }

    stickyHeader() {
        $window.on('scroll', function(e) {
            let top = jQuery(window).scrollTop();
            let $header = jQuery('#docHeaderSticky');
            if (top > 500) {
                $header.addClass('sticky');
            } else {
                $header.removeClass('sticky');
            }
        })
    }

}

export default new App;