/* global jQuery */
/* global wp */
function media_upload(button_class) {
    'use strict';
    jQuery('body').on('click', button_class, function () {
        let button_id = '#' + jQuery(this).attr('id');
        let display_field = jQuery(this).parent().children('input:text');
        let _custom_media = true;

        wp.media.editor.send.attachment = function (props, attachment) {

            if (_custom_media) {
                if (typeof display_field !== 'undefined') {
                    switch (props.size) {
                        case 'full':
                            display_field.val(attachment.sizes.full.url);
                            display_field.trigger('change');
                            break;
                        case 'medium':
                            display_field.val(attachment.sizes.medium.url);
                            display_field.trigger('change');
                            break;
                        case 'thumbnail':
                            display_field.val(attachment.sizes.thumbnail.url);
                            display_field.trigger('change');
                            break;
                        default:
                            display_field.val(attachment.url);
                            display_field.trigger('change');
                    }
                }
                _custom_media = false;
            } else {
                return wp.media.editor.send.attachment(button_id, [props, attachment]);
            }
        };
        wp.media.editor.open(button_class);
        window.send_to_editor = function (html) {

        };
        return false;
    });
}

/********************************************
 *** Generate unique id ***
 *********************************************/
function customizer_repeater_uniqid(prefix, more_entropy) {
    'use strict';
    if (typeof prefix === 'undefined') {
        prefix = '';
    }

    let retId;
    let php_js;
    let formatSeed = function (seed, reqWidth) {
        seed = parseInt(seed, 10)
            .toString(16); // to hex str
        if (reqWidth < seed.length) { // so long we split
            return seed.slice(seed.length - reqWidth);
        }
        if (reqWidth > seed.length) { // so short we pad
            return new Array(1 + (reqWidth - seed.length))
                .join('0') + seed;
        }
        return seed;
    };

    // BEGIN REDUNDANT
    if (!php_js) {
        php_js = {};
    }
    // END REDUNDANT
    if (!php_js.uniqidSeed) { // init seed with big random int
        php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
    }
    php_js.uniqidSeed++;

    retId = prefix; // start with prefix, add current milliseconds hex string
    retId += formatSeed(parseInt(new Date()
        .getTime() / 1000, 10), 8);
    retId += formatSeed(php_js.uniqidSeed, 5); // add seed hex string
    if (more_entropy) {
        // for more entropy we add a float lower to 10
        retId += (Math.random() * 10)
            .toFixed(8)
            .toString();
    }

    return retId;
}

/********************************************
 *** General Repeater ***
 *********************************************/
function customizer_repeater_refresh_social_icons(th) {

    'use strict';
    let icons_repeater_values = [];
    th.find('.customizer-repeater-myticket-repeater-container').each(function () {
        let icon = jQuery(this).find('.icp').val();
        let link = jQuery(this).find('.customizer-repeater-myticket-repeater-link').val();
        let id = jQuery(this).find('.customizer-repeater-myticket-repeater-id').val();

        if (!id) {
            id = 'customizer-repeater-myticket-repeater-' + customizer_repeater_uniqid();
            jQuery(this).find('.customizer-repeater-myticket-repeater-id').val(id);
        }

        if (icon !== '' && link !== '') {
            icons_repeater_values.push({
                'icon': icon,
                'link': link,
                'id': id
            });
        }
    });

    th.find('.myticket-repeater-socials-repeater-colector').val(JSON.stringify(icons_repeater_values));
    customizer_repeater_refresh_general_control_values();
}


function customizer_repeater_refresh_general_control_values() {
    'use strict';
    jQuery('.customizer-repeater-general-control-repeater').each(function () {
        let values = [];
        let th = jQuery(this);
        th.find('.customizer-repeater-general-control-repeater-container').each(function () {
      
            let id = jQuery(this).find('.myticket-repeater-box-id').val();
            if (!id) {
                id = 'myticket-repeater-' + customizer_repeater_uniqid();
                jQuery(this).find('.myticket-repeater-box-id').val(id);
            }

            let repeater = {};
            repeater['id'] = id;
            repeater['fields'] = {};
            jQuery(this).find('.customizer-repeater-field').each(function () {

                let obj = {};
                obj['type'] = this.dataset.type;
                obj['key'] = this.dataset.key;
                switch(obj['type']){

                    case 'checkbox': obj['value'] = jQuery(this).is(":checked") ? "1":""; break
                    default: obj['value'] = this.value;

                }

                repeater['fields'][this.dataset.key] = obj;
            });
        
            values.push(repeater);
        });
  
        th.find('.customizer-repeater-colector').val(JSON.stringify(values));
        console.log(JSON.stringify(values));
        th.find('.customizer-repeater-colector').trigger('change');
    });
}


