const { __ } = wp.i18n; 
const { RangeControl, CheckboxControl, SelectControl, PanelBody, Button } = wp.components;
const { Component, Fragment } = wp.element;
const { MediaUpload, PanelColorSettings } = wp.editor;

export const blockProps = {
    containerMaxWidth: {
        type: 'string',
        default: '2000',
    },

    containerPadding: {
        type: 'number',
        default: 0,
    },

    containerSidePadding: {
        type: 'number',
        default: 0,
    },

    withPadding: {
        type: 'boolean',
        default: false,
    },

    autoPadding: {
        type: 'string',
        default: '',
    },

    withAutoPadding: {
        type: 'boolean',
        default: false,
    },

    width100: {
        type: 'boolean',
        default: false,
    },

    parallax: {
        type: 'boolean',
        default: false,
    },

    optimize: {
        type: 'boolean',
        default: true,
    },

    backgroundColor: {
        type: 'string',
    },

    backgroundImage: {
        type: 'string',
        default: 'none',
    },

    backgroundImageF: {
        type: 'string',
        default: 'none',
    },

    backgroundImageId: {
        type: 'string',
        default: '',
    },

    backgroundStyle: {
        type: 'string',
        default: '',
    },

    backgroundPosition: {
        type: 'string',
        default: 'center center',
    },

    alignment: {
        type: 'string',
        default: '',
    },

    nestedBlocks: {
        type: 'string',
        default: '',
    },

    uniqueID: {
        type: 'string',
    },
};

/**
 * Removes domain name from the url
 * @param {string} value - url
 */
//export const uo = ( url ) => { if(typeof(url)==='undefined') return ''; return url.replace(/^.*\/\/[^\/]+/, ''); };
export const uo = ( url ) => { return url; };

/**
 * Implements inspector container
 */
export class InspectorContainer extends Component {

