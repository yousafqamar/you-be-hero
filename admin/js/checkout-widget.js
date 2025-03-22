wp.hooks.addFilter(
    'editor.BlockEdit',
    'donation-widget/ybh-chekcout-donation-block',
    function (BlockEdit) {
        return function (props) {
                console.log(props.name);
            if (props.name === 'woocommerce/checkout'|| props.name ==='woocommerce/checkout-fields-block'|| props.name === 'woocommerce/checkout-totals-block' ) {
                return wp.element.createElement(
                    wp.blockEditor.InnerBlocks,
                    {
                        allowedBlocks: [
                            'core/paragraph',
                            'core/image',
                            'donation-widget/ybh-chekcout-donation-block'
                        ]
                    }
                );
            }
            return wp.element.createElement(BlockEdit, props);
        };
    }
);
