const { __ } = wp.i18n; // Import __() from wp.i18n
const { Component } = wp.element;
const { serverSideRender: ServerSideRender } = wp;
const { InspectorControls, PanelColorSettings } = wp.editor;
const { RangeControl, CheckboxControl, PanelBody, SelectControl, TextControl } = wp.components;
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
                        title={ __( 'General', 'myticket-events' ) }
                        initialOpen={ false }
                    >

                        <RangeControl
                            label={ __( 'Records per page', 'myticket-events' ) }
                            value={ attributes.per_page }
                            onChange={ ( value ) => setAttributes( { per_page: value } ) }
                            min={ 1 }
                            max={ 100 }
                            help={ __( 'Specify the maximum number of events listed per single page.', 'myticket-events' ) }
                        />
                        
                        <TextControl
                            label={ __( 'Category', 'myticket-events' ) }
                            value={ attributes.category }
                            onChange={ ( category ) => setAttributes( { category } ) }
                            help={ __( 'Restrict all records to certain category. To view categories go to Products > Categories section.', 'myticket-events' ) }
                        />

                        <SelectControl
                            label={ __( 'List events', 'myticket-events' ) }
                            value={ attributes.type }
                            options={[
                                { label:  __( 'All', 'myticket-events' ) , value: '' },
                                { label:  __( 'Upcomming', 'myticket-events' ) , value: 'upcomming' },
                                { label:  __( 'Past', 'myticket-events' ) , value: 'past' },
                            ]}
                            help={ __( 'Choose how current time affects listing.', 'myticket-events' ) }
                            onChange={ (type) => setAttributes( { type } ) }
                        />

                        <SelectControl
                            label={ __( 'Image aspect ratio', 'myticket-events' ) }
                            value={ attributes.aspect }
                            options={[
                                { label:  __( 'Horizontal', 'myticket-events' ) , value: 'horizontal' },
                                { label:  __( 'Vertical', 'myticket-events' ) , value: 'vertical' },
                                { label:  __( 'Square', 'myticket-events' ) , value: 'square' },
                            ]}
                            onChange={ (aspect) => setAttributes( { aspect } ) }
                        />

                        <PanelColorSettings
                            title={ __( 'Main Color', 'myticket-events' ) }
                            initialOpen={ true }
                            colorSettings={ [
                                    {
                                        value: attributes.mainColor,
                                        onChange: ( value ) => {
                                            return setAttributes( { mainColor: value } );
                                        },
                                        label: __( 'Selected', 'myticket-events' ),
                                    },
                                ] }
                        />

                        <SelectControl
                            label={ __( 'Query relation', 'myticket-events' ) }
                            value={ attributes.relation }
                            options={[
                                { label:  __( 'AND', 'myticket-events' ) , value: '' },
                                { label:  __( 'OR', 'myticket-events' ) , value: 'popularity' },
                            ]}
                            help={ __( 'This rule tells database how to filter results. If user selects two categories Golf and Swimming AND will show only those events that are in Golf and Swimming simultaneously. If user selects Golf and Swimming categories OR will picks up all events within Golf category and unites them with all events from Swimming category. The more checkboxes user ticks with OR relation the more results will be shown and vice versa if AND is selected.', 'myticket-events' ) }
                            onChange={ (relation) => setAttributes( { relation } ) }
                        />

                        <SelectControl
                            label={ __( 'Default order', 'myticket-events' ) }
                            value={ attributes.order }
                            options={[
                                { label:  __( 'None', 'myticket-events' ) , value: '' },
                                { label:  __( 'Popularity', 'myticket-events' ) , value: 'popularity' },
                                { label:  __( 'Rating', 'myticket-events' ) , value: 'rating' },
                                { label:  __( 'Newest', 'myticket-events' ) , value: 'newest' },
                                { label:  __( 'Lowest price', 'myticket-events' ) , value: 'lowestprice' },
                                { label: __( 'Highest price', 'myticket-events' ) , value: 'highestprice' },
                            ]}
                            help={ __( 'Choose default sorting method', 'myticket-events' ) }
                            onChange={ (order) => setAttributes( { order } ) }
                        />

                        {/* <CheckboxControl
                            label={ __( 'Pagination', 'myticket-events' ) }
                            checked={ attributes.pagination}
                            onChange={ (pagination) => setAttributes( { pagination } ) }
                        /> */}

                    </PanelBody>
                    <PanelBody
                        title={ __( 'Filter', 'myticket-events' ) }
                        initialOpen={ false }
                    >

                        <CheckboxControl
                            label={ __( 'Month Filter', 'myticket-events' ) }
                            checked={ attributes.checkFilter}
                            onChange={ (checkFilter) => setAttributes( { checkFilter } ) }
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
                    block="myticket-events/listing-02"
                    attributes={ {
                        align: attributes.align,
                        checkSidebar: true,
                        serverSide: true,
                    } }
                />
            </div>
        );
    }
}
