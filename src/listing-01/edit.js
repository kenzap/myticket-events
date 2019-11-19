const { __ } = wp.i18n;
const { Component } = wp.element;
const { InspectorControls, PanelColorSettings } = wp.editor;
const { RangeControl, CheckboxControl, PanelBody, ServerSideRender, SelectControl, TextControl, TextareaControl } = wp.components;
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
                            max={ 50 }
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
                            label={ __( 'Query relation', 'myticket-events' ) }
                            value={ attributes.relation }
                            options={[
                                { label:  __( 'AND', 'myticket-events' ) , value: 'AND' },
                                { label:  __( 'OR', 'myticket-events' ) , value: 'OR' },
                            ]}
                            help={ __('This rule tells database how to filter results. If user selects two categories Golf and Swimming AND will show only those events that are in Golf and Swimming simultaneously. If user selects Golf and Swimming categories OR will picks up all events within Golf category and unites them with all events from Swimming category. The more checkboxes user ticks with OR relation the more results will be shown and vice versa if AND is selected.', 'myticket-events') }
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

                        <RangeControl
                            label={ __( 'Border radius', 'myticket-events' ) }
                            value={ attributes.borderRadius }
                            onChange={ ( borderRadius ) => setAttributes( { borderRadius } ) }
                            min={ 0 }
                            max={ 50 }
                        />

                        <CheckboxControl
                            label={ __( 'Pagination', 'myticket-events' ) }
                            checked={ attributes.pagination}
                            onChange={ (pagination) => setAttributes( { pagination } ) }
                        />

                    </PanelBody>

                    <PanelColorSettings
                        title={ __( 'Colors', 'myticket-events' ) }
                        initialOpen={ false }
                        colorSettings={ [
                                {
                                    value: attributes.mainColor,
                                    onChange: ( value ) => {
                                        return setAttributes( { mainColor: value } );
                                    },
                                    label: __( 'Main', 'myticket-events' ),
                                },{
                                    value: attributes.textColor,
                                    onChange: ( value ) => {
                                        return setAttributes( { textColor: value } );
                                    },
                                    label: __( 'Text', 'myticket-events' ),
                                },{
                                    value: attributes.textColor2,
                                    onChange: ( value ) => {
                                        return setAttributes( { textColor2: value } );
                                    },
                                    label: __( 'Containers', 'myticket-events' ),
                                },
                            ] }
                    />

                    <PanelBody
                        title={ __( 'Filter', 'myticket-events' ) }
                        initialOpen={ false }
                    >

                        <CheckboxControl
                            label={ __( 'Primary filter', 'myticket-events' ) }
                            checked={ attributes.checkFilter}
                            onChange={ (checkFilter) => setAttributes( { checkFilter } ) }
                            help={ __( 'Hint. Where input fields are located', 'myticket-events' ) } 
                        />

                        { attributes.checkFilter && ( <TextareaControl
                            label={ __( 'Filter locations', 'myticket-events' ) }
                            value={ attributes.filterLocations }
                            onChange={ ( filterLocations ) => setAttributes( { filterLocations } ) }
                            help={ __( 'Override default location list. Separate locations by ",". Ex.: Arena Berlin, Belgrade Stadium.. If empty all locations are queried. To specify event location go to Products > Edit product > Event Title', 'myticket-events' ) }
                        /> ) }

                        <CheckboxControl
                            label={ __( 'Secondary filter', 'myticket-events' ) }
                            checked={ attributes.checkFilter2}
                            onChange={ (checkFilter2) => setAttributes( { checkFilter2 } ) }
                            help={ __( 'Hint. Showing all records filter', 'myticket-events' ) } 
                        />

                    </PanelBody>
                    <PanelBody
                        title={ __( 'Sidebar', 'myticket-events' ) }
                        initialOpen={ false }
                    >
                        <CheckboxControl
                            label={ __( 'Show Sidebar', 'myticket-events' ) }
                            checked={ attributes.checkSidebar }
                            onChange={ (checkSidebar) => setAttributes( { checkSidebar } ) }
                        />

                        { attributes.checkSidebar && ( <SelectControl
                            label={ __( 'Sidebar Location', 'myticket-events' ) }
                            value={ attributes.sidebar }
                            options={[
                                { label: __( 'Left', 'myticket-events' ), value: 'left' },
                                { label: __( 'Right', 'myticket-events' ), value: 'right' },
                            ]}
                            help={ __( 'Choose sidebar location', 'myticket-events' ) }
                            onChange={ (sidebar) => setAttributes( { sidebar } ) }
                        /> ) }

                        { attributes.checkSidebar && ( <TextControl
                            label={ __( 'Title', 'myticket-events' ) }
                            value={ attributes.sidebarTitle }
                            onChange={ ( sidebarTitle ) => setAttributes( { sidebarTitle } ) }
                        /> ) }

                        { attributes.checkSidebar && ( <TextControl
                            label={ __( 'Subtitle', 'myticket-events' ) }
                            value={ attributes.sidebarSubTitle }
                            onChange={ ( sidebarSubTitle ) => setAttributes( { sidebarSubTitle } ) }
                        /> ) }

                        { attributes.checkSidebar && ( <TextControl
                            label={ __( '1 Category Title', 'myticket-events' ) }
                            value={ attributes.sidebarCat1Title }
                            onChange={ ( sidebarCat1Title ) => setAttributes( { sidebarCat1Title } ) }
                        /> ) }

                        { attributes.checkSidebar && ( <TextareaControl
                            label={ __( '1 Category List', 'myticket-events' ) }
                            value={ attributes.sidebarCat1List }
                            onChange={ ( sidebarCat1List ) => setAttributes( { sidebarCat1List } ) }
                            help={ __( 'Separate categories by comma. Categories are case-sensitive. Ex.: Sport, Concerts, etc. To find available categories go to Products > Categories.', 'myticket-events' ) }
                        /> ) }

                        { attributes.checkSidebar && ( <TextControl
                            label={ __( '2 Category Title', 'myticket-events' ) }
                            value={ attributes.sidebarCat2Title }
                            onChange={ ( sidebarCat2Title ) => setAttributes( { sidebarCat2Title } ) }
                        /> ) }

                        { attributes.checkSidebar && ( <TextareaControl
                            label={ __( '2 Category List', 'myticket-events' ) }
                            value={ attributes.sidebarCat2List }
                            onChange={ ( sidebarCat2List ) => setAttributes( { sidebarCat2List } ) }
                            help={ __( 'Separate categories by comma. Categories are case-sensitive. Ex.: Sport, Concerts, etc. To find available categories go to Products > Categories.', 'myticket-events' ) }
                        /> ) }

                        { attributes.checkSidebar && (<CheckboxControl
                            label={ __( 'Pricing Filter', 'myticket-events' ) }
                            checked={ attributes.pricingFilter }
                            onChange={ (pricingFilter) => setAttributes( { pricingFilter } ) }
                        /> ) }

                        { attributes.checkSidebar && attributes.pricingFilter && ( <TextControl
                            label={ __( 'Pricing Filter Title', 'myticket-events' ) }
                            value={ attributes.pricingFilterTitle }
                            onChange={ ( pricingFilterTitle ) => setAttributes( { pricingFilterTitle } ) }
                        /> ) }

                        { attributes.checkSidebar && attributes.pricingFilter && ( <TextControl
                            label={ __( 'Max Price', 'myticket-events' ) }
                            value={ attributes.pricingFilterMax }
                            onChange={ ( pricingFilterMax ) => setAttributes( { pricingFilterMax } ) }
                            help={ __( 'Specify the maximum pricing range.', 'myticket-events' ) }
                        /> ) }

                        { attributes.checkSidebar && attributes.pricingFilter && ( <TextControl
                            label={ __( 'Currency symbol', 'myticket-events' ) }
                            value={ attributes.currencysymbol }
                            onChange={ ( currencysymbol ) => setAttributes( { currencysymbol } ) }
                            help={ __( 'Add currency symbol to slider handles.', 'myticket-events' ) }
                        /> ) }
{/* 
                        { attributes.checkSidebar && (<SelectControl
                            label={ __( '1 Widget Type', 'myticket-events' ) }
                            value={ attributes.widget1 }
                            options={[
                                { label: __( 'None', 'myticket-events' ), value: '' },
                                { label: __( 'Category', 'myticket-events' ), value: 'category' },
                                { label: __( 'Pricing', 'myticket-events' ), value: 'pricing' },
                                { label: __( 'Text', 'myticket-events' ), value: 'text' },
                            ]}
                            help={ __( 'Choose sidebar widget type', 'myticket-events' ) }
                            onChange={ (widget1) => setAttributes( { widget1 } ) }
                        /> ) } */}

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
                    block="myticket-events/listing-01"
                    attributes={ {
                        // container
                        containerMaxWidth: attributes.containerMaxWidth,
                        containerPadding: attributes.containerPadding,
                        containerSidePadding: attributes.containerSidePadding,
                        backgroundColor: attributes.backgroundColor,
                        backgroundImage: attributes.backgroundImage,
                        backgroundStyle: attributes.backgroundStyle,
                        backgroundPosition: attributes.backgroundPosition,
                        parallax: attributes.parallax,
                        //block
                        align: attributes.align,
                        checkSidebar: false,
                        serverSide: true,
                        borderRadius: attributes.borderRadius,
                        currencysymbol: attributes.currencysymbol,
                    } }
                />
            </div>
        );
    }
}