jQuery(document).ready(function () {
    'use strict';
    let theme_conrols = jQuery('#customize-theme-controls');
    theme_conrols.on('click', '.customizer-repeater-customize-control-title', function () {
        jQuery(this).next().slideToggle('medium', function () {
            if (jQuery(this).is(':visible')){
                jQuery(this).prev().addClass('repeater-expanded');
                jQuery(this).css('display', 'block');
            } else {
                jQuery(this).prev().removeClass('repeater-expanded');
            }
        });
    });

    theme_conrols.on('change', '.icp',function(){
        customizer_repeater_refresh_general_control_values();
        return false;
    });

    theme_conrols.on('change', '.customizer-repeater-image-choice', function () {
        if (jQuery(this).val() === 'customizer_repeater_image') {
            jQuery(this).parent().parent().find('.myticket-repeater-general-control-icon').hide();
            jQuery(this).parent().parent().find('.customizer-repeater-image-control').show();
            jQuery(this).parent().parent().find('.customizer-repeater-color-control').prev().prev().hide();
            jQuery(this).parent().parent().find('.customizer-repeater-color-control').hide();
        }
        if (jQuery(this).val() === 'customizer_repeater_icon') {
            jQuery(this).parent().parent().find('.myticket-repeater-general-control-icon').show();
            jQuery(this).parent().parent().find('.customizer-repeater-image-control').hide();
            jQuery(this).parent().parent().find('.customizer-repeater-color-control').prev().prev().show();
            jQuery(this).parent().parent().find('.customizer-repeater-color-control').show();
        }
        if (jQuery(this).val() === 'customizer_repeater_none') {
            jQuery(this).parent().parent().find('.myticket-repeater-general-control-icon').hide();
            jQuery(this).parent().parent().find('.customizer-repeater-image-control').hide();
            jQuery(this).parent().parent().find('.customizer-repeater-color-control').prev().prev().hide();
            jQuery(this).parent().parent().find('.customizer-repeater-color-control').hide();
        }

        customizer_repeater_refresh_general_control_values();
        return false;
    });
    media_upload('.customizer-repeater-custom-media-button');
    jQuery('.custom-media-url').on('change', function () {
        customizer_repeater_refresh_general_control_values();
        return false;
    });

    let color_options = {
        change: function(event, ui){
            customizer_repeater_refresh_general_control_values();
        }
    };

    /**
     * This adds a new box to repeater
     *
     */
    theme_conrols.on('click', '.customizer-repeater-new-field', function () {
        let th = jQuery(this).parent();
        let id = 'customizer-repeater-' + customizer_repeater_uniqid();
        if (typeof th !== 'undefined') {

            /* Clone the first box*/
            let field = th.find('.customizer-repeater-demo').clone( true, true );
            // let field = th.find('.customizer-repeater-general-control-repeater-container:first').clone( true, true );
            
            if (typeof field !== 'undefined') {

                // /*Set box id*/
                field.find('.myticket-repeater-box-id').val(id);

                field.find('.customizer-repeater-general-control-repeater .customize-control-notifications-container').remove();

                field.attr('class', "customizer-repeater");

                // /*Append new box*/
                th.find('.customizer-repeater-general-control-repeater').append(field.children());

                /*Refresh values*/
                customizer_repeater_refresh_general_control_values();
            }
        }
        return false;
    });

    theme_conrols.on('click', '.myticket-repeater-general-control-remove-field', function () {
        if (typeof    jQuery(this).parent() !== 'undefined') {
            jQuery(this).parent().hide(500, function(){
                jQuery(this).parent().remove();
                customizer_repeater_refresh_general_control_values();

            });
        }
        return false;
    });

    theme_conrols.on('change', '.customizer-repeater-field', function () {
        customizer_repeater_refresh_general_control_values();
    });

    // jQuery('input.customizer-repeater-color-control').wpColorPicker(color_options);
    // jQuery('input.customizer-repeater-color2-control').wpColorPicker(color_options);

    theme_conrols.on('keyup', '.customizer-repeater-field', function () {
        customizer_repeater_refresh_general_control_values();
    });

    /*Drag and drop to change icons order*/
    jQuery('.customizer-repeater-general-control-droppable').sortable({
        axis: 'y',
        update: function () {
            customizer_repeater_refresh_general_control_values();
        }
    });

    /*----------------- Socials Repeater ---------------------*/
    theme_conrols.on('click', '.myticket-repeater-add-social-item', function (event) {
        event.preventDefault();
        let th = jQuery(this).parent();
        let id = 'customizer-repeater-myticket-repeater-' + customizer_repeater_uniqid();
        if (typeof th !== 'undefined') {
            let field = th.find('.customizer-repeater-myticket-repeater-container:first').clone( true, true );
            if (typeof field !== 'undefined') {
                field.find( '.icp' ).val('');
                field.find( '.input-group-addon' ).find('.fa').attr('class','fa');
                field.find('.myticket-repeater-remove-social-item').show();
                field.find('.customizer-repeater-myticket-repeater-link').val('');
                field.find('.customizer-repeater-myticket-repeater-id').val(id);
                th.find('.customizer-repeater-myticket-repeater-container:first').parent().append(field);
            }
        }
        return false;
    });

    theme_conrols.on('click', '.myticket-repeater-remove-social-item', function (event) {
        event.preventDefault();
        let th = jQuery(this).parent();
        let repeater = jQuery(this).parent().parent();
        th.remove();
        customizer_repeater_refresh_social_icons(repeater);
        return false;
    });

    theme_conrols.on('keyup', '.customizer-repeater-myticket-repeater-link', function (event) {
        event.preventDefault();
        let repeater = jQuery(this).parent().parent();
        customizer_repeater_refresh_social_icons(repeater);
        return false;
    });

    theme_conrols.on('change', '.customizer-repeater-myticket-repeater-container .icp', function (event) {
        event.preventDefault();
        let repeater = jQuery(this).parent().parent().parent();
        customizer_repeater_refresh_social_icons(repeater);
        return false;
    });

});

let entityMap = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    '\'': '&#39;',
    '/': '&#x2F;'
};

function escapeHtml(string) {
    'use strict';
    //noinspection JSUnresolvedFunction
    string = String(string).replace(new RegExp('\r?\n', 'g'), '<br />');
    string = String(string).replace(/\\/g, '&#92;');
    return String(string).replace(/[&<>"'\/]/g, function (s) {
        return entityMap[s];
    });

}