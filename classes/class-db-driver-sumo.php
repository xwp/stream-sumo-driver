<?php
namespace WP_Stream\Sumo;
use WP_Stream;

class DB_Driver_Sumo implements WP_Stream\DB_Driver {
	/**
	 * Receiver endpoint
	 *
	 * @var string
	 */
	protected $receiver_endpoint;

	/**
	 * API access id
	 *
	 * @var string
	 */
	protected $api_access_id;

	/**
	 * API access key
	 *
	 * @var string
	 */
	protected $api_access_key;

	/**
	 * API endpoint
	 *
	 * @var string
	 */
	protected $api_endpoint;

	/**
	 * Class constructor.
	 *
	 * @param Plugin $plugin The main Plugin class.
	 */
	public function __construct() {
		// @TODO: Retrieve from a stored setting
		$this->receiver_endpoint = '';
		$this->api_access_id     = '';
		$this->api_access_key    = '';
		$this->api_endpoint      = '';
	}

	/**
	 * Insert a record
	 *
	 * @param array $record
	 *
	 * @return int
	 */
	public function insert_record( $record ) {
		$response = wp_remote_post( $this->receiver_endpoint, array(
				'method'      => 'POST',
				'timeout'     => 45,
				'httpversion' => '1.1',
				'blocking'    => false,
				'body'        => wp_json_encode( $record, JSON_UNESCAPED_SLASHES ),
			)
		);

		return is_wp_error( $response ) ? 0 : 1;
	}

	/**
	 * Retrieve records
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_records( $args ) {
		$headers = array(
			'Authorization' => 'Basic ' . base64_encode( $this->api_access_id . ':' . $this->api_access_key ),
		);

		$params = array(
			'q' => '_source=spp-test-stream',
		);

		$url = $this->api_endpoint . '?' . build_query( $params );

		if ( function_exists( 'vip_safe_wp_remote_get' ) ) {
			$response = \vip_safe_wp_remote_get( $url, array(
					'headers' => $headers,
				)
			);
		} else {
			$response = \wp_remote_get( $url, array(
					'headers' => $headers,
				)
			);
		}

		if ( is_wp_error( $response ) ) {
			return array();
		}

		$result = json_decode( $response['body'] );
		return $result;
	}

	/**
	 * Returns array of existing values for requested column.
	 *
	 * @param string $column
	 *
	 * @return array
	 */
	public function get_column_values( $column ) {
		// TODO: Implement method
		return array();
	}

	/**
	 * Purge storage
	 */
	public function purge_storage() {
		// TODO: Implement method
	}

	/**
	 * Init storage
	 */
	public function setup_storage() {
		// TODO: Implement method
	}
}
