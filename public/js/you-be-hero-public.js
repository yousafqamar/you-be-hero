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

                showLoader();
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
                        update_totals();
//                        $('body').trigger('update_checkout');
                    }
                });
                console.log('Donation process ends!');
                return true;

            } catch (error) {
                console.error('Donation error:', error);
                hideLoader();
                //show elegant notice update this
                wp.data.dispatch('core/notices').createNotice(
                    'error',
                    `Failed to add donation: ${error.message}`,
                    { id: 'donation-error' }
                );
                throw error;
            }
        };
        
        const update_totals = async () => {
            
            try {
                showLoader();
                // Invalidate the current cart data resolution
                await wp.data.dispatch('wc/store/cart').invalidateResolution('getCartData');
              } catch (error) {
                console.error('Error updating cart totals:', error);
              } finally {
                // Hide the loader after the operations are complete
                hideLoader();
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
        
        $('.donation-amounts .radio-button:checked').trigger('click');
        $(document).on('click', '.donation-amounts .radio-button', function (event) {
            event.preventDefault();

            const donation_amount = $(this).data('value');
            const donation_label = $(this).data('label');
//            console.log($(this));
            const donationAmountEle = document.getElementById('donation-amount');
            donationAmountEle.value = donation_amount;
//            selectRadioButton(donation_amount);
            $('.donation-amount-pill').text(donation_label);
            $('.donation-amounts .radio-button').removeClass('selected');
            $(this).addClass('selected');
            $('.donation-amounts .donation-amount').change();
            if ( validate_donation_data() ) {
                add_donation_to_cart( );
            }
        });
        
        $(document).on('click', '.donation-amounts .delete-button', function (event) {
            event.preventDefault();

            console.log($(this));
            const donationAmountEle = document.getElementById('donation-amount');
            donationAmountEle.value = '';
            $('.donation-amount-pill').text('0,00');
            $('.donation-amounts .radio-button').removeClass('selected');
            $('.donation-amounts .donation-amount').change();
            add_donation_to_cart( );
        });
        if( jQuery('.ybh-dd-option').length )
            jQuery('.ybh-dd-option').eq(0).click();
        // Show the loader
        function showLoader() {
          const loader = document.getElementById('widget-loader');
          const bar = loader.querySelector('.widget-loader-bar');
          loader.classList.remove('hidden');
          bar.style.width = '0%';
          setTimeout(() => {
            bar.style.width = '100%';
          }, 10); // Slight delay to trigger transition
        }

        // Hide the loader
        function hideLoader() {
          const loader = document.getElementById('widget-loader');
          const bar = loader.querySelector('.widget-loader-bar');
          bar.style.width = '100%';
          setTimeout(() => {
            loader.classList.add('hidden');
            bar.style.width = '0%';
          }, 500); // Wait for the transition to complete
        }

    });

})( jQuery );
