(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
    jQuery(document).ready(function($) {

        const { causes, amounts } = ybh_donation_checkout_params;

        // Populate causes and amounts
        const $causeSelect = $('#donation-cause');
        const $amountsContainer = $('#donation-amounts');

        const addDonationFee = async (orgId, orgName, amount, orgImg) => {
            try {
                // 1. Get current cart state
                const { getCartData } = wp.data.select('wc/store/cart');
                const currentCart = getCartData();

                    console.log( orgId, orgName, amount )

                // const cartData = wp.data.select('wc/store/cart').getCartData();
                // let donationFee = [];
                // $.map(cartData.fees,function(elem, key){
                //     if (elem.name.includes('Donation for')){
                //         donationFee = elem
                //         // wp.data.dispatch('wc/store/cart').setCartData({
                //         //     fees: []
                //         // });
                //     }
                // })
                // // console.log(donationFee)
                //
                //     // await wp.data.dispatch('wc/store/cart').setCartData({
                //     //     fees: [
                //     //         ...(currentCart.fees || []),
                //     //         donationFee
                //     //     ]
                //     // });
                //     await wp.data.dispatch('wc/store/cart').setCartData({
                //         fees: [
                //             ...(currentCart.fees || []),
                //             {
                //                 name: `Donation for ${orgName}`,
                //                 totals: {
                //                     currency_code: 'USD',
                //                     currency_minor_unit: 2,
                //                     total: Math.round(amount).toString(),
                //                     total_tax: '0'
                //                 },
                //                 meta_data: [
                //                     { key: '_donation_org_id', value: orgId },
                //                     { key: '_donation_org_name', value: orgName }
                //                 ]
                //             }
                //         ]
                //     });


                let updatedFees = [];

                if (Array.isArray(currentCart.fees)) {
                    updatedFees = currentCart.fees.filter((fee) => {
                        return !fee.name.includes('Donation for');
                    });
                }

                updatedFees.push({
                    name: `Donation for ${orgName}`,
                    totals: {
                        currency_code: 'USD',
                        currency_minor_unit: 2,
                        total: Math.round(amount).toString(),
                        total_tax: '0'
                    },
                    meta_data: [
                        { key: '_donation_org_id', value: orgId },
                        { key: '_donation_org_name', value: orgName }
                    ]
                });

                await wp.data.dispatch('wc/store/cart').setCartData({
                    ...currentCart,
                    fees: updatedFees
                });

                await wp.data.dispatch('wc/store/cart').invalidateResolution('getCartData');



                //server side update
                $.ajax({
                    type: 'POST',
                    url: ybh_donation_checkout_params.ajax_url,
                    data: {
                        action: 'update_donation_fee',
                        org_id: orgId,
                        amount: amount/100,
                        org_name: orgName,
                        org_img: orgImg,
                        meta_data: [
                            { key: '_donation_org_id', value: orgId },
                            { key: '_donation_org_name', value: orgName },
                            { key: '_donation_org_img', value: orgImg }
                        ]
                    },
                    success: function(response) {
                        console.log('Donation added successfully!');
                        $('body').trigger('update_checkout');
                    }
                });
                console.log('Donation process ends!');
                return true;

            } catch (error) {
                console.error('Donation error:', error);
                //show elegant notice update this
                wp.data.dispatch('core/notices').createNotice(
                    'error',
                    `Failed to add donation: ${error.message}`,
                    { id: 'donation-error' }
                );
                throw error;
            }
        };

        function add_donation_to_cart(){
            console.log( 'add_donation_to_cart' )
            const orgId = $('#donation-cause').val();
            const amount = $('#donation-amount').val();

            const selectedCause = causes.find(cause =>cause.value === parseInt(orgId));
            const orgName = selectedCause ? selectedCause.label : '';
            const orgImg = selectedCause ? selectedCause.image : '';
            const numericAmount = parseFloat(amount);
            addDonationFee( orgId, orgName, numericAmount, orgImg );
        }

        function validate_donation_data(){

            const donation_cause = $('#donation-cause').val();
            const donation_amount = $('#donation-amount').val();
//            console.log(donation_amount);
            if( !donation_amount ){
                console.log('Please select amount to donate');
                return false;
            }
            if( !donation_cause ){
                console.log('Please select cause to donate');
                return false;
            }
            return true;
        }

        // Handle dynamic updates
//        $('#donation-amounts input[type="radio"], .donation-amounts button').change(function() {
        $('#donation-amount').change(function() {
            const donation_amount = $(this).val();
            const donation_cause = $('#donation-cause').val();
            if ( validate_donation_data() ) {
                add_donation_to_cart( );
            }
        });

        $('#donation-cause').change(function() {
            const donation_cause = $(this).val();
//            const donation_amount = $('#donation-amounts input[type="radio"]:checked').val();
            const donation_amount = $('#donation-amount').val();

            if ( validate_donation_data() ) {
                add_donation_to_cart( );
            }
        });

        $(document).on('click', '#ybh-dd-select', function () {
            $('#dropdownMenu').toggleClass('show');
          });
        $(document).on('click', '.ybh-dd-option', function (event) {
            event.preventDefault();
            const selectedOption = document.getElementById('selectedOption');
            const donationCauseEle = document.getElementById('donation-cause');
            const causeImgEle = document.getElementById('selected-cause-img');

            $('#dropdownMenu').removeClass('show');
            selectedOption.textContent = $(this).data("text");
            console.log('Selected Value:', $(this).data("value"));
            donationCauseEle.value = $(this).data("value");
            causeImgEle.src = $(this).data("image");

            if ( validate_donation_data() ) {
                add_donation_to_cart( );
            }
        });

        // Close the dropdown if clicked outside
        window.onclick = function(event) {
            if (!event.target.matches('.custom-dropdown-toggle')) {
                const dropdowns = document.querySelectorAll('.custom-dropdown-menu');
                dropdowns.forEach(dropdown => {
                    if (dropdown.classList.contains('show')) {
                        dropdown.classList.remove('show');
                    }
                });
            }
        };
        $('.radio-button:checked').trigger('click');
        $(document).on('click', '.radio-button', function (event) {
            event.preventDefault();

            const donation_amount = $(this).data('value');
            const donation_label = $(this).data('label');
            console.log($(this));
            const donationAmountEle = document.getElementById('donation-amount');
            donationAmountEle.value = donation_amount;
//            selectRadioButton(donation_amount);
            $('.donation-amount-pill').text(donation_label);
            $('.radio-button').removeClass('selected');
            $(this).addClass('selected');
            $('.donation-amount').change();
            if ( validate_donation_data() ) {
                add_donation_to_cart( );
            }
        });
        // JavaScript to handle radio button selection
//        function selectRadioButton(value) {
//             console.log('value:', value)
//            const radioButtons = document.querySelectorAll('.radio-button');
//            radioButtons.forEach(button => {
//                button.classList.remove('selected');
//            });
//            event.target.classList.add('selected');
//
//            const donationAmountEle = document.getElementById('donation-amount');
//            donationAmountEle.value = value;
//            console.log('Selected Radio Value:', value); // Log the selected radio value
////            const donationCauseEle = document.getElementById('donation-cause');
////            let donation_cause = donationCauseEle.value;
////            update_donation_to_cart( value, donation_cause );
//        }

    });

})( jQuery );
