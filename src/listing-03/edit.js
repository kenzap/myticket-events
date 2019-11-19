const { __ } = wp.i18n; 
const { Component } = wp.element;
const { InspectorControls, PanelColorSettings } = wp.editor;
const { RangeControl, PanelBody, ServerSideRender, SelectControl, TextControl } = wp.components;
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
                            label={ __( 'Event ID', 'myticket-events'  ) }
                            value={ attributes.eventID }
                            onChange={ ( eventID ) => setAttributes( { eventID } ) }
                            help={ __('Go to Products section from your admin menu. From the products list view hover on the desired record. ID number will show up. Copy it here.', 'myticket-events' ) }
                        />

                        <SelectControl
                            label={ __( 'Redirect', 'myticket-events'  ) }
                            value={ attributes.redirect }
                            options={[
                                { label:  __( 'No', 'myticket-events' ) , value: '' },
                                { label:  __( 'Cart page', 'myticket-events' ) , value: 'cart' },
                                { label:  __( 'Checkout page', 'myticket-events' ) , value: 'checkout' },
                            ]}
                            help={ __( 'Define action after one of the buttons is clicked. Make sure that Cart and Checkout pages of WooCommerce are set up. Please also verify that this setting do not conflict with WooCommerce > Settings > Products > Redirect to the cart...', 'myticket-events' ) }
                            onChange={ (redirect) => setAttributes( { redirect } ) }
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

                    </PanelBody>

                    
                    <InspectorContainer
                        setAttributes={ setAttributes }
                        { ...attributes }
                        withPadding
                        withWidth100
                        withBackground
                    />
                </InspectorControls>

                <ServerSideRender
                    block="myticket-events/listing-03"
                    attributes={ {
                        align: attributes.align,
                        eventID: 0,
                        serverSide: true,
                    } }
                />
            </div>
        );
    }
}
