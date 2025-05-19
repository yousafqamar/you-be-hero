<?php
$currency_symbol = get_woocommerce_currency_symbol();
$youbehero_data = get_option('ybh_donation_checkout_params', []);
$causes = [];
$amounts = [];

if( $youbehero_data['status'] == 'active' && !empty($youbehero_data) && !empty($youbehero_data['selected_causes']) ){
    
    if( !empty($youbehero_data['selected_causes']) ){
        $causes = array_map(function ($cause) {
            return [
                'label' => $cause['name'],
                'value' => $cause['id'],
                'image' => $cause['image']
            ];
        }, $youbehero_data['selected_causes']);

    }
    if( !empty($youbehero_data['donation_settings']) && !empty($youbehero_data['donation_settings']['fixed_amounts']) ){
        
        $amounts = array_values($youbehero_data['donation_settings']['fixed_amounts']);
    }



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
    $btn_color = $youbehero_data['widget_configurations']['checkout_page']['btn_color'] ?? "#3b82f6";
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
        $eligible = true;

        if ( $donor == 'customer' &&  $donationType == 'fixed' && !empty($amounts) ) {
                $donation_amount = WC()->session->get('ybh_donation_amount', 0);
                $headHtml .= '<span>Θα θέλατε να κάνετε μια δωρεά;</span><span class="pill-container"><span class="donation-amount-pill">'.number_format((float)$donation_amount, 2, '.', '').'</span>'.$currency_symbol.'</span>';

                foreach ($amounts as $amount) {
                    $amount_cents = (int)$amount * 100;

                    $selected = $donation_amount == (float)$amount ? 'selected' : '';

                    $html .= '<button class="radio-button '.$selected.'" data-value="'.$amount_cents.'" data-label="'.$amount.'">'.$amount.$currency_symbol.'</button>';

                }
                $html .= '<button class="delete-button">🗑</button>';
                $html .= '<input name="donation_cause" id="donation-cause" type="hidden"/>
                        <input name="donation_amount" id="donation-amount" type="hidden"/>';

        } else if ($donor == 'customer' &&  $donationType == 'roundup') {

            if (function_exists('WC') && WC() !== null && WC()->cart !== null) {
                $cart = WC()->cart;
                $subtotal = $cart->get_subtotal();

                $roundedSubtotal = ceil($subtotal);
                switch (true) {
                    case ($roundedSubtotal <= 10):
                        $rounded = ceil($roundedSubtotal * 2) / 2; // Nearest €0.50
                        break;

                    case ($roundedSubtotal <= 50):
                        $rounded = ceil($roundedSubtotal); // Nearest €1
                        break;

                    case ($roundedSubtotal <= 100):
                        $rounded = ceil($roundedSubtotal / 5) * 5; // Nearest €5
                        break;

                    case ($roundedSubtotal <= 500):
                        $rounded = ceil($roundedSubtotal / 10) * 10; // Nearest €10
                        break;

                    case ($roundedSubtotal > 500):
                        $rounded = ceil($roundedSubtotal / 10) * 10; // Also Nearest €10
                        break;

                    default:
                        $rounded = $roundedSubtotal; // Fallback
                }
                $roundupValue = round($rounded - $roundedSubtotal, 2);
            } else {
                $roundupValue = 0;
            }

            if ( $roundupValue > 0 ) {
                //            $roundupValue = $roundedSubtotal - $subtotal;
                $amount_cents = (float)$roundupValue * 100;
                $headHtml .= '<span>Θα θέλατε να κάνετε μια δωρεά;</span><span class="donation-amount-pill">' . $roundupValue . $currency_symbol . '</span>';

                $selected = !empty($roundupValue) ? 'selected' : '';
                $html .= '<button class="radio-button ' . $selected . '" data-value="' . $amount_cents . '" data-label="' . $roundupValue . '" >' . $roundupValue . $currency_symbol . '</button>';
                $html .= '<button class="delete-button">🗑</button>';
                $html .= '<input name="donation_cause" id="donation-cause" type="hidden"/>
                    <input name="donation_amount" id="donation-amount" type="hidden" value="' . $amount_cents . '"/>';
            } else {
                $eligible = false;
            }
        } else if ($donor == 'eshop' &&  $donationType == 'fixed') {

            // $fixedValue = '1.00';
            $fixedValue = $youbehero_data['donation_settings']['fixed_amount'] ?? '1.00';
            $amount_cents = (float)$fixedValue * 100;

            $headHtml .= '<span>Μέσω αυτής της αγοράς, θα προσφέρουμε '.$fixedValue.$currency_symbol.' για να υποστηρίξουμε έναν μη κερδοσκοπικό οργανισμό</span>';
            $html .= '<input type="hidden" data-value="'.$amount_cents.'" data-label="'.$fixedValue.'" />';
            $html .= '<input name="donation_cause" id="donation-cause" type="hidden"/>
                    <input name="donation_amount" id="donation-amount" type="hidden" value="'.$amount_cents.'"/>';

        } else if ($donor == 'eshop' &&  $donationType == 'percentage') {

            $percent = $youbehero_data['donation_settings']['fixedPercentage'] ?? '15';
            $cart = WC()->cart;
            $subtotal = $cart->get_subtotal();
            $percentValue = $subtotal * $percent / 100;
            $amount_cents = $percentValue * 100;

            $headHtml .= '<span>Θα δωρίσουμε το '.$percent.'% της παραγγελίας σας σε φιλανθρωπικό οργανισμό</span>';
            $html .= '<input type="hidden" data-value="'.$amount_cents.'" data-label="'.$percentValue.'" />';
            $html .= '<input name="donation_cause" id="donation-cause" type="hidden"/>
                    <input name="donation_amount" id="donation-amount" type="hidden" value="'.$amount_cents.'"/>';

        }

    /**
     * =======================
     * Dummpy Values  - End
     * =======================
     */

        if ( $eligible ) {
            ?>

            <div class="donation-checkout-widget youbehero-donation-widget">

                <div class="donation-box" >
                    <h3><?php _e('Would you like to make a Donation?', YBH_TEXT_DOMAIN); ?></h3>

                    <div class="donation-box-container <?php echo $classString; ?>" style="background-color: <?php echo $style['background_color']; ?>; color: <?php echo $style['text_color']; ?>; border-color: <?php echo $style['border_color']; ?>;">
                        <div class="donation-header">
                            <?php echo $headHtml; ?>
                        </div>

                        <div class="custom-dropdown" id="ybh-dd-dropdown">
                            <div class="donation-select  custom-dropdown-toggle" id="ybh-dd-select">
                                <div class="donation-text">
                                    <?php if( isset( WC()->session ) && !empty( WC()->session->get( 'ybh_donation_cause' ) ) ) { ?>
                                        <img id="selected-cause-img" src="<?php echo WC()->session->get( '_donation_org_img' ); ?>" alt="Logo">
                                        <span id="selectedOption"><?php echo __( WC()->session->get( 'ybh_donation_cause' ), YBH_TEXT_DOMAIN )?></span>
                                    <?php } else { ?>
                                        <img id="selected-cause-img" src="<?php echo YBH_PLUGIN_URL?>public/img/save-hood-img.png" alt="Logo">
                                        <span id="selectedOption"><?php echo __( 'Please select a nonprofit organization', YBH_TEXT_DOMAIN )?></span>
                                    <?php } ?>
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

                <div id="donation-amounts" class="donation-buttons">
                </div>

                <div id="widget-loader" class="widget-loader hidden">
                  <div class="widget-loader-bar">
                    Updating...</div>
                </div>
            </div>
            </div>
            <?php
        } else {
            ?>
            <div>
                Sorry, you are not eligible for donation.
            </div>
            <?php
        }
    }
} else {
    ?>
    <div>
        Sorry, we couldn't load the donation details at the moment. Please try again later.
    </div>
    <?php
}
