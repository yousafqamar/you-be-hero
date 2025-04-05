/**
 * External dependencies
 */
import { useEffect, useState, useCallback } from '@wordpress/element';
import { SelectControl, TextareaControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useSelect, useDispatch } from '@wordpress/data';
import { debounce } from 'lodash';

/**
 * Internal dependencies
 */
import { options } from './options';

export const Block = ( { checkoutExtensionData, extensions } ) => {
	/**
	 * setExtensionData will update the wc/store/checkout data store with the values supplied. It
	 * can be used to pass data from the client to the server when submitting the checkout form.
	 */
	const { setExtensionData } = checkoutExtensionData;
	/**
	 * Debounce the setExtensionData function to avoid multiple calls to the API when rapidly
	 * changing options.
	 */
	// eslint-disable-next-line react-hooks/exhaustive-deps
	const debouncedSetExtensionData = useCallback(
		debounce( ( namespace, key, value ) => {
			setExtensionData( namespace, key, value );
		}, 1000 ),
		[ setExtensionData ]
	);
        
        const { causes, amounts } = ybh_donation_checkout_params;

        // Populate causes and amounts
        const $causeSelect = $('#donation-cause');
        const $amountsContainer = $('#donation-amounts');
        console.log( document.querySelector(".ybh-dd-option") );

//	const validationErrorId = 'donatoin-widget-other-value';

//	const { setValidationErrors, clearValidationError } = useDispatch(
//		'wc/store/validation'
//	);

//	const validationError = useSelect( ( select ) => {
//		const store = select( 'wc/store/validation' );
//		/**
//		 * [frontend-step-07]
//		 * 📝 Write some code to get the validation error from the `wc/store/validation` data store.
//		 * Using the `getValidationError` selector on the `store` object, get the validation error.
//		 *
//		 * The `validationErrorId` variable can be used to get the validation error. Documentation
//		 * on the validation data store can be found here:
//		 * https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/third-party-developers/extensibility/data-store/validation.md
//		 */
//
//		/**
//		 * [frontend-step-07-extra-credit]
//		 * 💰 Extra credit: In the `useEffect` that handles the "other" textarea, only call
//		 * `clearValidationError` if the validation error is in the data store already.
//		 */
//	} );
	const [
		selectedYbhDonationInstruction,
		setSelectedYbhDonationInstruction,
	] = useState( 'try-again' );
	const [ otherDonationValue, setOtherDonationValue ] = useState( '' );

	/* Handle changing the select's value */
	useEffect( () => {
		/**
		 * [frontend-step-02]
		 * 📝 Using `setExtensionData`, write some code in this useEffect that will run when the
		 * `selectedYbhDonationInstruction` value changes.
		 *
		 * The API of this function is: setExtensionData( namespace, key, value )
		 *
		 * This code should use `setExtensionData` to update the `ybhDonationInstruction` key
		 * in the `donatoin-widget` namespace of the checkout data store.
		 */
		/**
		 * [frontend-step-02-extra-credit-1]
		 * 💰 Extra credit: Ensure the `setExtensionData` function is not called multiple times. We
		 * can use the `debouncedSetExtensionData` function for this. The API is the same.
		 */
	}, [ setExtensionData, selectedYbhDonationInstruction ] );

	/**
	 * [frontend-step-02-extra-credit-2]
	 * 💰 Extra credit: Use a `useState` to track whether the user has interacted with the "other"
	 * textbox. If they have, then the validation error should not be hidden when the user changes
	 * the select's value. If it is "pristine" then we should keep the error hidden.
	 */

	/* Handle changing the "other" value */
	useEffect( () => {
		/**
		 * [frontend-step-03]
		 * 📝 Write some code in this useEffect that will run when the `otherDonationValue` value
		 * changes. This code should use `setExtensionData` to update the `otherDonationValue` key
		 * in the `donatoin-widget` namespace of the checkout data store.
		 */
		/**
		 * [frontend-step-03-extra-credit]
		 * 💰 Extra credit: Ensure the `setExtensionData` function is not called multiple times. We
		 * can use the `debouncedSetExtensionData` function for this. The API is the same.
		 */
		/**
		 * [frontend-step-04]
		 * 📝 Write some code that will use `setValidationErrors` to add an entry to the validation
		 * data store if `otherDonationValue` is empty.
		 *
		 * The API of this function is: `setValidationErrors( errors )`.
		 *
		 * `errors` is an object with the following shape:
		 * {
		 *     [ validationErrorId ]: {
		 *  		message: string,
		 *	    	hidden: boolean,
		 *     }
		 * }
		 *
		 * For now, the error should remain hidden until the user has interacted with the field.
		 *
		 * [frontend-step-04-extra-credit]
		 * 💰 Extra credit: If the `selectedYbhDonationInstruction` is not `other` let's skip
		 * adding the validation error. Make sure to place this code before the
		 * `setValidationErrors` call, thus, the spoiler of [frontend-step-04] comes after the one
		 * of [frontend-step-04-extra-credit].
		 */
		/**
		 * [frontend-step-05]
		 * 📝 Update the above code so that it will use `clearValidationError` to remove the
		 * validation error from the data store if `selectedYbhDonationInstruction` is not
		 * `other`, or if the `otherDonationValue` is not empty.
		 *
		 * The API of `clearValidationError` is: `clearValidationError( validationErrorId )`
		 *
		 * 💡Don't forget to update the dependencies of the `useEffect` when you reference new
		 * functions/variables!
		 */
	}, [
		/**
		 * [frontend-step-06]
		 * 💡 Don't forget to update the dependencies of the `useEffect` when you reference new
		 * functions/variables!
		 */
		otherDonationValue,
		setExtensionData,
	] );

	return (
		<div className="wp-block-ybh-ybhDonation-widget">
			{ /**
			 * [frontend-step-01]
			 * 📝 Go to options.js and add some new options to display in the SelectControl below.
			 */ }
			<SelectControl
				label={ __(
					'would you like to donate?',
					'donatoin-widget'
				) }
				value={ selectedYbhDonationInstruction }
				options={ options }
				onChange={ setSelectedYbhDonationInstruction }
			/>

			{ selectedYbhDonationInstruction === 'other' && (
				<>
					<TextareaControl
						className={
							'donatoin-widget-other-textarea' +
							( validationError?.hidden === false
								? ' has-error'
								: '' )
						}
						onChange={ setOtherDonationValue }
						value={ otherDonationValue }
						required={ true }
						placeholder={ __(
							'Enter donatoin instructions',
							'donatoin-widget'
						) }
					/>
					{ /**
					 * [frontend-step-08]
					 * 📝 Write some code in this block that will render a validation error if the
					 * validation error we're using in the wc/store/validation data store is not
					 * hidden. It's fine to just use a div, and display the `message` property of
					 * the validation error.
					 */ }
				</>
			) }
		</div>
	);
};
