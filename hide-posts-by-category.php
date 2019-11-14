<?php 
/**
 * Plugin Name: Hide Posts By Category
 * Plugin URI: 
 * Description: A plugin that hide post by category
 * Version: 1.0.0
 * Author: Walfrido Oliveira
 * Author URI: 
 */

defined( 'ABSPATH' ) || exit;

function hpbc_options_page_html() {
	if (!current_user_can( 'manage_options' )) {
		return;
	}

	if ( isset( $_GET['settings-updated'] ) ) {
 	add_settings_error( 'hpbc_messages', 'hpbc_message', __( 'Configurações salvas', 'hpbc' ), 'updated' );
 	}

 	settings_errors( 'hpbc_messages' );
 	
	?>
	<div class="wrap">
		<h1><?php esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'hpbc' );
			do_settings_sections( 'hpbc' );
			submit_button( 'Salvar essa bagaça!' );
			?>
		</form>
	</div>
	<?php
}

function hpbc_options_page() {
	add_submenu_page(
        'options-general.php',
        'Hide Posts',
        'Hide Posts',
        'manage_options',
        'hpbc',
        'hpbc_options_page_html'
    );
}
add_action( 'admin_menu', 'hpbc_options_page');

function hpbc_settings_init() {

	register_setting( 'hpbc', 'hpbc_options');

	add_settings_section(
	   'hpbc_section_developers',
	    __( 'Configurações', 'hpbc' ),
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
}
add_action( 'admin_init', 'hpbc_settings_init' );

function hpbc_section_developers_cb( $args ) {
	?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>">
			<?php esc_html_e( 'Selecione a categoria que deseja ocultar.', 'hpbc' ); ?>
		</p>
	<?php
}

function hpbc_field_categories_cb( $args ) {
	
	$options = get_option( 'hpbc_options' );
	$categories = get_categories();
	?>
		<select id="<?php echo esc_attr( $args['label_for'] ); ?>"
		data-custom="<?php echo esc_attr( $args['hpbc_custom_data'] ); ?>"
		name="hpbc_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
		>
			<option value=""><?php echo esc_attr_e( 'Selecione a categoria', 'textdomain' ); ?></option> 
			<?php
				foreach ($categories as $category) : ?>
					<option value="<?php esc_html_e( $category->term_id, 'hpbc' ); ?>" <?php echo isset( $options[ 'hpbc_field_categories' ] ) ? ( selected( $options[ 'hpbc_field_categories' ], $category->term_id, false ) ) : ( '' ); ?>>
						<?php esc_html_e( $category->cat_name, 'hpbc' ); ?>
					</option>
		    <?php endforeach; ?>
		</select>
		<p class="description">
			<?php esc_html_e( 'Após selecionar a categoria e salvar, essa categoria não aparecerá mais na listagem de posts do seu site.', 'hpbc' ); ?>
		</p>
	<?php
}

function exclude_category($query) {
	$category = get_option( 'hpbc_options' )[ 'hpbc_field_categories' ];
	
	if ( ( $query->is_home() ) || ( $query->is_single() ) || ( $query->is_archive() ) || ( is_feed() ) || ( is_tag() ) || ( is_category() ) ) {
		$query->set( 'cat', '-' . $category );
	}
	return $query;
}
add_filter( 'pre_get_posts', 'exclude_category' );
 	