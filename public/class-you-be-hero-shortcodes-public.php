<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://youbehero.com
 * @since      1.0.0
 *
 * @package    You_Be_Hero
 * @subpackage You_Be_Hero/public
 */

class ShortCodes_Public {

    public function __construct( ) {


        add_shortcode('donation_form', [ $this, 'add_donation_form_shortcode' ]);
        add_shortcode('ybh_donation_form', [ $this, 'add_donation_form_shortcode' ]);
    }
    
    function add_donation_form_shortcode() {
        ob_start();
        ?>
        <div class="donation-checkout-widget">
            <!--<script src="<?php echo YBH_PLUGIN_URL?>public/css/tailwindcss.3.4.16.css"></script>-->
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
            <style>
              /* Custom styles for the dropdown */
              .custom-dropdown {
                position: relative;
                display: inline-block;
                width: 100%;
              }
              .custom-dropdown-toggle {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.5rem;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                background-color: white;
                cursor: pointer;
              }
              .custom-dropdown-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                background-color: white;
                z-index: 10;
              }
              .custom-dropdown-menu.show {
                display: block;
              }
              .custom-dropdown-option {
                display: flex;
                align-items: center;
                padding: 0.5rem;
                cursor: pointer;
              }
              .custom-dropdown-option:hover {
                background-color: #f3f4f6;
              }
              /* Style for selected radio button */
              .radio-button.selected {
                background-color: #3b82f6;
                color: white;
              }
            </style>
          <div class="bg-gray-100 flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg p-4 max-w-md w-full">
              <div class="bg-gray-200 text-center py-2 rounded-t-lg">
                <span class="text-gray-600 font-semibold">ΟΛΟΚΛΗΡΩΣΗ ΑΓΟΡΑΣ</span>
              </div>
              <div class="p-4">
                <div class="flex items-center justify-between mb-4">
                  <span class="text-gray-700">Θα θέλατε να κάνετε μια δωρεά;</span>
                  <span class="bg-blue-500 text-white font-semibold px-2 py-1 rounded">0,00€</span>
                </div>

                <!-- Custom Dropdown -->
                <div class="custom-dropdown mb-4">
                  <div class="custom-dropdown-toggle" onclick="toggleDropdown()">
                    <div class="flex items-center">
                      <img alt="Logo of Save your hood" class="w-10 h-10 rounded-full mr-2" src="https://placehold.co/40x40"/>
                      <span class="text-gray-700" id="selectedOption">Save your hood</span>
                    </div>
                    <div class="flex items-center">
                      <span class="text-gray-500 mr-2">Αλλαγή</span>
                      <i class="fas fa-chevron-down text-gray-500"></i> <!-- Font Awesome chevron -->
                    </div>
                  </div>
                  <div class="custom-dropdown-menu" id="dropdownMenu">
                    <div class="custom-dropdown-option" onclick="selectOption('Save your hood', 10)">
                      <img alt="Logo of Save your hood" class="w-10 h-10 rounded-full mr-2" src="https://placehold.co/40x40"/>
                      <span class="text-gray-700">Save your hood (10€)</span>
                    </div>
                    <div class="custom-dropdown-option" onclick="selectOption('Another Option', 20)">
                      <img alt="Logo of Another Option" class="w-10 h-10 rounded-full mr-2" src="https://placehold.co/40x40"/>
                      <span class="text-gray-700">Another Option (20€)</span>
                    </div>
                    <!-- Add more options here if needed -->
                  </div>
                </div>

                <!-- Radio Buttons -->
                <div class="grid grid-cols-4 gap-2">
                  <button class="radio-button border border-blue-500 text-blue-500 rounded-lg py-2" onclick="selectRadioButton(1)">1€</button>
                  <button class="radio-button border border-blue-500 text-blue-500 rounded-lg py-2" onclick="selectRadioButton(3)">3€</button>
                  <button class="radio-button border border-blue-500 text-blue-500 rounded-lg py-2" onclick="selectRadioButton(5)">5€</button>
                  <button class="radio-button border border-gray-500 text-gray-500 rounded-lg py-2" onclick="selectRadioButton(0)">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
                <input name="donation_cause" id="donation-cause" type="text"/>
                <input name="donation_amount" id="donation-amount" type="text"/>
              </div>
            </div>

            <script>
                
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
              function toggleDropdown() {
                const dropdownMenu = document.getElementById('dropdownMenu');
                dropdownMenu.classList.toggle('show');
              }

              function selectOption(optionText, value) {
                const selectedOption = document.getElementById('selectedOption');
                const donationCauseEle = document.getElementById('donation-cause');
                selectedOption.textContent = optionText;
                toggleDropdown(); // Close the dropdown after selection
                console.log('Selected Value:', value); // Log the selected value
                donationCauseEle.value = value;
              }

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
              
              
              jQuery(document).ready(function($) {
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
                
            });
            </script>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

}
