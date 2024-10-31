document.addEventListener( 'DOMContentLoaded', () => {
	let button = document.getElementById( 'cl-upload-heyzine-btn' );

	let show_title = false;
	let show_sub_title = false;
	let show_description = false;
	let heyzine_link = '';
	let title = '';
	let sub_title = '';
	let description = '';
	let show_link = '';
	let responsive_width = true
	let flibook_width = 800;
	let flibook_height = 500;
	let flibook_page = 0;

	if ( button ) {
		button.addEventListener( 'click', ( e ) => {
			let media_uploader;

			e.preventDefault();

			// If the media frame already exists, reopen it.
			if ( media_uploader ) {
				media_uploader.open();
				return;
			}

			media_uploader = wp.media.frames.media_uploader = wp.media( {
				title: CL_HEYZINE.title,
				button: {
					text: CL_HEYZINE.button,
				},
				multiple: false,
				library: {
					type: [
						'application/pdf',
						'application/msword',
						'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
						'application/vnd.openxmlformats-officedocument.presentationml.presentation',
						'application/vnd.ms-powerpoint',
						'application/vnd.oasis.opendocument.text',
						'application/rtf',
					]
				}
			} );

			media_uploader.on( 'select', () => {
				let attachment         = media_uploader.state().get( 'selection' ).first().toJSON();
				let url                = attachment.url;
				let heyzine_url        = document.getElementById( 'cl-upload-heyzine-url' );
				let btn_create_heyzine = document.getElementById( 'cl-create-heyzine-btn' );

				if ( heyzine_url ) {
					heyzine_url.value = url;
					btn_create_heyzine.disabled = false;
				}

			} );

			media_uploader.open();
		} );
	}

	const set_options_page_select = ( n_pages ) => {
		let select = document.getElementById( 'cl_heyzine_select_page' );

		select.innerHTML = '';

		if ( 0 === n_pages ) {
			select.disabled = true;
			return;
		}

		for ( let i = 1; i <= n_pages; i++ ) {
			let option = document.createElement( 'option' );
			option.value = i;
			option.text = CL_HEYZINE.page + ' ' + i;
			select.add( option );
		}

		select.disabled = false;
	}

	const update_shortcode = () => {
		let shortcode = '[cl_heyzine';

		if ( responsive_width ) {
			shortcode += ' responsive_width="true"';
		} else {
			shortcode += ' flipbook_width="' + flibook_width + '"';
		}

		if ( flibook_height ) {
			shortcode += ' flipbook_height="' + flibook_height + '"';
		}

		if ( show_title ) {
			shortcode += ' title="' + title + '"';
		}

		if ( show_sub_title ) {
			shortcode += ' sub_title="' + sub_title + '"';
		}

		if ( show_description ) {
			shortcode += ' description="' + description + '"';
		}

		if ( show_link ) {
			shortcode += ' show_link="true"';
		}

		if ( heyzine_link ) {
			shortcode += ' heyzine_link="' + heyzine_link + '"';
		}

		if ( flibook_page ) {
			shortcode += ' heyzine_page="' + flibook_page + '"';
		}

		shortcode += ']';

		if ( ! heyzine_link ) {
			shortcode = '[cl_heyzine]';
		}

		document.getElementById( 'cl-heyzine-shortcode' ).innerText = shortcode;
	}

	document.getElementById( 'responsive_width' ).addEventListener( 'change', ( e ) => {
		if ( e.target.checked ) {
			responsive_width = true;
			document.getElementById( 'flibook_width' ).disabled = true;
		} else {
			responsive_width = false;
			document.getElementById( 'flibook_width' ).disabled = false;
		}
		update_shortcode();
	} );

	document.getElementById( 'flibook_width' ).addEventListener( 'change', ( e ) => {
		flibook_width = e.target.value;
		document.getElementById( 'flipbook_width_value' ).textContent = '(' + e.target.value + 'px)';
		update_shortcode();
	} );

	document.getElementById( 'flibook_height' ).addEventListener( 'change', ( e ) => {
		flibook_height = e.target.value;
		document.getElementById( 'flipbook_height_value' ).textContent = '(' + e.target.value + 'px)';
		update_shortcode();
	} );

	document.getElementById( 'show_title' ).addEventListener( 'change', ( e ) => {
		if ( e.target.checked ) {
			show_title = true;
		} else {
			show_title = false;
		}
		update_shortcode();
	} );

	document.getElementById( 'show_sub_title' ).addEventListener( 'change', ( e ) => {
		if ( e.target.checked ) {
			show_sub_title = true;
		} else {
			show_sub_title = false;
		}
		update_shortcode();
	} );

	document.getElementById( 'show_description' ).addEventListener( 'change', ( e ) => {
		if ( e.target.checked ) {
			show_description = true;
		} else {
			show_description = false;
		}
		update_shortcode();
	} );

	document.getElementById( 'show_link' ).addEventListener( 'change', ( e ) => {
		if ( e.target.checked ) {
			show_link = true;
		} else {
			show_link = false;
		}
		update_shortcode();
	} );

	document.getElementById( 'cl_heyzine_select' ).addEventListener( 'change', ( e ) => {
		const selectedOption = e.target.selectedOptions[0];
		const customize      = document.getElementById( 'cl_heyzine_customize' );

		heyzine_link = e.target.value;

		if ( 'none' === heyzine_link ) {
			title        = '';
			sub_title    = '';
			description  = '';
			show_link    = false;
			heyzine_link = '';
			n_pages      = 0;

			customize.setAttribute( 'data-link', '' );
			customize.disabled = true;
		} else {
			id           = selectedOption.getAttribute( 'data-id' );
			title        = selectedOption.getAttribute( 'data-title' );
			sub_title    = selectedOption.getAttribute( 'data-sub-title' );
			description  = selectedOption.getAttribute( 'data-description' );
			n_pages      = selectedOption.getAttribute( 'data-pages' );

			customize.setAttribute( 'data-link', id );
			customize.disabled = false;
		}
		set_options_page_select( n_pages );

		update_shortcode();
	} );

	document.getElementById( 'cl_heyzine_select_page' ).addEventListener( 'change', ( e ) => {
		flibook_page = e.target.value;

		update_shortcode();
	} );

	document.getElementById( 'cl-heyzine-shortcode' ).addEventListener( 'click', ( e ) => {
		const codeText = e.target.innerText;

		navigator.clipboard.writeText( codeText )
			.then( () => {
				alert( CL_HEYZINE.copied );
			} )
			.catch( ( error ) => {
				alert( CL_HEYZINE.copy_error );
			} );
	} );

	document.getElementById( 'cl_heyzine_customize' ).addEventListener( 'click', ( e ) => {
		// got to data-link URL on new tab
		const heyzine_id = e.target.getAttribute( 'data-link' );
		window.open( 'https://heyzine.com/admin/view?n=' + heyzine_id, '_blank' );
	} );

} );
