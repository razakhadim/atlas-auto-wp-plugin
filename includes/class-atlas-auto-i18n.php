<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.wpstudio.agency/
 * @since      1.0.0
 *
 * @package    Atlas_Auto
 * @subpackage Atlas_Auto/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Atlas_Auto
 * @subpackage Atlas_Auto/includes
 * @author     WPStudio <hi@wpstudio.agency>
 */
class Atlas_Auto_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'atlas-auto',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
