//  Import CSS.
import './style.scss';
import './editor.scss';
import Edit from './edit';
import { blockProps, ContainerSave } from '../commonComponents/container/container';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'myticket-events/listing-06', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'MyTicket Listing 5', 'myticket-events' ), // Block title.
	icon: 'shield', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'layout', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'Events', 'myticket-events' ),
		__( 'Event listing', 'myticket-events' ),
		__( 'Calendar', 'myticket-events' ),
	],
	supports: {
        align: [ 'full', 'wide' ],
    },
	attributes: {
		...blockProps,

        borderRadius: {
            type: 'number',
            default: 5,
        },
		textColor: {
			type: 'string',
			default: '#333'
		},
		subColor: {
			type: 'string',
			default: '#e04242'
		},
		category: {
			type: 'string',
			default: ''
		}, 
		order: {
			type: 'string',
			default: ''
		}, 
		popularity: {
			type: 'string',
			default: ''
		}, 
		relation: {
			type: 'string',
			default: ''
		}, 
		pagination: {
			type: 'boolean',
			default: true
		}, 
		per_page: {
			type: 'number',
			default: 5
		}, 
		low_stock: {
			type: 'number',
			default: 4
		}, 
        img1: {
            type: 'string',
            default: window.kenzap_myticket_path + "images/location.svg",
        },
		order: {
			type: 'string',
			default: ''
		}, 
		type: {
			type: 'string',
			default: ''
		},
		serverSide: {
			type: 'boolena',
			default: false
		},
		mainColor: {
			type: 'string',
			default: '#9376df'
		},
	},
	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: function( props ) {

		return ( <Edit { ...props } /> );

	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save: function( props ) {
		return (
			<div>
				<p>{ __( 'MyTicket Listing 5', 'myticket-events' ) }</p>
			</div>
		);
	},
} );
