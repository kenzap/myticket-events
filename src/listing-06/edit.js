const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { serverSideRender: ServerSideRender } = wp;
const { InspectorControls, PanelColorSettings, MediaUpload } = wp.editor;
const { RangeControl, PanelBody, SelectControl, TextControl, ExternalLink, PanelRow, Button } = wp.components;

import { InspectorContainer, ContainerEdit } from '../commonComponents/container/container';

/**
 * The edit function describes the structure of your block in the context of the editor.
 * This represents what the editor will render when the block is used.
 *
 * The "edit" property must be a valid function.
 * @param {Object} props - attributes
 * @returns {Node} rendered component
 */
export default class Edit extends Component {

    render() {
        const {
            className,
            attributes,
            setAttributes,
        } = this.props;

        return (
            <div className={ className }>
                <InspectorControls
                    setAttributes={ setAttributes }
                    { ...attributes }
                >
                    <PanelBody
                        title={ __( 'General', 'myticket-events'  ) }
                        initialOpen={ false }
                    >

                        <RangeControl
                            label={ __( 'Records per page', 'myticket-events'  ) }
                            value={ attributes.per_page }
                            onChange={ ( value ) => setAttributes( { per_page: value } ) }
                            min={ 1 }
                            max={ 50 }
                            help={ __( 'Specify the maximum number of events listed per single page.', 'myticket-events'  ) }
                        />
                        
                        <TextControl
                            label={ __( 'Category', 'myticket-events' ) }
                            value={ attributes.category }
                            onChange={ ( category ) => setAttributes( { category } ) }
                            help={ __( 'Restrict all records to certain category. To view categories go to Products > Categories section.', 'myticket-events' ) }
                        />

                        <SelectControl
                            label={ __( 'Type', 'myticket-events' ) }
                            checked={ attributes.type }
                            options={[
                                { label:  __( 'All', 'myticket-events' ) , value: '' },
                                { label:  __( 'Upcomming', 'myticket-events' ) , value: 'upcomming' },
                                { label:  __( 'Past', 'myticket-events' ) , value: 'past' },
                            ]}
                            help={ __( 'Choose how current time affects listing.', 'myticket-events' ) }
                            onChange={ (type) => setAttributes( { type } ) }
                        />

                        <SelectControl
                            label={ __( 'Default order', 'myticket-events' ) }
                            checked={ attributes.order }
                            options={[
                                { label:  __( 'None', 'myticket-events' ) , value: '' },
                                { label:  __( 'Alphabetical', 'myticket-events' ) , value: 'alphabetical' },
                                { label:  __( 'Popularity', 'myticket-events' ) , value: 'popularity' },
                                { label:  __( 'Rating', 'myticket-events' ) , value: 'rating' },
                                { label:  __( 'Newest', 'myticket-events' ) , value: 'newest' },
                                { label:  __( 'Lowest price', 'myticket-events' ) , value: 'lowestprice' },
                                { label: __( 'Highest price', 'myticket-events' ) , value: 'highestprice' },
                            ]}
                            help={ __( 'Choose default sorting method', 'myticket-events' ) }
                            onChange={ (order) => setAttributes( { order } ) }
                        />
                        
                        <RangeControl
                            label={ __( 'Low stock notice', 'myticket-events'  ) }
                            value={ attributes.low_stock }
                            onChange={ ( value ) => setAttributes( { low_stock: value } ) }
                            min={ 0 }
                            max={ 250 }
                            help={ __( 'Specify when to trigger limited stock notice.', 'myticket-events'  ) }
                        />

                        <RangeControl
                            label={ __( 'Border radius', 'kenzap-cta' ) }
                            value={ attributes.borderRadius }
                            onChange={ ( borderRadius ) => setAttributes( { borderRadius } ) }
                            min={ 0 }
                            max={ 50 }
                        />

                        <p style={ { marginBottom: '5px' } }>{ __( 'Location icon (SVG only)', 'myticket-events' ) }</p>
                        <MediaUpload
                            onSelect={ ( media ) => {
                                setAttributes( { img1: media.url } )
                            } }
                            value={ attributes.img1 }
                            allowedTypes={ [ 'image/svg' ] }
                            render={ ( mediaUploadProps ) => (

                            <Fragment>
                                { ( attributes.img1 !== 'none' ) ? (
                                    <Fragment>
                                        <Button
                                            isDefault
                                            onClick={ () => { 
                                                setAttributes( { img1: 'none' } )
                                            } }
                                        >
                                        { __( 'Remove', 'myticket-events' ) }
                                        </Button>
                                        <div
                                            style={ {
                                                width: '27px',
                                                height: '27px',
                                                display: 'inline-block',
                                                margin: '0 0 8px 5px',
                                                backgroundImage: `url(${ [ attributes.img1 ? attributes.img1 : '' ] })`,
                                                backgroundRepeat: 'no-repeat',
                                                backgroundSize: 'cover',
                                            } }
                                        />

                                    </Fragment>
                                ) : (
                                    <Button isDefault onClick={ mediaUploadProps.open } style={ { margin: '0 0 8px 0px', } }>
                                        { __( 'Upload/Choose', 'myticket-events' ) }
                                    </Button>
                                ) }
                            </Fragment>

                            ) }
                        />

                        <PanelColorSettings
                            title={ __( 'Main Color', 'myticket-events'  ) }
                            initialOpen={ true }
                            colorSettings={ [
                                    {
                                        value: attributes.mainColor,
                                        onChange: ( value ) => {
                                            return setAttributes( { mainColor: value } );
                                        },
                                        label: __( 'Selected', 'myticket-events'  ),
                                    },
                                ] }
                        />

                        <SelectControl
                            label={ __( 'Query relation', 'myticket-events'  ) }
                            checked={ attributes.relation }
                            options={[
                                { label:  __( 'AND', 'myticket-events' ) , value: '' },
                                { label:  __( 'OR', 'myticket-events' ) , value: 'popularity' },
                            ]}
                            help={ __('This rule tells database how to filter results. If user selects two categories Golf and Swimming AND will show only those events that are in Golf and Swimming simultaneously. If user selects Golf and Swimming categories OR will picks up all events within Golf category and unites them with all events from Swimming category. The more checkboxes user ticks with OR relation the more results will be shown and vice versa if AND is selected.', 'myticket-events' ) }
                            onChange={ (relation) => setAttributes( { relation } ) }
                        />

                    </PanelBody>

                    <PanelColorSettings
                        title={ __( 'Colors', 'myticket-events'  ) }
                        initialOpen={ false }
                            colorSettings={ [
                                {
                                    value: attributes.textColor,
                                    onChange: ( value ) => {
                                        return setAttributes( { textColor: value } );
                                    },
                                    label: __( 'Text', 'myticket-events'  ),
                                },
                                {
                                    value: attributes.mainColor,
                                    onChange: ( value ) => {
                                        return setAttributes( { mainColor: value } );
                                    },
                                    label: __( 'Main', 'myticket-events'  ),
                                },
                                {
                                    value: attributes.subColor,
                                    onChange: ( value ) => {
                                        return setAttributes( { subColor: value } );
                                    },
                                    label: __( 'Limited', 'myticket-events'  ),
                                },
                            ] }
                    />
                    
                    <InspectorContainer
                        setAttributes={ setAttributes }
                        { ...attributes }
                        withPadding
                        withWidth100
                        withBackground
                    />

                    <PanelBody
                        title={ __( 'Support', 'myticket-events' ) }
                        initialOpen={ false }
                    >

                        <PanelRow>
                            { __( 'For additional customization features and assistance. Please contact our support team: ', 'myticket-events' ) } 
                        </PanelRow>

                        <PanelRow>
                            <ExternalLink 
                                href="https://kenzap.com/contacts/"  >
                                { __( 'Kenzap Support', 'myticket-events' ) }
                            </ExternalLink>
                        </PanelRow>

                    </PanelBody>
                </InspectorControls>

                <ServerSideRender
                    block="myticket-events/listing-06"
                    attributes={ {
                        align: attributes.align,
                        textColor: attributes.textColor,
                        mainColor: attributes.mainColor,
                        subColor: attributes.subColor,
                        category: attributes.category,
                        img1: attributes.img1,
                        order: attributes.order,
                        type: attributes.type,
                        low_stock: attributes.low_stock,
                        per_page: attributes.per_page,
                        backgroundColor: attributes.backgroundColor,
                        borderRadius: attributes.borderRadius,
                        serverSide: true,
                    } }
                />
            </div>
        );
    }
}
