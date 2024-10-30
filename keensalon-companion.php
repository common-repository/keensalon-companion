<?php

/**
 * Plugin Name:       KeenSalon Companion
 * Plugin URI:        https://themes.keendevs.com/keensalon
 * Description:       5 extremely useful custom widgets and package post type to create an engaging website.
 * Version:           1.0.0
 * Author:            KeenDevs
 * Author URI:        https://www.keendevs.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       keensalon-companion
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit();

/**
 * Main initiation class
 *
 * @since 1.0.0
 */
final class KeenSalon_Companion {

	public $version = '1.0.0';

	public $min_php = '5.6.0';

	public $name = 'KeenSalon Companion';

	protected static $instance = null;

	public function __construct() {

		if ( $this->check_environment() ) {
			$this->define_constants();
			$this->includes();
			$this->init_hooks();
			do_action( 'keensalon_companion_loaded' );
		}

	}

	function check_environment() {

		$return = true;

		if ( version_compare( PHP_VERSION, $this->min_php, '<=' ) ) {
			$return = false;

			$notice = sprintf(
			/* translators: %s: Min PHP version */
				esc_html__( 'Unsupported PHP version Min required PHP Version: "%s"', 'keensalon-companion' ),
				$this->min_php
			);
		}

		if ( ! $return ) {
			// Add notice and deactivate the plugin
			add_action( 'admin_notices', function () use ( $notice ) { ?>
                <div class="notice is-dismissible notice-error">
                    <p><?php echo $notice; ?></p>
                </div>
			<?php } );

			if ( ! function_exists( 'deactivate_plugins' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			deactivate_plugins( plugin_basename( __FILE__ ) );
		}

		return $return;

	}

	function define_constants() {
		define( 'KEENSALON_COMPANION_VERSION', $this->version );
		define( 'KEENSALON_COMPANION_FILE', __FILE__ );
		define( 'KEENSALON_COMPANION_PATH', dirname( KEENSALON_COMPANION_FILE ) );
		define( 'KEENSALON_COMPANION_INCLUDES', KEENSALON_COMPANION_PATH . '/includes' );
		define( 'KEENSALON_COMPANION_URL', plugins_url( '', KEENSALON_COMPANION_FILE ) );
		define( 'KEENSALON_COMPANION_ASSETS', KEENSALON_COMPANION_URL . '/assets' );
		define( 'KEENSALON_COMPANION_TEMPLATES', KEENSALON_COMPANION_PATH . '/templates' );
	}

	function includes() {

		//core includes
		include_once KEENSALON_COMPANION_INCLUDES . '/class-cpt.php';
		include_once KEENSALON_COMPANION_INCLUDES . '/class-form-handler.php';
		include_once KEENSALON_COMPANION_INCLUDES . '/class-enqueue.php';
		include_once KEENSALON_COMPANION_INCLUDES . '/functions.php';

		//widgets
		include_once KEENSALON_COMPANION_INCLUDES . '/widgets/widget-card.php';
		include_once KEENSALON_COMPANION_INCLUDES . '/widgets/widget-feature.php';
		include_once KEENSALON_COMPANION_INCLUDES . '/widgets/widget-package.php';
		include_once KEENSALON_COMPANION_INCLUDES . '/widgets/widget-recent-post.php';
		include_once KEENSALON_COMPANION_INCLUDES . '/widgets/widget-section-title.php';

		//instagram feeds
		include_once KEENSALON_COMPANION_INCLUDES . '/instagram-feeds.php';

		//admin includes
		if ( is_admin() ) {
			include_once KEENSALON_COMPANION_INCLUDES . '/admin/class-admin.php';
			include_once KEENSALON_COMPANION_INCLUDES . '/admin/class-install.php';
		}

	}

	function init_hooks() {

		/* Localize our plugin */
		add_action( 'init', [ $this, 'localization_setup' ] );

		register_activation_hook( __FILE__, [ 'KeenSalon_Companion_Install', 'activate' ] );

	}

	function localization_setup() {
		load_plugin_textdomain( 'keensalon-companion', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}


	static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

function keensalon_companion() {
	return KeenSalon_Companion::instance();
}

keensalon_companion();