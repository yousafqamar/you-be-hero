(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 */
	 $(function() {
		 document.getElementById('fetch-token')?.addEventListener('click', function() {
			 let button = this;
			 button.disabled = true;
			 document.getElementById('token-status').innerText = "Fetching token...";

			 fetch(ajaxurl+'?action=ybh_get_token', {
				 method: 'POST',
				 headers: { 'Content-Type': 'application/json' }
			 })
				 .then(response => response.json())
				 .then(data => {
					 if (data.success) {
						 document.getElementById('token-status').innerText = "Token received successfully! Reloading...";
						 setTimeout(() => location.reload(), 2000);
					 } else {
						 document.getElementById('token-status').innerText = "Error: " + data.message;
						 button.disabled = false;
					 }
				 })
				 .catch(error => {
					 document.getElementById('token-status').innerText = "Failed to fetch token.";
					 button.disabled = false;
				 });
		 });
	 });
	 /*
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );
