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

        $('.donation-item').before(`<div class="donation-box-container">
                <div class="donation-header">
                    <span>Ta ???ate ?a ???ete µ?a d??e?;</span>
                    <span class="donation-amount">0,00€</span>
                </div>

                <!-- Custom Dropdown -->
                <div class="custom-dropdown">
                    <div class="donation-select custom-dropdown-toggle" id="customDropdownToggle">
                        <div class="donation-text">
                            <img src="https://via.placeholder.com/35" alt="Logo">
                            <span id="selectedOption">Save your hood</span>
                        </div>
                        <span class="dropdown-arrow">?</span>
                    </div>
                    <div class="custom-dropdown-menu" id="dropdownMenu">
                        <!-- Add more options here if needed -->
                    </div>
                </div>

                <!-- Radio Buttons -->
                <div class="donation-buttons">
                    <button>0,5€</button>
                    <button class="active">1€</button>
                    <button>3€</button>
                    <button class="delete-button">??</button>
                </div>
                <input name="donation_cause" id="donation-cause" type="hidden"/>
                <input name="donation_amount" id="donation-amount" type="hidden"/>
             </div>`)


        $('#dropdownMenu').html("");
        causes.forEach((cause) => {
            $('#dropdownMenu').append(`<div class="custom-dropdown-option" id="customDropdownOption" data-text="${cause.label}" data-value="${cause.value}")">
                      <img alt="${cause.label}" src="${cause.image}"/>
                      <span class="text-gray-700">${cause.label}</span>
                    </div>`);
        });

        // $causeSelect.html("");
        // causes.forEach((cause) => {
        //     $causeSelect.append(`<option value="${cause.value}">${cause.label}</option>`);
        // });

        // amounts.forEach((amount) => {
        //     amount = parseInt(amount)
        //     $amountsContainer.prepend(`
        //         <label class="radio-button">
        //             <input type="radio" name="donation_amount" value="${amount}" class="visually-hidden"> $${amount}
        //         </label>
        //     `);
        // });
        
        
        function addDonation(donation_amount, donation_cause){
            
                wp.data.dispatch('wc/store/cart').setCartData({
                    fees: [
                        {
                            name: donation_cause,
                            totals: {
                                currency_minor_unit: donation_amount,
                                total: donation_amount * 100, // In cents (5.00 USD)
                                total_tax: '0'
                            }
                        }
                    ]
                }).then((response) => {
                    console.log('Donation added successfully!', response);
                }).catch((error) => {
                    console.error('Error adding Donation:', error);
                });
        }

        // Handle dynamic updates
        $('#donation-amounts input[type="radio"], .donation-amounts button').change(function() {
            const donation_amount = $(this).val();
            const donation_cause = $('#donation-cause').val();
    console.log(donation_amount);
            if (donation_amount && donation_cause) {
                addDonation( donation_amount, donation_cause );
            }
        });

        $('#donation-cause').change(function() {
            const donation_cause = $(this).val();
            const donation_amount = $('#donation-amounts input[type="radio"]:checked').val();
    console.log(donation_cause);
            if (donation_amount && donation_cause) {
                addDonation( donation_amount, donation_cause );
            }
        });

        //=======//=====//
        function update_donation_to_cart( donation_amount, donation_cause ){

            if (donation_amount && donation_cause) {
                jQuery.ajax({
                    type: 'POST',
                    url: wc_checkout_params.ajax_url,
                    data: {
                        action: 'update_donation_fee',
                        donation_amount: donation_amount,
                        donation_cause: donation_cause
                    },
                    success: function(response) {
                        jQuery('body').trigger('update_checkout');
                    }
                });
            }
        }
        // JavaScript to handle dropdown toggle and selection
        // function toggleDropdown() {

                // const dropdownMenu = document.getElementById('dropdownMenu');
                // dropdownMenu.classList.toggle('show');

        document.getElementById("customDropdownToggle").addEventListener("click", function () {
            document.getElementById("dropdownMenu").classList.toggle("show");
        });

        // }

        document.getElementById("customDropdownOption").addEventListener("click", function () {
            console.log('ads')
        // function selectOption(optionText, value) {
            const selectedOption = document.getElementById('selectedOption');
            const donationCauseEle = document.getElementById('donation-cause');
            selectedOption.textContent = $(this).data("text");//optionText;
            toggleDropdown(); // Close the dropdown after selection
            console.log('Selected Value:', $(this).data("value")); // Log the selected value
            donationCauseEle.value = $(this).data("value");
        // }
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

        // JavaScript to handle radio button selection
        function selectRadioButton(value) {
            const radioButtons = document.querySelectorAll('.radio-button');
            radioButtons.forEach(button => {
                button.classList.remove('selected');
            });
            event.target.classList.add('selected');

            const donationAmountEle = document.getElementById('donation-amount');
            donationAmountEle.value = value;
            console.log('Selected Radio Value:', value); // Log the selected radio value
            const donationCauseEle = document.getElementById('donation-cause');
            let donation_cause = donationCauseEle.value;
            update_donation_to_cart( value, donation_cause );
        }


        // jQuery(document).ready(function($) {
            $('#donation-amount').change(function() {
                var donation_amount = $(this).val();
                var donation_cause = $('#donation-cause').val();
                console.log(donation_cause, donation_amount);

                if (donation_amount && donation_cause) {
                    $.ajax({
                        type: 'POST',
                        url: wc_checkout_params.ajax_url,
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
                console.log($('#donation-cause'));
                var donation_cause = $(this).val();
                var donation_amount = $('#donation-amount').val();
                update_donation_to_cart( donation_amount, donation_cause );
            });

        // });
        //====//===//
    });

})( jQuery );
