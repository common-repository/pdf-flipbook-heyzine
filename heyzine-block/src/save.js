/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 */
export default function save( { attributes } ) {
	const {
		heyzineSelected,
		heyzineTitle,
		heyzineSubTitle,
		heyzineDescription,
		heyzineLinkPage,
		showTitle,
		showSubTitle,
		showDescription,
		showLink,
		responsiveWidth,
		flibookWidth,
		flibookHeight
	} = attributes;

	return (
		<div { ...useBlockProps.save() }>
			{ heyzineSelected && (
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
	);
}
