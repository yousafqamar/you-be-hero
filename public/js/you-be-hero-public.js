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

        causes.forEach((cause) => {
            //$causeSelect.append(`<option value="${cause.value}">${cause.label}</option>`);
        });

        amounts.forEach((amount) => {
            amount = parseInt(amount)
            $amountsContainer.append(`
                <label>
                    <input type="radio" name="donation_amount" value="${amount}"> $${amount}
                </label>
            `);
        });

        // Handle dynamic updates
        $('#donation-amounts input[type="radio"]').change(function() {
            const donation_amount = $(this).val();
            const donation_cause = $('#donation-cause').val();
    console.log(donation_amount);
            if (donation_amount && donation_cause) {
                $.ajax({
                    type: 'POST',
                    url: ybh_donation_checkout_params.ajax_url,
                    data: {
                        action: 'update_donation_fee',
                        donation_amount: donation_amount,
                        donation_cause: donation_cause
                    },
                    success: function(response) {
                        $('body').trigger('update_checkout');
                    }
                });
            }
        });

        $('#donation-cause').change(function() {
            const donation_cause = $(this).val();
            const donation_amount = $('#donation-amounts input[type="radio"]:checked').val();
    console.log(donation_cause);
            if (donation_amount && donation_cause) {
                $.ajax({
                    type: 'POST',
                    url: ybh_donation_checkout_params.ajax_url,
                    data: {
                        action: 'update_donation_fee',
                        donation_amount: donation_amount,
                        donation_cause: donation_cause
                    },
                    success: function(response) {
                        $('body').trigger('update_checkout');
                    }
                });
            }
        });
    });


//    const YBH_CHECKOUT_STORE_KEY = 'wc/store/checkout';
//
//    function YBHupdateCheckoutBlockData( values ) {
//        console.log('YBHupdateCheckoutBlockData');
//            // Update Checkout block data if available.
//            if ( window.wp && window.wp.data && window.wp.data.dispatch && window.wc && window.wc.wcBlocksData ) {
//                    window.wp.data.dispatch( window.wc.wcBlocksData.YBH_CHECKOUT_STORE_KEY ).__internalSetExtensionData(
//                            'donation-widget/ybh-chekcout-donation',
//                            values,
//                            true
//                    );
//            }
//    }
//    function YBHeventuallyInitializeCheckoutBlock() {
//        console.log('YBHeventuallyInitializeCheckoutBlock', window.wp && window.wp.data && typeof window.wp.data.subscribe === 'function');
//        console.log(window.wp.data.subscribe);
//            if (
//                    window.wp && window.wp.data && typeof window.wp.data.subscribe === 'function'
//            ) {
//                    // Update checkout block data once more if the checkout store was loaded after this script.
//                    const unsubscribe = window.wp.data.subscribe( function () {
//                            unsubscribe();
//                            YBHupdateCheckoutBlockData( wc_order_attribution.getAttributionData() );
//                    }, YBH_CHECKOUT_STORE_KEY );
//            }
//    };
    // Wait for DOMContentLoaded to make sure wp.data is in place, if applicable for the page.
    //if (document.readyState === "loading") {
    //        document.addEventListener("DOMContentLoaded", YBHeventuallyInitializeCheckoutBlock);
    //} else {
    //        YBHeventuallyInitializeCheckoutBlock();
    //}

})( jQuery );
