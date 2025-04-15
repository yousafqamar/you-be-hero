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
    // $donor = 'eshop'; // customer, eshop
    // $donationType = 'fixed'; // fixed, roundup, percentage

$donor = $youbehero_data['donation_settings']['donor_type'] ?? 'customer'; // fallback to customer if not set
$donationType = $youbehero_data['donation_settings']['donation_type'] ?? 'fixed'; // fallback to fixed if not set
$checkWActive = $youbehero_data['widget_configurations']['checkout_page']['active'] ?? true;

$background_color = $youbehero_data['widget_configurations']['checkout_page']['background_color'] ?? "#ffffff";
$text_color = $youbehero_data['widget_configurations']['checkout_page']['text_color'] ?? "#000000";
$btn_color = $youbehero_data['widget_configurations']['checkout_page']['btn_color'] ?? "#000000";
$border = $youbehero_data['widget_configurations']['checkout_page']['border'] ?? true;
$margin = $youbehero_data['widget_configurations']['checkout_page']['margin'] ?? "bigMargin";
$padding = $youbehero_data['widget_configurations']['checkout_page']['padding'] ?? "midPadding";




$config = $youbehero_data['widget_configurations'];
$style = $config['checkout_page']['checkout_page'];
$classes = [];

if (!empty($style['padding'])) {
    $classes[] = $style['padding'];
}
if (!empty($style['margin'])) {
    $classes[] = $style['margin'];
}
if (!empty($style['border_radius'])) {
    $classes[] = $style['border_radius'];
}
if (!empty($style['border'])) {
    $classes[] = 'bordered'; // optional class for styling border if needed
}

$classString = implode(' ', $classes);

if( $checkWActive ){
    $html = $headHtml = '';
    if ($donor == 'customer' &&  $donationType == 'fixed') {

            $headHtml .= '<span>Θα θέλατε να κάνετε μια δωρεά;</span><span class="donation-amount-pill">0,00'.$currency_symbol.'</span>';

            foreach ($amounts as $amount) {
                $amount_cents = (int)$amount * 100;

                $html .= '<button class="radio-button" data-value="'.$amount_cents.'" data-label="'.$amount.'" >'.$amount.'</button>';

            }
            $html .= '<button class="delete-button">🗑</button>';
            $html .= '<input name="donation_cause" id="donation-cause" type="hidden"/>
                    <input name="donation_amount" id="donation-amount" type="hidden"/>';

    } else if ($donor == 'customer' &&  $donationType == 'roundup') {
        
        $cart = WC()->cart;
        $subtotal = $cart->get_subtotal();

        $roundedSubtotal = ceil($subtotal);
        $roundupValue = $roundedSubtotal - $subtotal;
        $headHtml .= '<span>Θα θέλατε να κάνετε μια δωρεά;</span><span class="donation-amount-pill">0,00'.$currency_symbol.'</span>';

        $html .= '<button class="radio-button" data-value="'.$roundupValue.'" data-label="'.$roundupValue.'" >'.$roundupValue.'</button>';
        $html .= '<button class="delete-button">🗑</button>';
        $html .= '<input name="donation_cause" id="donation-cause" type="hidden"/>
                <input name="donation_amount" id="donation-amount" type="hidden"/>';

    } else if ($donor == 'eshop' &&  $donationType == 'fixed') {

        // $fixedValue = '1.00';
        $fixedValue = $youbehero_data['donation_settings']['fixed_amount'] ?? '1.00';

        $headHtml .= '<span>Μέσω αυτής της αγοράς, θα προσφέρουμε '.$fixedValue.$currency_symbol.' για να υποστηρίξουμε έναν μη κερδοσκοπικό οργανισμό</span>';
        $html .= '<input type="hidden" data-value="'.$fixedValue.'" data-label="'.$fixedValue.'" />';
        $html .= '<input name="donation_cause" id="donation-cause" type="hidden"/>
                <input name="donation_amount" id="donation-amount" type="hidden" value="'.$fixedValue.'"/>';

    } else if ($donor == 'eshop' &&  $donationType == 'percentage') {

//        $percent = '15';
        $percent = $youbehero_data['donation_settings']['fixedPercentage'] ?? '15';
        $cart = WC()->cart;
        $subtotal = $cart->get_subtotal();
        $percentValue = $subtotal * $percent / 100;

        $headHtml .= '<span>Θα δωρίσουμε το '.$percent.'% της παραγγελίας σας σε φιλανθρωπικό οργανισμό</span>';
        $html .= '<input type="hidden" data-value="'.$percentValue.'" data-label="'.$percentValue.'" />';
        $html .= '<input name="donation_cause" id="donation-cause" type="hidden"/>
                <input name="donation_amount" id="donation-amount" type="hidden" value="'.$percentValue.'"/>';

    }

/**
 * =======================
 * Dummpy Values  - End
 * =======================
 */


?>

<div class="donation-checkout-widget youbehero-donation-widget">

    <div class="donation-box" >
        <h3><?php _e('Would you like to make a Donation?', 'woocommerce'); ?></h3>

        <div class="donation-box-container <?php echo $classString; ?>" style="background-color: <?php echo $style['background_color']; ?>; color: <?php echo $style['text_color']; ?>; border-color: <?php echo $style['border_color']; ?>;">
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

    </div>
    <div class="donation-item">
    </div>
    <div id="donation-amounts" class="donation-buttons">

    </div>
</div>
</div>

<?php 
}
