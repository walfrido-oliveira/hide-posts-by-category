<?php 
/**
 * Plugin Name: Hide Posts By Category
 * Plugin URI: https://github.com/walfrido-oliveira/wp-hide-posts-by-category
 * Description: A plugin that hide post by category
 * Version: 1.0.0
 * Author: Walfrido Oliveira
 * Text Domain: hpbc
 * Domain Path: /languages
 * Author URI: https://github.com/walfrido-oliveira
 *
 * @package hpbc
 */

defined( 'ABSPATH' ) || exit;

if ( !function_exists( 'hpbc_options_page_html' ) ) {
	function hpbc_options_page_html() {
		if (!current_user_can( 'manage_options' )) {
			return;
		}

	 	wp_enqueue_script( 'hide-posts-by-category', plugins_url( 'assets/js/hide-posts-by-category.js', __FILE__ ));

	 	settings_errors( 'hpbc_messages' );
	 	
		?>
		<div class="wrap">
			<h1><?php esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post" id="hpbc_form">
				<?php
				settings_fields( 'hpbc' );
				do_settings_sections( 'hpbc' );
				submit_button( __('Save') );
				?>
				<div class="error notice is-dismissable" id="error" style="display: none">
	    			<p id="menssage_error"></p>
				</div>
			</form>
			<script type="text/javascript">
				var error_category = '<?php _e( "Field category is empty.", "hpbc") ?>';
				var error_local = '<?php _e( " Nothing local selected.", "hpbc" ) ?>';
			</script>
		</div>
		<?php
	}
}

if ( !function_exists( 'hpbc_options_page' ) ) {
	function hpbc_options_page() {
		add_submenu_page(
	        'options-general.php',
	        __('Hide Posts','hpbc'),
	        __('Hide Posts','hpbc'),
	        'manage_options',
	        'hpbc',
	        'hpbc_options_page_html'
	    );
	}
}
add_action( 'admin_menu', 'hpbc_options_page');

if ( !function_exists( 'hpbc_settings_init' ) ) {
	function hpbc_settings_init() {

		register_setting( 'hpbc', 'hpbc_options');

		add_settings_section(
		   'hpbc_section_developers',
		    __( 'Settings', 'hpbc' ),
			'hpbc_section_developers_cb',
			'hpbc'
		);

		add_settings_field(
	 		'hpbc_field_categories', 
	 		__( 'Category', 'hpbc' ),
	 		'hpbc_field_categories_cb',
	 		'hpbc',
	 		'hpbc_section_developers',
	 		[
		 		'label_for' => 'hpbc_field_categories',
		 		'class' => 'hpbc_row',
		 		'hpbc_custom_data' => 'custom',
	 		]
	 	);

	 	add_settings_field(
	 		'hpbc_field_local', 
	 		__( 'Local', 'hpbc' ),
	 		'hpbc_field_local_cb',
	 		'hpbc',
	 		'hpbc_section_developers',
	 		[
		 		'label_for' => 'hpbc_field_local',
		 		'class' => 'hpbc_row',
		 		'hpbc_custom_data' => 'custom',
	 		]
	 	);

	}
}
add_action( 'admin_init', 'hpbc_settings_init' );

if ( !function_exists( 'hpbc_section_developers_cb' ) ) {
	function hpbc_section_developers_cb( $args ) {
		?>
			<p id="<?php echo esc_attr( $args['id'] ); ?>">
				<?php 
					esc_html_e( 'Select a category and a local where you want to hide.' , 'hpbc' ); 
				?>
			</p>
		<?php
	}
}

