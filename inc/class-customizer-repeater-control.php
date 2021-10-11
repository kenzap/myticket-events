<?php
// source: https://raw.githubusercontent.com/cristian-ungureanu/customizer-repeater/production/css/admin-style.css
/* dependencies 
file assets/customizer-repeater.js
file assets/customizer-repeater.css
MyTicket_Customizer_Repeater declaration under class-customizer.php

*/
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

class MyTicket_Customizer_Repeater extends WP_Customize_Control {

	public $id;
	private $boxtitle = array();
	private $add_field_label = array();
	private $customizer_icon_container = '';
	private $allowed_html = array();
	public $fields = [];
	public $customizer_repeater_image_control = false;
	public $customizer_repeater_icon_control = false;
	public $customizer_repeater_color_control = false;
	public $customizer_repeater_color2_control = false;
	public $customizer_repeater_title_control = false;
	public $customizer_repeater_subtitle_control = false;
	public $customizer_repeater_text_control = false;
	public $customizer_repeater_link_control = false;
	public $customizer_repeater_text2_control = false;
	public $customizer_repeater_link2_control = false;
	public $customizer_repeater_shortcode_control = false;
	public $customizer_repeater_repeater_control = false;

	/*Class constructor*/
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		/*Get options from customizer.php*/
		$this->add_field_label = esc_html__( 'Add new field', 'myticket-events' );
		if ( ! empty( $args['add_field_label'] ) ) {
			$this->add_field_label = $args['add_field_label'];
		}

		$this->boxtitle = esc_html__( 'Customizer Repeater', 'myticket-events' );
		if ( ! empty ( $args['item_name'] ) ) {
			$this->boxtitle = $args['item_name'];
		} elseif ( ! empty( $this->label ) ) {
			$this->boxtitle = $this->label;
		}

		if ( ! empty( $args['fields'] ) ) {
			$this->fields = $args['fields'];
		}

		if ( ! empty( $args['customizer_repeater_shortcode_control'] ) ) {
			$this->customizer_repeater_shortcode_control = $args['customizer_repeater_shortcode_control'];
		}

		if ( ! empty( $args['customizer_repeater_repeater_control'] ) ) {
			$this->customizer_repeater_repeater_control = $args['customizer_repeater_repeater_control'];
		}

		if ( ! empty( $id ) ) {
			$this->id = $id;
		}

		if ( file_exists( get_template_directory() . '/customizer-repeater/inc/icons.php' ) ) {
			$this->customizer_icon_container =  'customizer-repeater/inc/icons';
		}

		$allowed_array1 = wp_kses_allowed_html( 'post' );
		$allowed_array2 = array(
			'input' => array(
				'type'        => array(),
				'class'       => array(),
				'placeholder' => array()
			)
		);

