( function() {
    document.addEventListener('DOMContentLoaded', function() {
        
//        const { registerBlockType } = wp.blocks;
//        const { useBlockProps } = wp.blockEditor;
//        const { createElement } = wp.element;
//
//        registerBlockType('ybh/checkout-donation', {
//            title: 'Checkout Donation',
//            category: 'woocommerce',
//            edit: () =>
//                createElement('div', useBlockProps(), '[Donation Form Here]'),
//            save: () =>
//                createElement('div', useBlockProps.save(), '[Donation Form Here]'),
//        });
//
//        if ( !window.wc || !window.wc.blocksCheckout ) {
//            console.error( 'WooCommerce Blocks API not available.' );
//            return;
//        }

//        const { registerCheckoutBlock } = window.wc.blocksCheckout;
//        const { createElement } = window.wp.element;
//        const { SelectControl } = window.wp.components;
//
//        registerCheckoutBlock({
//            metadata: { name: 'custom-checkout-field', title: 'Custom Checkout Field' }, 
//            component: () => createElement(
//                'div',
//                { className: 'custom-checkout-field', style: { marginBottom: '15px' } },
//                createElement(
//                    'label',
//                    { htmlFor: 'how-did-you-hear' },
//                    wp.i18n.__( 'How Did You Hear About Us?', 'you-be-hero' )
//                ),
//                createElement(
//                    SelectControl,
//                    {
//                        id: 'how-did-you-hear',
//                        options: [
//                            { label: 'Google', value: 'google' },
//                            { label: 'Social Media', value: 'social' },
//                            { label: 'Friend Recommendation', value: 'friend' },
//                            { label: 'Other', value: 'other' },
//                        ],
//                        onChange: (value) => console.log('Selected:', value),
//                    }
//                )
//            )
//        });

    });
} )();
