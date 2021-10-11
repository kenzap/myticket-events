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
registerBlockType( 'myticket-events/listing-05', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'MyTicket Hall', 'myticket-events' ), // Block title.
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
		ticketsPerBooking: {
			type: 'string',
			default: '10'
		},
		renderType: {
			type: 'string',
			default: '1'
		},
		seatMode: {
			type: 'string',
			default: 'circle'
		},
		snSize: {
			type: 'number',
			default: 12
		},
		hideNumbers: {
			type: 'boolean',
			default: false
		},
		numOpacity: {
			type: 'number',
			default: 50
		},
		numOpacity2: {
			type: 'number',
			default: 50
		},
		availableColor: {
			type: 'string',
			default: '#b1e2a5'
		},
		soldoutColor: {
			type: 'string',
			default: '#afc3e5'
		},
		selectedColor: {
			type: 'string',
			default: '#f78da7'
		},
		seatsColor: {
			type: 'string',
			default: '#333333'
		},
		dwidth: {
			type: 'string',
			default: '640'
		},
		mwidth: {
			type: 'string',
			default: '400'
		},
		sminwidth: {
			type: 'string',
			default: '640'
		},
		smaxwidth: {
			type: 'string',
			default: '400'
		},
		showArrows: {
			type: 'boolena',
			default: false
		},
		title: {
			type: 'string',
			default: '',
		},
		subtitle: {
			type: 'string',
			default: '',
		},
		desc: {
			type: 'string',
			default: '',
		},
		note: {
			type: 'string',
			default: 'Move your cursor over a seat to view how the stage looks from that position. Click on the seat to place the relevant ticket in your cart.',
		},
		cta: {
			type: 'string',
			default: 'Add to Cart',
		},

		checkFilter: {
			type: 'boolean',
			default: true
		},
		filterLocations: {
			type: 'string',
			default: ''
		},
		eventID: {
			type: 'string',
			default: ''
		},
		serverSide: {
			type: 'boolena',
			default: false
		},
		mainColor: {
			type: 'string',
			default: '#ff6600'
		},
		preview: {
            type: 'boolean',
            default: false,
        },
	},

	/**
	 * The example function provides block preview in the editor.
	 * This represents what the editor will render when the block is used.
	 */
	example: { attributes: { preview: true } },

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: function( props ) {

		if ( props.attributes.preview ) return ( <img src={ `${ window.kenzap_myticket_path + 'assets/block_preview-05.jpg' }` } /> );

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
