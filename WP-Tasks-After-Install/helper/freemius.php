<?php
/* phpcs:disable Generic.ControlStructures.InlineControlStructure.NotAllowed */

if ( ! class_exists( 'Freemius_License_Auto_Activator' ) ) :

class Freemius_License_Auto_Activator {
	private $priv_shortcode;
	private $license_key;

	public function __construct( $shortcode, $license_key ) {
		$this->priv_shortcode = $shortcode;
		$this->license_key    = $license_key;

		// Only run in admin, after Freemius is loaded.
		add_action( 'admin_init', array( $this, 'license_key_auto_activation' ), 999 );
	}

	public function license_key_auto_activation() {
		$fs = false;

		$this->debug_notices( 'license_key_auto_activation started for ' . $this->priv_shortcode );

		// Get Freemius instance: either function or global.
		if ( function_exists( $this->priv_shortcode ) ) {
			$fs = call_user_func( $this->priv_shortcode );
		} else {
			global ${$this->priv_shortcode};
			if ( isset( ${$this->priv_shortcode} ) ) {
				$fs = ${$this->priv_shortcode};
			}
		}

		if ( empty( $fs ) ) {
			$this->debug_notices( 'Error: Freemius instance is empty for ' . $this->priv_shortcode );
			return;
		}

		if ( false === $fs->has_api_connectivity() ) {
			$this->debug_notices( 'Error: no API connectivity for ' . $this->priv_shortcode );
			return;
		}

		// Option key so we don't keep retrying forever.
		$option_key = "{$this->priv_shortcode}_auto_license_activation_status";
		$status     = get_option( $option_key );

		// If already done, don't try again.
		if ( 'done' === $status ) {
			$this->debug_notices( "Notice: license for {$this->priv_shortcode} already activated (status=done)" );
			return;
		}

		// If user already registered manually, just mark as done.
		if ( $fs->is_registered() ) {
			$this->debug_notices( "Notice: user already opted-in / registered for {$this->priv_shortcode}" );
			update_option( $option_key, 'done' );
			return;
		}

		if ( empty( $this->license_key ) ) {
			$this->debug_notices( 'Error: no license key provided for ' . $this->priv_shortcode );
			update_option( $option_key, 'no_license_key' );
			return;
		}

		$this->debug_notices( "Attempting Freemius license activation for {$this->priv_shortcode}" );

		try {
			$next_page = $fs->activate_migrated_license( $this->license_key );
		} catch ( Exception $e ) {
			$this->debug_notices( 'Error: ' . $e->getMessage() );
			update_option( $option_key, 'unexpected_error' );
			return;
		}

		if ( $fs->can_use_premium_code() ) {
			update_option( $option_key, 'done' );
			$this->debug_notices( 'Success: license key install is done for ' . $this->priv_shortcode );

			if ( is_string( $next_page ) ) {
				// Safe redirect helper from Freemius SDK.
				if ( function_exists( 'fs_redirect' ) ) {
					fs_redirect( $next_page );
				} else {
					wp_safe_redirect( $next_page );
					exit;
				}
			}
		} else {
			$this->debug_notices( 'Error: license key install failed for ' . $this->priv_shortcode );
			update_option( $option_key, 'failed' );
		}
	}

	private function debug_notices( $message ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( $message ); // phpcs:ignore
		}
		if ( defined( 'FREEMIUS_WP_CLI' ) && FREEMIUS_WP_CLI ) {
			echo "$message \n";
		}
	}
}

endif;
;

