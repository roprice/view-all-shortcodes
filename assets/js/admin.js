( function () {
	'use strict';

	var status = document.getElementById( 'view-all-shortcodes-status' );

	/**
	 * Copies text in browsers that do not expose the asynchronous Clipboard API.
	 *
	 * @param {string} text The text to copy.
	 * @return {boolean} Whether the browser reported that the copy succeeded.
	 */
	function copyWithFallback( text ) {
		var textArea = document.createElement( 'textarea' );

		textArea.value = text;
		textArea.setAttribute( 'readonly', '' );
		textArea.style.position = 'fixed';
		textArea.style.opacity = '0';
		document.body.appendChild( textArea );
		textArea.select();

		var copied = false;

		try {
			copied = document.execCommand( 'copy' );
		} catch ( error ) {
			copied = false;
		}

		document.body.removeChild( textArea );
		return copied;
	}

	/**
	 * Announces the result to screen reader users and displays a WordPress notice.
	 *
	 * @param {string} message Notice text.
	 * @param {boolean} isError Whether this is an error notice.
	 */
	function showResult( message, isError ) {
		var notice = document.createElement( 'div' );
		var paragraph = document.createElement( 'p' );

		if ( status ) {
			status.textContent = message;
		}

		notice.className = isError ? 'notice notice-error' : 'notice notice-success';
		paragraph.textContent = message;
		notice.appendChild( paragraph );

		var wrap = document.querySelector( '.wrap' );
		if ( wrap ) {
			wrap.insertBefore( notice, wrap.children[ 1 ] || null );
		}
	}

	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '.view-all-shortcodes-copy' );

		if ( ! button ) {
			return;
		}

		var shortcode = button.getAttribute( 'data-shortcode' );

		if ( ! shortcode ) {
			return;
		}

		if ( navigator.clipboard && window.isSecureContext ) {
			navigator.clipboard.writeText( shortcode ).then(
				function () {
					showResult( button.getAttribute( 'data-success-message' ), false );
				},
				function () {
					showResult( button.getAttribute( 'data-error-message' ), true );
				}
			);
			return;
		}

		var copied = copyWithFallback( shortcode );
		showResult(
			copied ? button.getAttribute( 'data-success-message' ) : button.getAttribute( 'data-error-message' ),
			! copied
		);
	} );
}() );
