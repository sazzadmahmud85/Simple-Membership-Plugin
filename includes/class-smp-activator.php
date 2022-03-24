<?php

/**
 * Fired during plugin activation
 *
 * @link       https://portfoliosazzad.web.app
 * @since      1.0.0
 *
 * @package    Smp
 * @subpackage Smp/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Smp
 * @subpackage Smp/includes
 * @author     Sazzad Mahmud <mahmudsazzad85@gmail.com>
 */
class Smp_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_role('premiumm', 'premiumm');
	}

}
