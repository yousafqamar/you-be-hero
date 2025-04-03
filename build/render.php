<?php
$causes = $attributes['causes'];
$amounts = $attributes['amounts'];
//var_dump('$causes');
//var_dump($causes);
?>

    <div class="donation-checkout-widget youbehero-donation-widget">
        
        <div class="donation-box">
            <h3><?php _e('Make a Donation', 'woocommerce'); ?></h3>
<!--            <p>--><?php //_e('Select a cause and donation amount:', 'woocommerce'); ?><!--</p>-->
            <div class="donation-item">
<!--                <img src="https://via.placeholder.com/40" alt="logo">-->
<!--                <span>Save your hood</span>-->

            </div>
            <div id="donation-amounts" class="donation-buttons">

            </div>
        </div>
  </div>

<!--    <div class="donation-box">
        <h3><?php _e('Make a Donation', 'woocommerce'); ?></h3>
        <p><?php _e('Select a cause and donation amount:', 'woocommerce'); ?> <span class="amount-box">0,00€</span></p>
        <div class="donation-item">
            <img src="https://via.placeholder.com/40" alt="logo">
            <span>Save your hood</span>
            <select id="donation-cause" name="donation_cause">
                <?php foreach ($causes as $cause) : ?>
                        <option>Αλλαγή</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="donation-buttons donation-amounts">
            <?php foreach ($amounts as $amount) : ?>
                <button name="donation_amount" value="<?php echo esc_attr($amount); ?>"><?php echo esc_html('$' . $amount); ?></button>
            <?php endforeach; ?>
            <button style="border-color: red; color: red;">🗑</button>
        </div>
    </div>-->