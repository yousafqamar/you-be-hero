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

/**
 * =======================
 * Dummpy Values  - Start
 * =======================
 */
    $donor = 'eshop'; // customer, eshop
    $donationType = 'percentage'; // fixed, roundup, percentage

    $html = $headHtml = '';
    if ($donor == 'customer' &&  $donationType == 'fixed') {

            $headHtml .= '<span>Θα θέλατε να κάνετε μια δωρεά;</span><span class="donation-amount-pill">0,00'.$currency_symbol.'</span>';

            foreach ($amounts as $amount) {
                $amount_cents = (int)$amount * 100;

                $html .= '<button class="radio-button" data-value="'.$amount_cents.'" data-label="'.$amount.'" >'.$amount.'</button>';

            }
            $html .= '<button class="delete-button">🗑</button>';

    } else if ($donor == 'customer' &&  $donationType == 'roundup') {

        $headHtml .= '<span>Θα θέλατε να κάνετε μια δωρεά;</span><span class="donation-amount-pill">0,00'.$currency_symbol.'</span>';

        $html .= '<button class="radio-button" data-value="0.80" data-label="0.80" >0.80</button>';
        $html .= '<button class="delete-button">🗑</button>';

    } else if ($donor == 'eshop' &&  $donationType == 'fixed') {

        $fixedValue = '1.00';

        $headHtml .= '<span>Μέσω αυτής της αγοράς, θα προσφέρουμε '.$fixedValue.$currency_symbol.' για να υποστηρίξουμε έναν μη κερδοσκοπικό οργανισμό</span>';
        $html .= '<input type="hidden" data-value="'.$fixedValue.'" data-label="'.$fixedValue.'" />';

    } else if ($donor == 'eshop' &&  $donationType == 'percentage') {

        $percent = '15';
        $cart = WC()->cart;
        $subtotal = $cart->get_subtotal();
        $percentValue = $subtotal * $percent / 100;

        $headHtml .= '<span>Θα δωρίσουμε το '.$percent.'% της παραγγελίας σας σε φιλανθρωπικό οργανισμό</span>';
        $html .= '<input type="hidden" data-value="'.$percentValue.'" data-label="'.$percentValue.'" />';

    }

/**
 * =======================
 * Dummpy Values  - End
 * =======================
 */


?>

<div class="donation-checkout-widget youbehero-donation-widget">

    <div class="donation-box">
        <h3><?php _e('Would you like to make a Donation?', 'woocommerce'); ?></h3>

        <div class="donation-box-container">
            <div class="donation-header">
                <?php echo $headHtml; ?>
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
            <?php echo $html; ?>
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
