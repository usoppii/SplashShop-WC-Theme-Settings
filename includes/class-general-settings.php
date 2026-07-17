<?php
/**
 * ShopChop General Settings page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ShopChop_General_Settings {

	const OPTION_KEY = 'shopchop_general_settings';
	const PAGE_SLUG  = 'shopchop-general-settings';

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	public function add_settings_page() {
		add_menu_page(
			esc_html__( 'ShopChop Settings', 'shopchop-theme-settings' ),
			esc_html__( 'ShopChop Settings', 'shopchop-theme-settings' ),
			'manage_options',
			self::PAGE_SLUG,
			[ $this, 'render_settings_page' ],
			'dashicons-store',
			59
		);
	}

	public function register_settings() {
		register_setting( self::PAGE_SLUG, self::OPTION_KEY, [
			'type'              => 'array',
			'sanitize_callback' => [ $this, 'sanitize' ],
			'default'           => [],
		] );

		add_settings_section(
			'shopchop_general_section',
			esc_html__( 'Shop Details', 'shopchop-theme-settings' ),
			'__return_false',
			self::PAGE_SLUG
		);

		$fields = [
			'shop_name'  => __( 'Shop Name', 'shopchop-theme-settings' ),
			'shop_phone' => __( 'Phone Number', 'shopchop-theme-settings' ),
			'shop_email' => __( 'Email', 'shopchop-theme-settings' ),
		];

		foreach ( $fields as $key => $label ) {
			add_settings_field(
				$key,
				esc_html( $label ),
				[ $this, 'render_field' ],
				self::PAGE_SLUG,
				'shopchop_general_section',
				[ 'key' => $key ]
			);
		}
	}

	public function render_field( $args ) {
		$key      = $args['key'];
		$options  = self::get_settings();
		$value    = isset( $options[ $key ] ) ? $options[ $key ] : '';
		$name     = self::OPTION_KEY . '[' . $key . ']';
		$type     = ( 'shop_email' === $key ) ? 'email' : 'text';
		$desc     = '';

		if ( 'shop_phone' === $key ) {
			$desc = esc_html__( 'Used for WhatsApp and sender details. Include country code, e.g. 60123456789.', 'shopchop-theme-settings' );
		}

		printf(
			'<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="regular-text" />',
			esc_attr( $type ),
			esc_attr( $key ),
			esc_attr( $name ),
			esc_attr( $value )
		);

		if ( $desc ) {
			printf( '<p class="description">%s</p>', $desc );
		}
	}

	public function sanitize( $input ) {
		$output = [];

		$output['shop_name']  = isset( $input['shop_name'] ) ? sanitize_text_field( $input['shop_name'] ) : '';
		$output['shop_phone'] = isset( $input['shop_phone'] ) ? preg_replace( '/[^0-9+]/', '', $input['shop_phone'] ) : '';
		$output['shop_email'] = isset( $input['shop_email'] ) ? sanitize_email( $input['shop_email'] ) : '';

		return $output;
	}

	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'ShopChop General Settings', 'shopchop-theme-settings' ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( self::PAGE_SLUG );
				do_settings_sections( self::PAGE_SLUG );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Get all ShopChop general settings.
	 *
	 * @return array
	 */
	public static function get_settings() {
		$defaults = [
			'shop_name'  => get_bloginfo( 'name' ),
			'shop_phone' => '',
			'shop_email' => get_bloginfo( 'admin_email' ),
		];

		$options = get_option( self::OPTION_KEY, [] );

		return wp_parse_args( $options, $defaults );
	}

	/**
	 * Get a single ShopChop general setting.
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public static function get( $key, $default = '' ) {
		$settings = self::get_settings();
		return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
	}
}

new ShopChop_General_Settings();
