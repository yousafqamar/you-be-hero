<?php
$causes = $attributes['causes'];
$amounts = $attributes['amounts'];
//var_dump('$causes');
//var_dump($causes);
?>

    <div class="donation-checkout-widget youbehero-donation-widget">
    <h3><?php _e('Make a Donation', 'woocommerce'); ?></h3>
    <p><?php _e('Select a cause and donation amount:', 'woocommerce'); ?></p>
    <select id="donation-cause" name="donation_cause">
        <option value=""><?php _e('Select a cause', 'woocommerce'); ?></option>
        <?php foreach ($causes as $cause) : ?>
            <option class="<?php echo $cause['value']; ?>" value="<?php echo $cause['value']; ?>">
                <?php echo esc_html($cause['label']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div id="donation-amounts">
        <?php foreach ($amounts as $amount) : ?>
            <label class="radio-button border border-blue-500 text-blue-500 rounded-lg py-2">
                <input type="radio" name="donation_amount" value="<?php echo esc_attr($amount); ?>">
                <?php echo esc_html('$' . $amount); ?>
            </label>
        <?php endforeach; ?>
    </div>
  </div>

    <!-- Added new example code to use for widget UI -->
    <div class="donation-box">
        <h3>ΟΛΟΚΛΗΡΩΣΗ ΑΓΟΡΑΣ</h3>
        <p>Θα θέλατε να κάνετε μια δωρεά; <span class="amount-box">0,00€</span></p>
        <div class="donation-item">
            <img src="https://via.placeholder.com/40" alt="logo">
            <span>Save your hood</span>
            <select>
                <option>Αλλαγή</option>
            </select>
        </div>
        <div class="donation-buttons">
            <button>1€</button>
            <button>3€</button>
            <button>5€</button>
            <button style="border-color: red; color: red;">🗑</button>
        </div>
    </div>