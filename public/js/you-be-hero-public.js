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

//        $('.donation-item').before(`<div class="donation-box-container">
//                <div class="donation-header">
//                    <span>Θα θέλατε να κάνετε μια δωρεά;</span>
//                    <span class="donation-amount">0,00€</span>
//                </div>
//
//                <!-- Custom Dropdown -->
//                <div class="custom-dropdown">
//                    <div class="donation-select custom-dropdown-toggle" id="customDropdownToggle">
//                        <div class="donation-text">
//                            <img src="https://via.placeholder.com/35" alt="Logo">
//                            <span id="selectedOption">Save your hood</span>
//                        </div>
//                        <span class="dropdown-arrow">▼</span>
//                    </div>
//                    <div class="custom-dropdown-menu" id="dropdownMenu">
//                        <!-- Add more options here if needed -->
//                    </div>
//                </div>
//
//                <!-- Radio Buttons -->
//                <div class="donation-buttons donation-amounts">
//                    <button class="radio-button">0,5€</button>
//                    <button class="active">1€</button>
//                    <button>3€</button>
//                    <button class="delete-button">🗑</button>
//                </div>
//             </div>`)


//        $('#dropdownMenu').html("");
//        causes.forEach((cause) => {
//            $('#dropdownMenu').append(`<div class="custom-dropdown-option" id="customDropdownOption" data-text="${cause.label}" data-value="${cause.value}")">
//                      <img alt="${cause.label}" src="${cause.image}"/>
//                      <span class="text-gray-700">${cause.label}</span>
//                    </div>`);
//        });

        // $causeSelect.html("");
        // causes.forEach((cause) => {
        //     $causeSelect.append(`<option value="${cause.value}">${cause.label}</option>`);
        // });

//         amounts.forEach((amount) => {
//             amount = parseInt(amount)
//             $amountsContainer.prepend(`
//                 <label class="radio-button">
//                     <input type="radio" name="donation_amount" value="${amount}" class="visually-hidden"> $${amount}
//                 </label>
//             `);
//         });
        
        
        
        const addDonationFee = async (orgId, orgName, amount) => {
            try {
                const currentCart = wp.data.select('wc/store/cart').getCartData();

            console.log( orgId, orgName, amount )
                await wp.data.dispatch('wc/store/cart').setCartData({
                    fees: [
                        ...(currentCart.fees || []),
                        {
                            name: `Donation for ${orgName}`,
                            totals: {
                                currency_code: 'USD',
                                currency_minor_unit: 2,
                                total: Math.round(amount * 100).toString(),
                                total_tax: '0'
                            },
                            meta_data: [
                                { key: '_donation_org_id', value: orgId },
                                { key: '_donation_org_name', value: orgName }
                            ]
                        }
                    ]
                });


            } catch (error) {
                console.error('Donation error:', error);
            }
        };

        
        function add_donation_to_cart(){
            console.log( 'add_donation_to_cart' )
            const orgId = $('#donation-cause').val();
            const amount = $('#donation-amount').val();
            
            const selectedCause = causes.find(cause =>cause.value === parseInt(orgId));
            const orgName = selectedCause ? selectedCause.label : '';
            const numericAmount = parseFloat(amount);
            addDonationFee( orgId, orgName, numericAmount );
        }
        
        function validate_donation_data(){
            
            const donation_cause = $('#donation-cause').val();
            const donation_amount = $('#donation-amount').val();
//            console.log(donation_amount);
            if( !donation_amount ){
                alert('Please select amount to donate');
                return false;
            }
            if( !donation_cause ){
                alert('Please select cause to donate');
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
        $(document).on('click', '.ybh-dd-option', function () {
            const selectedOption = document.getElementById('selectedOption');
            const donationCauseEle = document.getElementById('donation-cause');
            const causeImgEle = document.getElementById('selected-cause-img');

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

        $(document).on('click', '.radio-button', function () {
            
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