if ( !function_exists( 'hpbc_section_developers_cb' ) ) {
	function hpbc_section_developers_cb( $args ) {

		$options = get_option( 'hpbc_options' );

		?>
			<input type="checkbox" id="<?php echo esc_attr( $args['label_for'] . '_home' ); ?>"
				data-custom="<?php echo esc_attr( $args['hpbc_custom_data'] ); ?>"
				name="hpbc_options[<?php echo esc_attr( $args['label_for'] . '_home' ); ?>]" 
				<?php echo isset($options['hpbc_field_local_home']) ? 'checked' : '' ?>    
			> <?php _e('Home','hpbc') ?><br>

			<input type="checkbox" id="<?php echo esc_attr( $args['label_for'] . '_page' ); ?>"
				data-custom="<?php echo esc_attr( $args['hpbc_custom_data'] ); ?>"
				name="hpbc_options[<?php echo esc_attr( $args['label_for'] . '_page' ); ?>]"
				<?php echo isset($options['hpbc_field_local_page']) ? 'checked' : '' ?> 
			> <?php _e('Page','hpbc') ?><br>

			<input type="checkbox" id="<?php echo esc_attr( $args['label_for'] . '_archive' ); ?>"
				data-custom="<?php echo esc_attr( $args['hpbc_custom_data'] ); ?>"
				name="hpbc_options[<?php echo esc_attr( $args['label_for'] . '_archive' ); ?>]"
				<?php echo isset($options['hpbc_field_local_archive']) ? 'checked' : '' ?> 
			> <?php _e('Archive','hpbc') ?><br>

			<input type="checkbox" id="<?php echo esc_attr( $args['label_for'] . '_feed' ); ?>"
				data-custom="<?php echo esc_attr( $args['hpbc_custom_data'] ); ?>"
				name="hpbc_options[<?php echo esc_attr( $args['label_for'] . '_feed' ); ?>]"
				<?php echo isset($options['hpbc_field_local_feed']) ? 'checked' : '' ?> 
			> <?php _e('Feed','hpbc') ?><br>

			<input type="checkbox" id="<?php echo esc_attr( $args['label_for'] . '_tag' ); ?>"
				data-custom="<?php echo esc_attr( $args['hpbc_custom_data'] ); ?>"
				name="hpbc_options[<?php echo esc_attr( $args['label_for'] . '_tag' ); ?>]"
				<?php echo isset($options['hpbc_field_local_tag']) ? 'checked' : '' ?> 
			> <?php _e('Tag','hpbc') ?><br>

			<input type="checkbox" id="<?php echo esc_attr( $args['label_for'] . '_category' ); ?>"
				data-custom="<?php echo esc_attr( $args['hpbc_custom_data'] ); ?>"
				name="hpbc_options[<?php echo esc_attr( $args['label_for'] . '_category' ); ?>]"
				<?php echo isset($options['hpbc_field_local_category']) ? 'checked' : '' ?> 
			> <?php _e('Category','hpbc') ?><br>


			<p class="description">
				<?php esc_html_e( 'Select at least a local', 'hpbc' ); ?>
			</p>
		<?php
		
	}
}

if ( !function_exists( 'hpbc_section_developers_cb' ) ) {
	function hpbc_section_developers_cb( $args ) {
		
		$options = get_option( 'hpbc_options' );
		$categories = get_categories( array ('hide_empty' => false) );

		?>
			<select 
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			data-custom="<?php echo esc_attr( $args['hpbc_custom_data'] ); ?>"
			name="hpbc_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
			>
				<option value=""><?php echo esc_attr_e( 'Select a category', 'hpbc' ); ?></option> 
				<?php
					foreach ($categories as $category) : ?>
						<option value="<?php esc_html_e( $category->term_id, 'hpbc' ); ?>" <?php echo isset( $options[ 'hpbc_field_categories' ] ) ? ( selected( $options[ 'hpbc_field_categories' ], $category->term_id, false ) ) : ( '' ); ?>>
							<?php esc_html_e( $category->cat_name, 'hpbc' ); ?>
						</option>
			    <?php endforeach; ?>
			</select>
			<p class="description">
				<?php esc_html_e( 'After to select a category and save, than this category dont show in posts list.', 'hpbc' ); ?>
			</p>
		<?php
	}
}

if ( !function_exists( 'hpbc_exclude_category' ) ) {
	function hpbc_exclude_category($query) {

		$options = get_option( 'hpbc_options' );
		$category = $options[ 'hpbc_field_categories' ];
		
		if ( ( $query->is_home() && isset( $options['hpbc_field_local_home'] ) ) || 
			 ( $query->is_single() && isset( $options['hpbc_field_local_single'] ) ) || 
			 ( $query->is_archive() && isset( $options['hpbc_field_local_archive'] ) ) || 
			 ( is_feed() && isset( $options['hpbc_field_local_isset'] ) ) || 
			 ( is_tag() && isset( $options['hpbc_field_local_tag'] ) ) || 
			 ( is_category() && isset( $options['hpbc_field_local_category'] ) ) ) {
			$query->set( 'cat', '-' . $category );
		}
		return $query;
	}
}
add_filter( 'pre_get_posts', 'hpbc_exclude_category' );

if ( !function_exists( 'hpbc_load_textdomain' ) ) {
	function hpbc_load_textdomain() {
	  load_plugin_textdomain( 'hpbc', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
	}
}
add_action( 'plugins_loaded', 'hpbc_load_textdomain' );