    render() {

        const {
            withBackground = true,
            backgroundImageId,
            backgroundImage,
            backgroundImageF,
            containerMaxWidth,
            backgroundColor,
            backgroundRepeat,
            backgroundPosition,
            alignment,
            setAttributes,
            width100,
            parallax,
            optimize,
            withWidth100 = false,
            withPadding = false,
            withNested = false,
            containerPadding,
            containerSidePadding,
            autoPadding = '',
            nestedBlocks = '',
        } = this.props;

        return (
            <Fragment>
                { withBackground &&
                <PanelBody
                    title={ __( 'Background' ) }
                    initialOpen={ false }
                >
                    <PanelColorSettings
                        title={ __( 'Color' ) }
                        initialOpen={ true }
                        colorSettings={ [
                                {
                                    value: backgroundColor,
                                    onChange: ( value ) => {
                                        return setAttributes( { backgroundColor: value } );
                                    },
                                    label: __( 'Selected' ),
                                },
                            ] }
                    />

                    <p style={ { marginBottom: '5px' } }>{ __( 'Image' ) }</p>
                    <MediaUpload
                        onSelect={ ( media ) => {
                                let url = media.sizes['kp_banner']?media.sizes['kp_banner']['url']:media.url;
                                this.props.setAttributes( {
                                    backgroundImage: url,
                                    backgroundImageF: media.url,
                                    backgroundImageId: media.id,
                                } );
                            } }
                        value={ backgroundImageId }
                        //allowedTypes={ [ 'image' ] }
                        render={ ( mediaUploadProps ) => (
                            <Fragment>
                                { ( backgroundImageId || backgroundImage !== 'none' ) ? (
                                    <Fragment>
                                        <Button
                                            isDefault
                                            onClick={ () => {
                                                setAttributes( {
                                                    backgroundImageId: '',
                                                    backgroundImage: 'none',
                                                } );
                                            } }
                                        >
                                            { __( 'Remove' ) }
                                        </Button>
                                        <div
                                            style={ {
                                                width: '27px',
                                                height: '27px',
                                                display: 'inline-block',
                                                margin: '0 0 0 5px',
                                                backgroundImage: `url(${ [ this.props.backgroundImage ? (this.props.backgroundImage) : '' ] })`,
                                                backgroundRepeat: 'no-repeat',
                                                backgroundSize: 'cover',
                                            } }
                                        />
                                    </Fragment>
                                ) : (
                                    <Button isDefault onClick={ mediaUploadProps.open }>
                                        { __( 'Upload/Choose' ) }
                                    </Button>
                                ) }
                            </Fragment>

                            ) }
                        />

                    <p style={ { fontStyle: 'italic' } }>
                        { __( 'Override background color with image.' ) }
                    </p>

                    { backgroundImage !== 'none' && <Fragment>
                    <SelectControl
                        label={ __( 'Style' ) }
                        value={ backgroundRepeat }
                        options={ [
                                { label: __( 'default' ), value: 'default' },
                                { label: __( 'contain' ), value: 'contain' },
                                { label: __( 'cover' ), value: 'cover' },
                                { label: __( 'repeated' ), value: 'repeat' },
                            ] }
                        onChange={ ( value ) => {
                                setAttributes( { backgroundStyle: value } );
                            } }
                        help={ __( 'Background image alignment.' ) }
                        />

                    <SelectControl
                        label={ __( 'Position' ) }
                        value={ backgroundPosition }
                        options={ [
                                { label: __( 'left top' ), value: 'left top' },
                                { label: __( 'left center' ), value: 'left center' },
                                { label: __( 'left bottom' ), value: 'left bottom' },
                                { label: __( 'right top' ), value: 'right top' },
                                { label: __( 'right center' ), value: 'right center' },
                                { label: __( 'right bottom' ), value: 'right bottom' },
                                { label: __( 'center top' ), value: 'center top' },
                                { label: __( 'center center' ), value: 'center center' },
                                { label: __( 'center bottom' ), value: 'center bottom' },
                            ] }
                        onChange={ ( value ) => {
                                setAttributes( { backgroundPosition: value } );
                            } }
                        help={ __( 'Starting position of the background image.' ) }
                        />

                    <CheckboxControl
                        label={ __( 'Parallax' ) }
                        checked={ parallax }
                        onChange={ ( parallax ) => {
                            setAttributes( {
                                parallax: parallax,
                            } );
                        } }
                        help={ __( 'Background image behaviour during scroll.' ) }
                    />
                    
                    <CheckboxControl
                        label={ __( 'Optimize' ) }
                        checked={ optimize }
                        onChange={ ( optimize ) => {
                            setAttributes( {
                                optimize: optimize,
                            } );
                        } }
                        help={ __( 'Optimize background image size for faster page loading.' ) }
                    />
                    </Fragment>}
                </PanelBody>
                }

                <PanelBody
                    title={ __( 'Container' ) }
                    initialOpen={ false }
                >

                    { ! width100 &&
                    <RangeControl
                        label={ __( 'Max width' ) }
                        value={ Number( containerMaxWidth ) }
                        onChange={ ( value ) => setAttributes( { containerMaxWidth: `${ value }` } ) }
                        min={ 300 }
                        max={ 2000 }
                        help={ __( 'Restrict layout width for content children.' ) }
                    />
                    }

                    { withWidth100 &&
                    <CheckboxControl
                        label={ __( 'Full width' ) }
                        checked={ width100 }
                        onChange={ ( isChecked ) => {
                            setAttributes( {
                                width100: isChecked,
                                containerMaxWidth: isChecked ? '100%' : '2000',
                            } );
                        } }
                        help={ __( 'Ignore max width restriction.' ) }
                    />
                    }

                    { withPadding &&
                        <Fragment>
                            <RangeControl
                                label={ __( 'Top and bottom paddings' ) }
                                value={ containerPadding }
                                onChange={ ( value ) => setAttributes( { containerPadding: value } ) }
                                min={ 0 }
                                max={ 200 }
                            />

                            <RangeControl
                                label={ __( 'Left and right paddings' ) }
                                value={ containerSidePadding }
                                onChange={ ( value ) => setAttributes( { containerSidePadding: value } ) }
                                min={ 0 }
                                max={ 50 }
                            />

                            <CheckboxControl
                                label={ __( 'Responsive paddings' ) }
                                checked={ autoPadding.length > 0 }
                                onChange={ ( isChecked ) => {
                                    setAttributes( {
                                        autoPadding: isChecked ? 'autoPadding' : '',
                                    } );
                                } }
                                help={ __( 'Auto calculate top and bottom paddings.' ) }
                            />
                        </Fragment>
                    }

                    { withNested &&
                    <SelectControl
                        label={ __( 'Nested block' ) }
                        value={ nestedBlocks }
                        options={ [
                                { label: __( 'hidden' ), value: '' },
                                { label: __( 'top' ), value: 'top' },
                                { label: __( 'bottom' ), value: 'bottom' },
                            ] }
                        onChange={ ( value ) => {
                                setAttributes( { nestedBlocks: value } );
                            } }
                            help={ __( 'Embed other blocks inside this container. Nested blocks inherit parent block styling settings. Add custom headings, spacings or paragraphs.' ) }
                        />
                    }
                </PanelBody>
            </Fragment>
        );
    }
}

/**
 * Implements the edit container
 * @param {Object} props from editor
 * @return {Node} rendered edit component
 * @constructor
 */
