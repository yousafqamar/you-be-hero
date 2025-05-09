import { __ } from '@wordpress/i18n'; // For translations
import { registerBlockType } from '@wordpress/blocks'; // For block registration
import { useBlockProps } from '@wordpress/block-editor'; // For block props
import { useEffect, useState, useCallback } from '@wordpress/element';
import { SelectControl } from '@wordpress/components'; // For UI components

import { useSelect, useDispatch } from '@wordpress/data';
import { debounce } from 'lodash';


import { registerPlugin } from '@wordpress/plugins';

const render = () => {};

registerPlugin( 'ybh-chekcout-donation', {
	render,
	scope: 'woocommerce-checkout',
} );

import metadata from './block.json';
// Register the block
registerBlockType(metadata.name, {
    title: __('YouBeHero Checkout form', 'woocommerce'), // Block title
    icon: metadata.icon, // Block icon
    category: metadata.category, // Block category
    attributes: {
        causes: {
            type: 'array',
            default: [],
        },
        amounts: {
            type: 'array',
            default: [],
        },
    },
    edit: ({ attributes, setAttributes }) => {
//        const { extensionData, setExtensionData } = useExtensionData('donation-widget/donation-block');
        const { causes = [], amounts = [] } = attributes;

        return (
            <div {...useBlockProps()}>
                <h3>{__('YouBeHero Donation', 'woocommerce')}</h3>
                <p>{__('YouBeHero donation widget will reside here. For easy relocation use List overview option (Shift+Alt+O)', 'woocommerce')}</p>
                
            </div>
        );
    },
    save: () => {
        // Server-side rendering is handled by render.php
        return null;
    },
});

/**
 * External dependencies
 */
//import { registerCheckoutBlock } from '@woocommerce/blocks-checkout';
///**
// * Internal dependencies
// */
//import { Block } from './block';
////import metadata from './block.json';
//
//registerCheckoutBlock( {
//	metadata,
//	component: Block,
//} );


//import { registerCheckoutFields } from '@woocommerce/blocks-checkout';
//
//registerCheckoutFields('custom-checkout-donation', {
//    donation_amount: {
//        label: 'Donation Amount',
//        type: 'number',
//        required: false,
//        defaultValue: '',
//        placeholder: 'Enter donation amount',
//    },
//});

