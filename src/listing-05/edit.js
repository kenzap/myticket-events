const { __ } = wp.i18n; // Import __() from wp.i18n
const { Component } = wp.element;
const { InspectorControls, PanelColorSettings } = wp.editor;
const { CheckboxControl, PanelBody, ServerSideRender, TextControl, TextareaControl, ExternalLink, PanelRow } = wp.components;

import { InspectorContainer } from '../commonComponents/container/container';

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

                        <TextControl
                            label={ __( 'Product ID', 'myticket-events' ) }
                            value={ attributes.eventID }
                            onChange={ (eventID) => setAttributes( { eventID: eventID } ) }
                            help={ __( 'Go to Products > All Products > hover product to view its ID. This setting is mandatory.', 'myticket-events' ) }
                        />

                        <TextControl
                            label={ __( 'Tickets Per Booking', 'myticket-events' ) }
                            type="number"
                            value={ attributes.ticketsPerBooking }
                            onChange={ (value) => setAttributes( { ticketsPerBooking: value } ) }
                            help={ __( 'Maximum amount of tickets one user is allowed to reserve per booking.', 'myticket-events' ) }
                        />

                    </PanelBody>

                    <PanelBody
                        title={ __( 'Colors', 'myticket-events'  ) }
                        initialOpen={ false }
                    >

                        <PanelColorSettings
                            title={ __( 'Highlight', 'myticket-events'  ) }
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

                        <PanelColorSettings
                            title={ __( 'Availability', 'myticket-events'  ) }
                            initialOpen={ false }
                            colorSettings={ [
                                    {
                                        value: attributes.availableColor,
                                        onChange: ( value ) => {
                                            return setAttributes( { availableColor: value } );
                                        },
                                        label: __( 'Selected', 'myticket-events'  ),
                                    },
                                ] }
                        />

                        <PanelColorSettings
                            title={ __( 'Sold Out', 'myticket-events'  ) }
                            initialOpen={ false }
                            colorSettings={ [
                                    {
                                        value: attributes.soldoutColor,
                                        onChange: ( value ) => {
                                            return setAttributes( { soldoutColor: value } );
                                        },
                                        label: __( 'Selected', 'myticket-events'  ),
                                    },
                                ] }
                        />

                        <PanelColorSettings
                            title={ __( 'Selected', 'myticket-events'  ) }
                            initialOpen={ false }
                            colorSettings={ [
                                    {
                                        value: attributes.selectedColor,
                                        onChange: ( value ) => {
                                            return setAttributes( { selectedColor: value } );
                                        },
                                        label: __( 'Selected', 'myticket-events'  ),
                                    },
                                ] }
                        />

                    </PanelBody>

                    <PanelBody
                        title={ __( 'Texts', 'myticket-events'  ) }
                        initialOpen={ false }
                    >

                        <TextControl
                            label={ __( 'Title', 'myticket-events' ) }
                            value={ attributes.title }
                            onChange={ (value) => setAttributes( { title: value } ) }
                            help={ __( 'Leave blank to hide.', 'myticket-events' ) }
                        />

                        <TextControl
                            label={ __( 'Subtitle', 'myticket-events' ) }
                            value={ attributes.subtitle }
                            onChange={ (value) => setAttributes( { subtitle: value } ) }
                            help={ __( 'Leave blank to hide.', 'myticket-events' ) }
                        />

                        <TextControl
                            label={ __( 'Description', 'myticket-events' ) }
                            value={ attributes.desc }
                            onChange={ (value) => setAttributes( { desc: value } ) }
                            help={ __( 'Leave blank to hide.', 'myticket-events' ) }
                        />

                        <TextareaControl
                            label={ __( 'Bottom Note', 'myticket-events' ) }
                            value={ attributes.note }
                            onChange={ (value) => setAttributes( { note: value } ) }
                            help={ __( 'Leave blank to hide.', 'myticket-events' ) }
                        />

                        <TextControl
                            label={ __( 'Button Text', 'myticket-events' ) }
                            value={ attributes.cta }
                            onChange={ (value) => setAttributes( { cta: value } ) }
                        />
                    </PanelBody>

                    <PanelBody
                        title={ __( 'Layout', 'myticket-events' ) }
                        initialOpen={ false }
                    >
                        <TextareaControl
                            label={ __( 'Seat Code', 'myticket-events' ) }
                            value={ attributes.filterLocations }
                            onChange={ ( filterLocations ) => setAttributes( { filterLocations } ) }
                            help={ __( 'Create new layout under this page: https://kenzap.com/myticket/. Click export and paste generated code here.', 'myticket-events' ) }
                        />

                        <CheckboxControl
                            label={ __( 'Arrows', 'myticket-events' ) }
                            checked={ attributes.showArrows }
                            onChange={ ( showArrows ) => {
                                setAttributes( { showArrows } );
                            } }
                            help={ __( 'Show right/left arrows during seat selection.', 'myticket-events' ) }
                        />

                        <TextControl
                            label={ __( 'Max Layout Width', 'myticket-events' ) }
                            type="number"
                            value={ attributes.dwidth }
                            onChange={ (value) => setAttributes( { dwidth: value } ) }
                            help={ __( 'Maximum width of the layout in desktop mode. In case layout is too wide a scroll can be used to fit the layout.', 'myticket-events' ) }
                        />

                        <TextControl
                            label={ __( 'Min Layout Width', 'myticket-events' ) }
                            type="number"
                            value={ attributes.mwidth }
                            onChange={ (value) => setAttributes( { mwidth: value } ) }
                            help={ __( 'Minimal width of the layout in mobile mode. In case layout is too wide a scroll can be used to fit the layout.', 'myticket-events' ) }
                        />
                        
                        <TextControl
                            label={ __( 'Max Selection Width', 'myticket-events' ) }
                            type="number"
                            value={ attributes.smaxwidth }
                            onChange={ (value) => setAttributes( { smaxwidth: value } ) }
                            help={ __( 'Maximum width of layout during ticket selection in desktop mode. Reduce this parameter in order to fit layout with many seats.', 'myticket-events' ) }
                        />

                        <TextControl
                            label={ __( 'Min Selection Width', 'myticket-events' ) }
                            type="number"
                            value={ attributes.sminwidth }
                            onChange={ (value) => setAttributes( { sminwidth: value } ) }
                            help={ __( 'minimal width of layout during ticket selection in desktop mode. Increase this parameter in order to fit layout with many seats.', 'myticket-events' ) }
                        />
                        
                    </PanelBody>
                    
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
                    block="myticket-events/listing-05"
                    attributes={ {
                        align: attributes.align,
                        serverSide: true,
                    } }
                />
            </div>
        );
    }
}
