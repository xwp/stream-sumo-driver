<?php
/**
 * Plugin Name: Stream - Sumo Driver
 * Plugin URI: https://wp-stream.com/
 * Description: Send and Retrieve your Stream records with Sumo Logic.
 * Version: 0.1
 * Author: XWP, evgenykireev
 * Author URI: https://xwp.co/
 * License: GPLv2+
 * Text Domain: stream-db-driver-sumo
 * Domain Path: /languages
 */
namespace WP_Stream\Sumo;

class Plugin {
	/**
	 * Driver class name
	 *
	 * @var string
	 */
	public $driver_class = '\WP_Stream\Sumo\DB_Driver_Sumo';

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_filter( 'wp_stream_db_driver', array( $this, 'load_driver' ) );
	}

	public function load_driver( $driver_class ) {
		if ( interface_exists( '\WP_Stream\DB_Driver' ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'classes/class-db-driver-sumo.php';
			$driver_class = $this->driver_class;
		}

		return $driver_class;
	}
}

$GLOBALS['wp_stream_sumo_driver'] = new Plugin();
