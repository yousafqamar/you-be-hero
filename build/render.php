<?php
$causes = $attributes['causes'];
$amounts = $attributes['amounts'];
//var_dump('$causes');
//var_dump($causes);
?>

    <div class="donation-checkout-widget youbehero-donation-widget">
        
        <div class="donation-box">
            <h3><?php _e('Make a Donation', 'woocommerce'); ?></h3>
            <p><?php _e('Select a cause and donation amount:', 'woocommerce'); ?></p>
            <div class="donation-item">
                <img src="https://via.placeholder.com/40" alt="logo">
            <span>Save your hood</span>
                <select id="donation-cause" name="donation_cause">
                    <option value=""><?php _e('Select a cause', 'woocommerce'); ?></option>
                    <?php foreach ($causes as $cause) : ?>
                        <option class="<?php echo $cause['value']; ?>" value="<?php echo $cause['value']; ?>">
                            <?php echo esc_html($cause['label']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div id="donation-amounts" class="donation-buttons">
                <?php foreach ($amounts as $amount) : ?>
                    <label class="radio-button">
                        <input type="radio" name="donation_amount" value="<?php echo esc_attr($amount); ?>" class="visually-hidden">
                        <?php echo esc_html('$' . $amount); ?>
                    </label>
                <?php endforeach; ?>
                    <label class="radio-button">
                        <input type="radio" name="donation_amount" value="0" class="visually-hidden">
                        🗑
                    </label>
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