		$this->allowed_html = array_merge( $allowed_array1, $allowed_array2 );
	}

	public function render_content() {

		$default = [];
		$this->$fields = array(
			'title'     => array('title' => 'Field title', 'type'  => 'text', 	  'key'  => 'title', 	'value'  => ''),
			'type'      => array('title' => 'Field type',  'type'  => 'select',   'key'  => 'type', 	'value'  => '', 'choices' => array('text'=> 'Text', 'textarea'=> 'Textarea', 'email'=> 'Email', 'checkbox'=> 'Checkbox', 'note'=> 'Note') ),
			'key'       => array('title' => 'Field key',   'type'  => 'text',     'key'  => 'key', 		'value'  => ''),
			'required'  => array('title' => 'Required',    'type'  => 'checkbox', 'key'  => 'required', 'value'  => ''),
		);
		
		array_push($default, array('id' => '', 'fields'  => $this->$fields) );
		
		/* Get values (json format) */
		$values = $this->value();

		/* Decode values */
		$json = json_decode( $values, true );

		if ( ! is_array( $json ) ) {
			$json = array( $values );
		} ?>

		<div class="customizer-repeater-demo" style="display:none;">
			<?php $this->iterate_array( $default, true ); ?>
		</div>

        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php if ( isset( $this->description ) ){ ?><span id="_customize-description-myticket_fields" class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span><?php } ?>
        <div class="customizer-repeater-general-control-repeater customizer-repeater-general-control-droppable">
			<?php
			if ( !empty( $json ) ) {
				$this->iterate_array( $json, false ); ?>
			<?php } ?>
			<input type="hidden" id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?> class="customizer-repeater-colector" value="<?php echo esc_textarea( $this->value() ); ?>"/>
        </div>
        <button type="button" class="button add_field customizer-repeater-new-field">
			<?php echo esc_html( $this->add_field_label ); ?>
        </button>
		<?php
	}

	private function iterate_array($array = array(), $demo){
		/*Counter that helps checking if the box is first and should have the delete button disabled*/
		$it = 0;
		foreach($array as $icon){ ?>
			<div class="customizer-repeater-general-control-repeater-container customizer-repeater-draggable">
				<div class="customizer-repeater-customize-control-title">
					<?php esc_html_e( 'Input field', 'myticket-events' ); ?>
				</div>
				<div class="customizer-repeater-box-content-hidden">
					<?php
					$values = $choice = $image_url = $icon_value = $title = $subtitle = $text = $text2 = $link2 = $link = $shortcode = $repeater = $color = $color2 = '';
					
					if(!empty($icon->social_repeater)){
						$repeater = $icon->social_repeater;
					}

					if($this->customizer_repeater_image_control == true && $this->customizer_repeater_icon_control == true) {
						$this->icon_type_choice( $choice );
					}

					if($this->customizer_repeater_image_control == true){
						$this->image_control($image_url, $choice);
					}

					if($this->customizer_repeater_icon_control == true){
						$this->icon_picker_control($icon_value, $choice);
					}

					// parse fields 
					$id = "";
					foreach ( $this->$fields as $key => $val ){

						$this->input_control(array(
							'label' => apply_filters('repeater_input_labels_filter', esc_html__( $val['title'],'myticket-events' ), $key."_".$this->id, 'customizer_repeater_'.$key.'_control' ),
							'class' => 'customizer-repeater-field customizer-repeater-'.$key.'-control',
							'type'  => $val['type'],
							'choices'  => isset( $val['choices'] ) ? $val['choices'] : [],
							'data-key' 	=> $key,
							'data-id' 	=> $id.'_'.$key
						), $icon['fields'][$key]['value'] );
					}

					if($this->customizer_repeater_repeater_control==true){
						$this->repeater_control($repeater);
					} ?>

					<input type="hidden" class="myticket-repeater-box-id" value="<?php if ( ! empty( $id ) ) {
						echo esc_attr( $id );
					} ?>">
					<button type="button" class="myticket-repeater-general-control-remove-field" <?php if ( $it == 0 ) {
						// echo 'style="display:none;"';
					} ?>>
						<?php esc_html_e( 'Delete field', 'myticket-events' ); ?>
					</button>

				</div>
			</div>

			<?php
			$it++;
		}
	}

	private function input_control( $options, $value='' ){ ?>

		<?php
		if( !empty($options['type']) ){
			switch ($options['type']) {
				case 'text':	?>
					<span class="customize-control-title"><?php echo esc_html( $options['label'] ); ?></span>
            		<input type="text" data-type="<?php echo esc_attr( $options['type'] ); ?>" data-key="<?php echo esc_attr( $options['data-key'] ); ?>" value="<?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?>" class="<?php echo esc_attr($options['class']); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>"/>
					<?php
					break;
				case 'checkbox':?>
					<span class="customize-control-title"><?php echo esc_html( $options['label'] ); ?></span>
            		<div><input type="checkbox" <?php if ($value == '1') { echo 'checked'; } ?>  data-type="<?php echo esc_attr( $options['type'] ); ?>" data-key="<?php echo esc_attr( $options['data-key'] ); ?>" value="<?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?>" class="<?php echo esc_attr($options['class']); ?>" /></div>
					<?php
					break;
				case 'select':	?>
					<span class="customize-control-title"><?php echo esc_html( $options['label'] ); ?></span>
					<select data-type="<?php echo esc_attr( $options['type'] ); ?>" data-key="<?php echo esc_attr( $options['data-key'] ); ?>" value="<?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?>" class="<?php echo esc_attr($options['class']); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>">
						<?php foreach( $options['choices'] as $key => $val ){ ?>
						<option <?php if ($value == $key) { echo 'selected'; } ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $val ); ?></option>	
						<?php } ?>
					</select>
					<?php
					break;
				case 'textarea':?>
                    <span class="customize-control-title"><?php echo esc_html( $options['label'] ); ?></span>
                    <textarea class="<?php echo esc_attr( $options['class'] ); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>" ><?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_html($value) ); ?></textarea>
					<?php
					break;
				case 'color':
					$style_to_add = '';
					if( $options['choice'] !== 'customizer_repeater_icon' ){
						$style_to_add = 'display:none';
					}?>
                    <span class="customize-control-title" <?php if( !empty( $style_to_add ) ) { echo 'style="'.esc_attr( $style_to_add ).'"';} ?>><?php echo esc_html( $options['label'] ); ?></span>
                    <div class="<?php echo esc_attr($options['class']); ?>" <?php if( !empty( $style_to_add ) ) { echo 'style="'.esc_attr( $style_to_add ).'"';} ?>>
                        <input type="text" value="<?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?>" class="<?php echo esc_attr($options['class']); ?>" />
                    </div>
					<?php
					break;
			}
		} else { ?>
            <span class="customize-control-title"><?php echo esc_html( $options['label'] ); ?></span>
            <input type="text" data-type="<?php esc_attr( $options['data-type'] ); ?>" data-key="<?php esc_attr( $options['data-key'] ); ?>" value="<?php echo ( !empty($options['sanitize_callback']) ?  call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr($value) ); ?>" class="<?php echo esc_attr($options['class']); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>"/>
			<?php
		}
	}

	private function icon_picker_control($value = '', $show = ''){ ?>

        <div class="myticket-repeater-general-control-icon" <?php if( $show === 'customizer_repeater_image' || $show === 'customizer_repeater_none' ) { echo 'style="display:none;"'; } ?>>
            <span class="customize-control-title">
                <?php esc_html_e('Icon','myticket-events'); ?>
            </span>
            <span class="description customize-control-description">
                <?php
                echo sprintf(
	                esc_html__( 'Note: Some icons may not be displayed here. You can see the full list of icons at %1$s.', 'myticket-events' ),
	                sprintf( '<a href="http://fontawesome.io/icons/" rel="nofollow">%s</a>', esc_html__( 'http://fontawesome.io/icons/', 'myticket-events' ) )
                ); ?>
            </span>
            <div class="input-group icp-container">
                <input data-placement="bottomRight" class="icp icp-auto" value="<?php if(!empty($value)) { echo esc_attr( $value );} ?>" type="text">
                <span class="input-group-addon">
                    <i class="fa <?php echo esc_attr($value); ?>"></i>
                </span>
            </div>
			<?php get_template_part( $this->customizer_icon_container ); ?>
        </div>
		<?php
	}

	private function image_control($value = '', $show = ''){ ?>

        <div class="customizer-repeater-image-control" <?php if( $show === 'customizer_repeater_icon' || $show === 'customizer_repeater_none' || empty( $show ) ) { echo 'style="display:none;"'; } ?>>
            <span class="customize-control-title">
                <?php esc_html_e('Image','myticket-events')?>
            </span>
            <input type="text" class="widefat custom-media-url" value="<?php echo esc_attr( $value ); ?>">
            <input type="button" class="button button-secondary customizer-repeater-custom-media-button" value="<?php esc_attr_e( 'Upload Image','myticket-events' ); ?>" />
        </div>
		<?php
	}

	private function icon_type_choice($value='customizer_repeater_icon'){ ?>

        <span class="customize-control-title">
            <?php esc_html_e('Image type','myticket-events');?>
        </span>
        <select class="customizer-repeater-image-choice">
            <option value="customizer_repeater_icon" <?php selected($value,'customizer_repeater_icon');?>><?php esc_html_e('Icon','myticket-events'); ?></option>
            <option value="customizer_repeater_image" <?php selected($value,'customizer_repeater_image');?>><?php esc_html_e('Image','myticket-events'); ?></option>
            <option value="customizer_repeater_none" <?php selected($value,'customizer_repeater_none');?>><?php esc_html_e('None','myticket-events'); ?></option>
        </select>
		<?php
	}

	private function repeater_control($value = ''){

		$social_repeater = array();
		$show_del        = 0; ?>
        <span class="customize-control-title"><?php esc_html_e( 'Social icons', 'myticket-events' ); ?></span>
		<?php
		echo '<span class="description customize-control-description">';
		echo sprintf(
			esc_html__( 'Note: Some icons may not be displayed here. You can see the full list of icons at %1$s.', 'myticket-events' ),
			sprintf( '<a href="http://fontawesome.io/icons/" rel="nofollow">%s</a>', esc_html__( 'http://fontawesome.io/icons/', 'myticket-events' ) )
		);
		echo '</span>';
		if(!empty($value)) {
			$social_repeater = json_decode( html_entity_decode( $value ), true );
		}
		if ( ( count( $social_repeater ) == 1 && '' === $social_repeater[0] ) || empty( $social_repeater ) ) { ?>
            <div class="customizer-repeater-myticket-repeater">
                <div class="customizer-repeater-myticket-repeater-container">
                    <div class="customizer-repeater-rc input-group icp-container">
                        <input data-placement="bottomRight" class="icp icp-auto" value="<?php if(!empty($value)) { echo esc_attr( $value ); } ?>" type="text">
                        <span class="input-group-addon"></span>
                    </div>
					<?php get_template_part( $this->customizer_icon_container ); ?>
                    <input type="text" class="customizer-repeater-myticket-repeater-link"
                           placeholder="<?php esc_attr_e( 'Link', 'myticket-events' ); ?>">
                    <input type="hidden" class="customizer-repeater-myticket-repeater-id" value="">
                    <button class="myticket-repeater-remove-social-item" style="display:none">
						<?php esc_html_e( 'Remove Icon', 'myticket-events' ); ?>
                    </button>
                </div>
                <input type="hidden" id="myticket-repeater-socials-repeater-colector" class="myticket-repeater-socials-repeater-colector" value=""/>
            </div>
            <button class="myticket-repeater-add-social-item button-secondary"><?php esc_html_e( 'Add Icon', 'myticket-events' ); ?></button>
			<?php
		} else { ?>
            <div class="customizer-repeater-myticket-repeater">
				<?php
				foreach ( $social_repeater as $social_icon ) {
					$show_del ++; ?>
                    <div class="customizer-repeater-myticket-repeater-container">
                        <div class="customizer-repeater-rc input-group icp-container">
                            <input data-placement="bottomRight" class="icp icp-auto" value="<?php if( !empty($social_icon['icon']) ) { echo esc_attr( $social_icon['icon'] ); } ?>" type="text">
                            <span class="input-group-addon"><i class="fa <?php echo esc_attr( $social_icon['icon'] ); ?>"></i></span>
                        </div>
						<?php get_template_part( $this->customizer_icon_container ); ?>
                        <input type="text" class="customizer-repeater-myticket-repeater-link"
                               placeholder="<?php esc_attr_e( 'Link', 'myticket-events' ); ?>"
                               value="<?php if ( ! empty( $social_icon['link'] ) ) {
							       echo esc_url( $social_icon['link'] );
						       } ?>">
                        <input type="hidden" class="customizer-repeater-myticket-repeater-id"
                               value="<?php if ( ! empty( $social_icon['id'] ) ) {
							       echo esc_attr( $social_icon['id'] );
						       } ?>">
                        <button class="myticket-repeater-remove-social-item"
                                style="<?php if ( $show_del == 1 ) {
							        echo "display:none";
						        } ?>"><?php esc_html_e( 'Remove Icon', 'myticket-events' ); ?></button>
                    </div>
					<?php
				} ?>
                <input type="hidden" id="myticket-repeater-socials-repeater-colector"
                       class="myticket-repeater-socials-repeater-colector"
                       value="<?php echo esc_textarea( html_entity_decode( $value ) ); ?>" />
            </div>
            <button class="myticket-repeater-add-social-item button-secondary"><?php esc_html_e( 'Add Icon', 'myticket-events' ); ?></button>
			<?php
		}
	}
}