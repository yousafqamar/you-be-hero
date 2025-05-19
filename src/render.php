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
//                $html .= '<button class="delete-button">🗑</button>';
            $html .= '<button class="delete-button"><svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.07187 0.983984C4.21953 0.685938 4.52305 0.5 4.85391 0.5H8.14609C8.47695 0.5 8.78047 0.685938 8.92812 0.983984L9.125 1.375H11.75C12.234 1.375 12.625 1.76602 12.625 2.25C12.625 2.73398 12.234 3.125 11.75 3.125H1.25C0.766016 3.125 0.375 2.73398 0.375 2.25C0.375 1.76602 0.766016 1.375 1.25 1.375H3.875L4.07187 0.983984ZM1.25 4H11.75V12.75C11.75 13.7152 10.9652 14.5 10 14.5H3C2.03477 14.5 1.25 13.7152 1.25 12.75V4ZM3.875 5.75C3.63438 5.75 3.4375 5.94688 3.4375 6.1875V12.3125C3.4375 12.5531 3.63438 12.75 3.875 12.75C4.11562 12.75 4.3125 12.5531 4.3125 12.3125V6.1875C4.3125 5.94688 4.11562 5.75 3.875 5.75ZM6.5 5.75C6.25938 5.75 6.0625 5.94688 6.0625 6.1875V12.3125C6.0625 12.5531 6.25938 12.75 6.5 12.75C6.74062 12.75 6.9375 12.5531 6.9375 12.3125V6.1875C6.9375 5.94688 6.74062 5.75 6.5 5.75ZM9.125 5.75C8.88437 5.75 8.6875 5.94688 8.6875 6.1875V12.3125C8.6875 12.5531 8.88437 12.75 9.125 12.75C9.36563 12.75 9.5625 12.5531 9.5625 12.3125V6.1875C9.5625 5.94688 9.36563 5.75 9.125 5.75Z" fill="#212121"/></svg></button>';
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
//                $html .= '<button class="delete-button">🗑</button>';
                $html .= '<button class="delete-button"><svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.07187 0.983984C4.21953 0.685938 4.52305 0.5 4.85391 0.5H8.14609C8.47695 0.5 8.78047 0.685938 8.92812 0.983984L9.125 1.375H11.75C12.234 1.375 12.625 1.76602 12.625 2.25C12.625 2.73398 12.234 3.125 11.75 3.125H1.25C0.766016 3.125 0.375 2.73398 0.375 2.25C0.375 1.76602 0.766016 1.375 1.25 1.375H3.875L4.07187 0.983984ZM1.25 4H11.75V12.75C11.75 13.7152 10.9652 14.5 10 14.5H3C2.03477 14.5 1.25 13.7152 1.25 12.75V4ZM3.875 5.75C3.63438 5.75 3.4375 5.94688 3.4375 6.1875V12.3125C3.4375 12.5531 3.63438 12.75 3.875 12.75C4.11562 12.75 4.3125 12.5531 4.3125 12.3125V6.1875C4.3125 5.94688 4.11562 5.75 3.875 5.75ZM6.5 5.75C6.25938 5.75 6.0625 5.94688 6.0625 6.1875V12.3125C6.0625 12.5531 6.25938 12.75 6.5 12.75C6.74062 12.75 6.9375 12.5531 6.9375 12.3125V6.1875C6.9375 5.94688 6.74062 5.75 6.5 5.75ZM9.125 5.75C8.88437 5.75 8.6875 5.94688 8.6875 6.1875V12.3125C8.6875 12.5531 8.88437 12.75 9.125 12.75C9.36563 12.75 9.5625 12.5531 9.5625 12.3125V6.1875C9.5625 5.94688 9.36563 5.75 9.125 5.75Z" fill="#212121"/></svg></button>';
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
                                <!--                                <span class="dropdown-arrow">▼</span>-->
                                <span class="dropdown-arrow"><span>Αλλαγή</span>
                                    <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.8168 4.97901C13.7789 4.88764 13.7149 4.80954 13.6327 4.75458C13.5504 4.69963 13.4538 4.67028 13.3549 4.67026H3.35489C3.25594 4.67018 3.15919 4.69947 3.07689 4.7544C2.9946 4.80934 2.93045 4.88745 2.89257 4.97887C2.8547 5.07028 2.8448 5.17087 2.86412 5.26791C2.88345 5.36496 2.93113 5.45408 3.00114 5.52401L8.00114 10.524C8.04757 10.5705 8.10272 10.6074 8.16342 10.6325C8.22411 10.6577 8.28918 10.6707 8.35489 10.6707C8.42059 10.6707 8.48566 10.6577 8.54636 10.6325C8.60706 10.6074 8.6622 10.5705 8.70864 10.524L13.7086 5.52401C13.7785 5.45405 13.8261 5.36492 13.8454 5.26791C13.8646 5.1709 13.8547 5.07036 13.8168 4.97901Z" fill="#212121"/>
                                    </svg>
                                </span>
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
