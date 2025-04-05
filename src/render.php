<?php
$currency_symbol = get_woocommerce_currency_symbol();
$youbehero_data = get_option('ybh_donation_checkout_params');
//$causes = $attributes['causes'];
//$amounts = $attributes['amounts'];

$causes = array_map(function ($cause) {
    return [
        'label' => $cause['name'],
        'value' => $cause['id'],
        'image' => $cause['image']
    ];
}, $youbehero_data['selected_causes']);

$amounts = array_values($youbehero_data['donation_settings']['fixed_amounts']);

function render_donation_buttons($amounts) {
    
    foreach ($amounts as $amount) {
        $amount_cents = $amount * 100; // Convert to cents for Stripe
        ?>
        <button class="radio-button" data-value="<?php echo $amount_cents; ?>" data-label="<?php echo $amount; ?>" ><?php echo $amount; ?></button>
        <?php
    }
}
?>

    <div class="donation-checkout-widget youbehero-donation-widget">
        
        <div class="donation-box">
            <h3><?php _e('Would you like to make a Donation?', 'woocommerce'); ?></h3>

                    <div class="donation-box-container">
                    <div class="donation-header">
                        <span>Θα θέλατε να κάνετε μια δωρεά;</span>
                        <span class="donation-amount-pill">0,00<?php echo $currency_symbol;?></span>
                    </div>

                    <div class="custom-dropdown">
                        <div class="donation-select  custom-dropdown-toggle" id="ybh-dd-select">
                            <div class="donation-text">
                                <img id="selected-cause-img" src="<?php echo YBH_PLUGIN_URL?>public/img/save-hood-img.png" alt="Logo">
                                <span id="selectedOption">Save your hood</span>
                            </div>
                            <span class="dropdown-arrow">▼</span>
                        </div>
                        <div class="custom-dropdown-menu" id="dropdownMenu">

                        <?php
                            foreach ($causes as $key=>$cause) {?>
                            <div class="custom-dropdown-option ybh-dd-option" id="<?php echo $key;?>-ybh-dd-option" data-image="<?php echo $cause['image']?>" data-text="<?php echo $cause['label']?>" data-value="<?php echo $cause['value']?>")">
                                  <img alt="<?php echo $cause['label']?>" src="<?php echo $cause['image']?>"/>
                                <span class="text-gray-700"><?php echo $cause['label']?></span>
                            </div>
                    <?php   } ?>
                        </div>
                    </div>

                    <div class="donation-buttons donation-amounts">
                        <?php render_donation_buttons($amounts);?>
                        <button class="delete-button">🗑</button>
                    </div>
                    <input name="donation_cause" id="donation-cause" type="hidden"/>
                    <input name="donation_amount" id="donation-amount" type="hidden"/>
                    
                 </div>
            <div class="donation-item">
            </div>
            <div id="donation-amounts" class="donation-buttons">

            </div>
        </div>
  </div>
