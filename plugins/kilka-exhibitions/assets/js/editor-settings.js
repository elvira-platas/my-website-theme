( function ( components, data, editPost, element, i18n, plugins ) {
	'use strict';

	var el = element.createElement;
	var PluginDocumentSettingPanel = editPost.PluginDocumentSettingPanel;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var ToggleControl = components.ToggleControl;
	var __ = i18n.__;

	if ( ! PluginDocumentSettingPanel || ! plugins.registerPlugin ) {
		return;
	}

	function rawText( value ) {
		if ( value && 'object' === typeof value ) {
			return value.raw || '';
		}

		return value || '';
	}

	function ExhibitionInformationPanel() {
		var postType = data.useSelect( function ( select ) {
			return select( 'core/editor' ).getCurrentPostType();
		}, [] );
		var excerpt = data.useSelect( function ( select ) {
			return select( 'core/editor' ).getEditedPostAttribute( 'excerpt' );
		}, [] );
		var meta = data.useSelect( function ( select ) {
			return select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {};
		}, [] );
		var editCurrentPost = data.useDispatch( 'core/editor' ).editPost;

		if ( 'kilka_exhibition' !== postType ) {
			return null;
		}

		function updateMeta( key, value ) {
			var updatedMeta = Object.assign( {}, meta );

			updatedMeta[ key ] = value;
			editCurrentPost( { meta: updatedMeta } );
		}

		return el(
			PluginDocumentSettingPanel,
			{
				name: 'kilka-exhibition-information',
				title: __( 'Exhibition information', 'kilka-exhibitions' ),
				className: 'kilka-exhibition-information-settings'
			},
			el( TextareaControl, {
				label: __( 'Brief description', 'kilka-exhibitions' ),
				help: __( 'Shown near the beginning of the public information panel.', 'kilka-exhibitions' ),
				value: rawText( excerpt ),
				onChange: function ( value ) {
					editCurrentPost( { excerpt: value } );
				}
			} ),
			el( TextControl, {
				label: __( 'Creator', 'kilka-exhibitions' ),
				value: meta.kilka_exhibition_creator || '',
				onChange: function ( value ) {
					updateMeta( 'kilka_exhibition_creator', value );
				}
			} ),
			el( TextControl, {
				label: __( 'Rights notice', 'kilka-exhibitions' ),
				value: meta.kilka_exhibition_copyright_notice || '',
				onChange: function ( value ) {
					updateMeta( 'kilka_exhibition_copyright_notice', value );
				}
			} ),
			el( ToggleControl, {
				label: __( 'Show information panel', 'kilka-exhibitions' ),
				checked: false !== meta.kilka_exhibition_information_panel,
				onChange: function ( value ) {
					updateMeta( 'kilka_exhibition_information_panel', value );
				}
			} )
		);
	}

	plugins.registerPlugin( 'kilka-exhibitions-information', {
		render: ExhibitionInformationPanel
	} );
} )(
	window.wp.components,
	window.wp.data,
	window.wp.editPost,
	window.wp.element,
	window.wp.i18n,
	window.wp.plugins
);
