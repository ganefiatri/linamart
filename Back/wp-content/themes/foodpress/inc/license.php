<?php

/**
 * FoodPress license classes
 * @package FoodPress/inc
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */

class Foodpress_License
{

	private $server = 'https://foodpress.id/';

	private $source = '';

	private $credential = array(
		'code' => '',
		'email' => '',
		'pass' => '',
		'check' => false,
	);

	private $data = array(
		'status' => 'INACTIVE',
		'message' => '',
		'domain' => '',
		'code' => '',
	);

	/**
	 * construction
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{

		$data = get_option('foodpress_license');
		if ($data && is_array($data)) {
			$this->data = wp_parse_args($data, $this->data);
		}
	}

	/**
	 * set license credential
	 * @param [type] $code [description]
	 */
	public function set_credential($credential)
	{
		$credential = (array) $credential;
		$cred['code']    = isset($credential['code']) ? sanitize_text_field($credential['code']) : '';
		$cred['email']   = isset($credential['email']) ? sanitize_email($credential['email']) : '';
		$cred['pass']    = isset($credential['pass']) ? sanitize_text_field($credential['pass']) : '';
		$cred['request'] = isset($credential['request']) ? $credential['request'] : 'register';
		$this->credential = wp_parse_args($cred, $this->credential);
		return $this;
	}

	/**
	 * connect to server
	 * @param  string $slug [description]
	 * @return [type]       [description]
	 */
	public function connect()
	{

		$vars = array(
			'website' => site_url(),
			'time' => strtotime('now'),
		);

		if ($this->credential['code']) {

			if ($this->credential['request'] == 'check') {
				$this->server = $this->server . '/sejoli-validate-license';

				$vars['license'] = $this->credential['code'];
				$vars['host'] = preg_replace("(^https?://)", "", site_url());

				$curl = wp_remote_get(add_query_arg($vars, $this->server));
			} else if ($this->credential['request'] == 'delete') {
				$this->server = $this->server . '/sejoli-delete-license';

				$curl = wp_remote_post(
					add_query_arg($vars, $this->server),
					array(
						'body' => array(
							'string'     => preg_replace("(^https?://)", "", site_url()),
							'license'    => $this->credential['code'],
							'user_email' => $this->credential['email'],
							'user_pass'  => $this->credential['pass'],
						),
						'timeout' => 120,
					)
				);
			} else {
				$this->server = $this->server . '/sejoli-license';

				$curl = wp_remote_post(
					add_query_arg($vars, $this->server),
					array(
						'body' => array(
							'string'     => preg_replace("(^https?://)", "", site_url()),
							'license'    => $this->credential['code'],
							'user_email' => $this->credential['email'],
							'user_pass'  => $this->credential['pass'],
						),
						'timeout' => 120,
					)
				);
			}

			if ($this->credential['request'] == 'delete') {

				if (is_wp_error($curl)) {
					$this->data['status'] = 'UNCONNECTED';
					$this->data['message'] = $curl->get_error_message();
				} else {
					$data = json_decode(wp_remote_retrieve_body($curl), true);

					$this->data['status'] = $data['valid'] ? 'DELETED' : 'FAILED';
					$this->data['message'] = isset($data['message']) ? sanitize_text_field($data['message']) : '';
					if ($this->data['status'] == 'DELETED') {
						delete_option('foodpress_license');
						wp_cache_delete('foodpress_license', 'options');
						delete_transient('d64866e326d996foodpresser');
						$this->data['code'] = '';
					}
				}
			} else {

				if (is_wp_error($curl)) {
					$this->data['status'] = 'INACTIVE';
					$this->data['message'] = $curl->get_error_message();
					set_transient('d64866e326d996foodpresser', $this->data, 1 * DAY_IN_SECONDS);
				} else {
					$data = json_decode(wp_remote_retrieve_body($curl), true);

					$this->data['status'] = $data['valid'] ? 'ACTIVE' : 'INACTIVE';
					$this->data['message'] = isset($data['messages'][0]) ? sanitize_text_field($data['messages'][0]) : '';
					$this->data['domain'] = preg_replace("(^https?://)", "", site_url());
					delete_option('foodpress_license');
					wp_cache_delete('foodpress_license', 'options');
					if ($this->data['status'] == 'ACTIVE') {
						$this->data['code'] = $this->credential['code'];
						update_option('foodpress_license', $this->data);
						set_transient('d64866e326d996foodpresser', $this->data, 10 * DAY_IN_SECONDS);
					}
				}
			}
		}
	}

	/**
	 * return license data
	 * @return [type] [description]
	 */
	public function data()
	{

		return $this->data;
	}
}