export const ContainerEdit = ( props ) => {
    const styles = {};

    if ( props.withBackground ) {
        if ( props.attributes.backgroundImage ) { 
            if(props.attributes.optimize){ styles.backgroundImage = props.attributes.backgroundImage !== 'none' ? `url(${ (props.attributes.backgroundImage) })` : 'none';}else{styles.backgroundImage = props.attributes.backgroundImageF !== 'none' ? `url(${ (props.attributes.backgroundImageF) })` : 'none'; }
            styles.backgroundRepeat = props.attributes.backgroundRepeat;
            styles.backgroundSize = props.attributes.backgroundSize;
            styles.backgroundPosition = props.attributes.backgroundPosition;
        }

        if ( props.attributes.backgroundColor ) {
            styles.backgroundColor = props.attributes.backgroundColor;
        }
    }

    if ( props.withPadding && ! props.attributes.autoPadding ) {
        styles.padding = `${ props.attributes.containerPadding }px 0px`;
    }

    if ( props.attributes.parallax ) {
        styles.backgroundAttachment = 'fixed';
    }

    switch ( props.attributes.backgroundStyle ) {
        case 'default': {
            styles.backgroundRepeat = 'no-repeat';
            styles.backgroundSize = 'auto';
            break;
        }

        case 'contain': {
            styles.backgroundRepeat = 'no-repeat';
            styles.backgroundSize = 'contain';
            break;
        }

        case 'cover': {
            styles.backgroundRepeat = 'no-repeat';
            styles.backgroundSize = 'cover';
            break;
        }

        case 'repeat': {
            styles.backgroundRepeat = 'repeat';
            styles.backgroundSize = 'auto';
        }
    }

    let additionalClassForKenzapContainer = 'kenzap-lg';
    if (props.attributes.containerMaxWidth < 992 ) {
        additionalClassForKenzapContainer = 'kenzap-md';
    }
    if ( props.attributes.containerMaxWidth < 768 ) {
        additionalClassForKenzapContainer = 'kenzap-sm';
    }
    if ( props.attributes.containerMaxWidth < 480 ) {
        additionalClassForKenzapContainer = 'kenzap-xs';
    }
    if ( props.attributes.width100 ) {
        additionalClassForKenzapContainer = 'kenzap-lg';
    }

    return (
        <div
            className={ `${ props.className } ${additionalClassForKenzapContainer} ${ props.attributes.alignment } ${ props.attributes.autoPadding }` }
            style={ { ...styles, ...props.style } }
        >
            { props.children }
        </div>
    );
};

/**
 * Implements the save container
 * @param {Object} props from editor
 * @return {Node} rendered edit component
 * @constructor
 */
export const ContainerSave = ( props ) => {
    const styles = {};

    if ( props.withBackground ) {
        if ( props.attributes.backgroundImage ) {
            if(props.attributes.optimize){ styles.backgroundImage = props.attributes.backgroundImage !== 'none' ? `url(${ (props.attributes.backgroundImage) })` : 'none'; }else{ styles.backgroundImage = props.attributes.backgroundImageF !== 'none' ? `url(${ (props.attributes.backgroundImageF) })` : 'none'; }
            styles.backgroundRepeat = props.attributes.backgroundRepeat;
            styles.backgroundSize = props.attributes.backgroundSize;
            styles.backgroundPosition = props.attributes.backgroundPosition;
        }

        if ( props.attributes.backgroundColor ) {
            styles.backgroundColor = props.attributes.backgroundColor;
        }
    }

    if ( props.withPadding && ! props.attributes.autoPadding ) {
        styles.padding = `${ props.attributes.containerPadding }px 0px`;
    }

    if ( props.attributes.parallax ) {
        styles.backgroundAttachment = 'fixed';
    }

    switch ( props.attributes.backgroundStyle ) {
        case 'default': {
            styles.backgroundRepeat = 'no-repeat';
            styles.backgroundSize = 'auto';
            break;
        }

        case 'contain': {
            styles.backgroundRepeat = 'no-repeat';
            styles.backgroundSize = 'contain';
            break;
        }

        case 'cover': {
            styles.backgroundRepeat = 'no-repeat';
            styles.backgroundSize = 'cover';
            break;
        }

        case 'repeat': {
            styles.backgroundRepeat = 'repeat';
            styles.backgroundSize = 'auto';
        }
    }

    let additionalClassForKenzapContainer = 'kenzap-lg';
    if (props.attributes.containerMaxWidth < 992 ) {
        additionalClassForKenzapContainer = 'kenzap-md';
    }
    if ( props.attributes.containerMaxWidth < 768 ) {
        additionalClassForKenzapContainer = 'kenzap-sm';
    }
    if ( props.attributes.containerMaxWidth < 480 ) {
        additionalClassForKenzapContainer = 'kenzap-xs';
    }
    if ( props.attributes.width100 ) {
        additionalClassForKenzapContainer = 'kenzap-lg';
    }

    return (
        <div
            className={ `${ props.className } ${additionalClassForKenzapContainer} ${ props.attributes.alignment } ${ props.attributes.autoPadding }` }
            style={ { ...styles, ...props.style } }
        >
            { props.children }
        </div>
    );
};