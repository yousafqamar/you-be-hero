(function (wp) {
    var el = wp.element.createElement;
    var addFilter = wp.hooks.addFilter;
    var PanelBody = wp.components.PanelBody;
    var SelectControl = wp.components.SelectControl;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var createHigherOrderComponent = wp.compose.createHigherOrderComponent;

    // Possible positions for the donation form in checkout
    var donationPositions = [
        { label: 'Before Checkout Form', value: 'woocommerce_before_checkout_form' },
        { label: 'After Billing Fields', value: 'woocommerce_after_checkout_billing_form' },
        { label: 'Before Order Notes', value: 'woocommerce_before_order_notes' },
        { label: 'After Payment Section', value: 'woocommerce_review_order_after_payment' }
    ];

    // Extend WooCommerce Checkout Block settings
    var withYBHCheckoutDonationSettings = createHigherOrderComponent(function (BlockEdit) {
        return function (props) {
            console.log('props:', props);
            if (props.name !== 'woocommerce/checkout') {
                return el(BlockEdit, props);
            }

            return el(
                wp.element.Fragment,
                {},
                el(BlockEdit, props),
                el(
                    InspectorControls,
                    {},
                    el(
                        PanelBody,
                        { title: 'YouBeHero Settings', initialOpen: true },
                        el(SelectControl, {
                            label: 'Donation Form Position',
                            value: props.attributes.donationPosition || 'woocommerce_after_checkout_billing_form',
                            options: donationPositions,
                            onChange: function (newPosition) {
                                props.setAttributes({ donationPosition: newPosition });
                            }
                        })
                    )
                )
            );
        };
    }, 'withYBHCheckoutDonationSettings');

    addFilter(
        'editor.BlockEdit',
        'ybh-checkout-donation/checkout-block-settings',
        withYBHCheckoutDonationSettings
    );
})(window.wp);
