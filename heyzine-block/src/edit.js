import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, Button, ToggleControl, RangeControl, Notice } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import './editor.scss';

/**
 * Structure of block in the context of the editor.
 * This represents what the editor will render when the block is used.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {

	const {
		heyzines,
		heyzineSelected,
		heyzinePages,
		heyzineEmbedPage,
		heyzineTitle,
		heyzineSubTitle,
		heyzineDescription,
		heyzineLink,
		heyzineLinkPage,
		customizeDisabled,
		showTitle,
		showSubTitle,
		showDescription,
		showLink,
		responsiveWidth,
		pagesEnabled,
		flibookWidth,
		flibookHeight
	} = attributes;

	const [ optionsSelectHeyzine, setOptionsSelectHeyzine ] = useState( [] );
	const [ optionsEmbedPage, setOptionsEmbedPage ] = useState( [] );
	const [ isLoading, setIsLoading ] = useState( true );
	const [ heyzinesAvailables, setHeyzinesAvailables ] = useState( false );

	useEffect( () => {
		const loadHeyzines = async () => {
			// Load flipbooks from WP API.
			setIsLoading( true );
			const res = await apiFetch(
				{
					path: '/heyzine/v1/flipbooks'
				}
			);
			setIsLoading( false) ;

			if ( res.length === 0 ) {
				setHeyzinesAvailables( false );
			} else {
				setHeyzinesAvailables( true );
			}

			setAttributes( { heyzines: res } );

			// Load Select control with all flipbooks titles and IDs
			const options = res.map(
				( heyzine ) => ( {
						value: heyzine.id,
						label: heyzine.title,
					}
				)
			);
			options.unshift( {
				value: '',
				label: __( 'Select a Heyzine:', 'pdf-flipbook-heyzine' ),
			} );
			setOptionsSelectHeyzine( options );

			// Initiliaze embed page select control with only first page
			const optionsPage = [
				{
					label: __( 'Page', 'pdf-flipbook-heyzine' ) + ' 1',
					value: 1
				}
			];
			setOptionsEmbedPage( optionsPage );

			// We have a flipbook selected, so we can enable customize button
			if ( heyzineSelected ) {
				setAttributes( {
					customizeDisabled: false,
				} );

				loadPagesOnSelect( heyzinePages );
				console.log( 'Embed Page: ' + heyzineEmbedPage );
			}

		};

		loadHeyzines();
	}, [] );

	const loadPagesOnSelect = ( pages ) => {
		const optionsPage = [];
		for ( let i = 1; i <= pages; i++ ) {
			optionsPage.push(
				{
					label: __( 'Page', 'pdf-flipbook-heyzine' ) + ' ' + i,
					value: i
				}
			);
		}
		setOptionsEmbedPage( optionsPage );
	};

	const onChangeHeyzineSelected = ( val ) => {
		const selectedHeyzine = heyzines.find( ( heyzine ) => heyzine.id === val );

		setAttributes( {
			heyzineSelected: val,
		} );

		if ( selectedHeyzine ) {
			setAttributes( {
				heyzineTitle: selectedHeyzine.title,
				heyzineSubTitle: selectedHeyzine.subtitle,
				heyzineDescription: selectedHeyzine.description,
				heyzinePages: selectedHeyzine.pages,
				heyzineLink: selectedHeyzine.link_custom ? selectedHeyzine.link_custom : selectedHeyzine.link_base,
				heyzineLinkPage: selectedHeyzine.link_custom ? selectedHeyzine.link_custom : selectedHeyzine.link_base,
				customizeDisabled: false,
				pagesEnabled: true,
			} );

			loadPagesOnSelect( selectedHeyzine.pages );
		} else {
			setAttributes( {
				pagesEnabled: false,
				customizeDisabled: false,
				heyzineLinkPage: '',
			} );
			loadPagesOnSelect( 1 );
		}

	};

	const onClickCustomize = () => {
		const customizeLink = 'https://heyzine.com/admin/view?n=' + heyzineSelected;
		window.open( customizeLink, '_blank' );
	};

	const onChangeHeyzinePage = ( val ) => {
		setAttributes( {
			heyzineEmbedPage: Number( val ),
			heyzineLinkPage: heyzineLink + '#page/' + val,
		} );
	};

	const onChangeResponsiveWidth = ( val ) => {
		setAttributes( {
			responsiveWidth: val,
		} );
	};

	const onChangeFlibookWidth = ( val ) => {
		setAttributes( {
			flibookWidth: Number( val ),
		} );
	};

	const onChangeFlibookHeight = ( val ) => {
		setAttributes( {
			flibookHeight: Number( val ),
		} );
	};

	const onChangeShowTitle = ( val ) => {
		setAttributes( {
			showTitle: val,
		} );
	};

	const onChangeShowSubTitle = ( val ) => {
		setAttributes( {
			showSubTitle: val,
		} );
	};

	const onChangeShowDescription = ( val ) => {
		setAttributes( {
			showDescription: val,
		} );
	};

	const onChangeShowLink = ( val ) => {
		setAttributes( {
			showLink: val,
		} );
	};


	return (
		<>
			<InspectorControls>
					<PanelBody
						title={ __( 'Embed', 'pdf-flipbook-heyzine' ) }
					>
						<SelectControl
							__next36pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Select a Heyzine:', 'pdf-flipbook-heyzine' ) }
							help={ __( 'Select the Heyzine title to be inserted', 'pdf-flipbook-heyzine' ) }
							value={ heyzineSelected }
							options={ optionsSelectHeyzine }
							onChange={ onChangeHeyzineSelected }
						/>

						<Button
						__experimentalIsFocusable
						__next40pxDefaultSize
						disabled={ customizeDisabled }
						variant="primary"
						onClick={ onClickCustomize }
						className="cl-heyzine-customize-button"
						>
						{ __( 'Customize', 'pdf-flipbook-heyzine' ) }
						</Button>

						<ToggleControl
							__nextHasNoMarginBottom
							label={ __( 'Responsive width', 'pdf-flipbook-heyzine' ) }
							help={ __( 'Determines whether the width will be responsive and depending of the Heyzine height', 'pdf-flipbook-heyzine' ) }
							checked={ responsiveWidth }
							onChange={ onChangeResponsiveWidth }
						/>

						<RangeControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Flipbook width', 'pdf-flipbook-heyzine' ) }
							help={ __( 'Sets the width of the embedded Heyzine', 'pdf-flipbook-heyzine' ) }
							initialPosition={ flibookWidth }
							currentInput={ flibookWidth }
							disabled={ responsiveWidth }
							step={10}
							max={1200}
							min={150}
							onChange={ onChangeFlibookWidth }
						/>

						<RangeControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={ __( 'Flipbook height', 'pdf-flipbook-heyzine' ) }
							help={ __( 'Sets the height of the embedded Heyzine', 'pdf-flipbook-heyzine' ) }
							initialPosition={ flibookHeight }
							currentInput={ flibookHeight }
							step={10}
							max={900}
							min={100}
							onChange={ onChangeFlibookHeight }
						/>

						<SelectControl
							__next36pxDefaultSize
							__nextHasNoMarginBottom
							value={ heyzineEmbedPage }
							options={ optionsEmbedPage }
							onChange={ onChangeHeyzinePage }
						/>
					</PanelBody>

					<PanelBody
						title={ __( 'Additional options', 'pdf-flipbook-heyzine' ) }
						initialOpen={ false }
					>
						<ToggleControl
								__nextHasNoMarginBottom
								label={ __( 'Show Heyzine title', 'pdf-flipbook-heyzine' ) }
								help={ __( 'Determines whether to display Heyzine title above the embed', 'pdf-flipbook-heyzine' ) }
								checked={ showTitle }
								onChange={ onChangeShowTitle }
							/>

						<ToggleControl
							__nextHasNoMarginBottom
							label={ __( 'Show Heyzine subtitle', 'pdf-flipbook-heyzine' ) }
							help={ __( 'Determines whether to display Heyzine subtitle above the embed', 'pdf-flipbook-heyzine' ) }
							checked={ showSubTitle }
							onChange={ onChangeShowSubTitle }
						/>

						<ToggleControl
							__nextHasNoMarginBottom
							label={ __( 'Show Heyzine description', 'pdf-flipbook-heyzine' ) }
							help={ __( 'Determines whether to display Heyzine description above the embed', 'pdf-flipbook-heyzine' ) }
							checked={ showDescription }
							onChange={ onChangeShowDescription }
						/>

						<ToggleControl
							__nextHasNoMarginBottom
							label={ __( 'Show Heyzine link to heyzine page', 'pdf-flipbook-heyzine' ) }
							help={ __( 'Determines whether to display Heyzine link (to heyzine page) above the embed', 'pdf-flipbook-heyzine' ) }
							checked={ showLink }
							onChange={ onChangeShowLink }
						/>
					</PanelBody>
			</InspectorControls>

			<div { ...useBlockProps() }>
				{ isLoading && (
					__( 'Loading...', 'pdf-flipbook-heyzine' )
				) }

				{ ! heyzineSelected && (
					<Notice status="info">
						{ __( 'Select a Heyzine to embed', 'pdf-flipbook-heyzine' ) }
					</Notice>
				) }

				{ ! heyzinesAvailables && (
					<Notice status="info">
						{ __( 'There are no Heyzines availables', 'pdf-flipbook-heyzine' ) }
					</Notice>
				) }

				{ heyzinesAvailables && heyzineSelected && (
					<div className="cl-heyzine-embed">

						{ showTitle && (
							<p className="cl-heyzine-title">
								{ heyzineTitle }
							</p>
						) }

						{ showSubTitle && (
							<p className="cl-heyzine-subtitle">
								{ heyzineSubTitle }
							</p>
						) }

						{ showDescription && (
							<p className="cl-heyzine-description">
								{ heyzineDescription }
							</p>
						) }

						{ showLink && (
							<p className="cl-heyzine-link">
								<a href={ heyzineLinkPage } target='_blank' rel='noreferrer noopener'>{ heyzineLinkPage }</a>
							</p>
						) }

						<iframe className="cl-heyzine-iframe fp-iframe"
							allow="fullscreen"
							style= {{
								border: "0px",
								width: responsiveWidth ? (
									"100%"
									) : (
										flibookWidth + "px"
									),
								height: flibookHeight + "px"
							}}
							src= { heyzineLinkPage }
						>
						</iframe>
					</div>
				) }
			</div>
		</>
	);
}
