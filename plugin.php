<?php
/**
 * Plugin Name:       All the Shortcodes
 * Description:       Lists every shortcode registered on the site under Settings → View all shortcodes.
 * Version:           2.0.0
 * Author:            Rowan Price & Associates
 * Author URI:        https://www.rowanprice.com
 * Text Domain:       view-all-shortcodes
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.0
 * Requires PHP:      7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class View_All_Shortcodes {
	private const PAGE_SLUG = 'view-all-shortcodes';

	/**
	 * The hook suffix returned when the settings page is registered.
	 *
	 * @var string
	 */
	private $page_hook = '';

	/**
	 * Register WordPress hooks.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Add the plugin page to the Settings menu.
	 */
	public function register_settings_page() {
		$this->page_hook = add_options_page(
			__( 'View all shortcodes', 'view-all-shortcodes' ),
			__( 'View all shortcodes', 'view-all-shortcodes' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Load assets only on this plugin's settings page.
	 *
	 * @param string $hook_suffix The current admin page hook suffix.
	 */
	public function enqueue_assets( $hook_suffix ) {
		if ( $this->page_hook !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'view-all-shortcodes-admin',
			plugins_url( 'assets/css/admin.css', __FILE__ ),
			array(),
			'2.0.0'
		);

		wp_enqueue_script(
			'view-all-shortcodes-admin',
			plugins_url( 'assets/js/admin.js', __FILE__ ),
			array(),
			'2.0.0',
			true
		);
	}

	/**
	 * Render the settings page.
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		global $shortcode_tags;

		$shortcodes = is_array( $shortcode_tags ) ? array_keys( $shortcode_tags ) : array();
	natcasesort( $shortcodes );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Listing of shortcodes on this site', 'view-all-shortcodes' ); ?></h1>
			<p><?php esc_html_e( 'Select a shortcode to copy it to your clipboard.', 'view-all-shortcodes' ); ?></p>
			<p id="view-all-shortcodes-status" class="screen-reader-text" aria-live="polite"></p>

			<?php if ( empty( $shortcodes ) ) : ?>
				<div class="notice notice-info inline">
					<p><?php esc_html_e( 'No shortcodes are currently registered on this site.', 'view-all-shortcodes' ); ?></p>
				</div>
			<?php else : ?>
				<h2><?php esc_html_e( 'Shortcodes', 'view-all-shortcodes' ); ?></h2>
				<ul class="view-all-shortcodes-list">
					<?php foreach ( $shortcodes as $shortcode ) : ?>
						<?php
						$shortcode_text = sprintf( '[%s]', $shortcode );
						$copy_label     = sprintf(
							/* translators: %s: shortcode text. */
							__( 'Copy %s', 'view-all-shortcodes' ),
							$shortcode_text
						);
						?>
						<li>
							<button
								type="button"
								class="button button-secondary view-all-shortcodes-copy"
								data-shortcode="<?php echo esc_attr( $shortcode_text ); ?>"
								data-success-message="<?php echo esc_attr( sprintf( __( '%s copied to clipboard.', 'view-all-shortcodes' ), $shortcode_text ) ); ?>"
								data-error-message="<?php esc_attr_e( 'Your browser could not copy the shortcode. Please copy it manually.', 'view-all-shortcodes' ); ?>"
								aria-label="<?php echo esc_attr( $copy_label ); ?>"
							>
								<code><?php echo esc_html( $shortcode_text ); ?></code>
							</button>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php
	}
}

$view_all_shortcodes = new View_All_Shortcodes();
$view_all_shortcodes->init();
