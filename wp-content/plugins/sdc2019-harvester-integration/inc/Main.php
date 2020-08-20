<?php

namespace App;

use App\HarvesterClient;


class Main {
    function __construct()
	{
		add_action('admin_enqueue_scripts', array($this, 'create_menu_items'), 500);
		add_action('admin_menu', array($this, 'register_menu_harvester_cache'));

		add_action('wp_ajax_session_data', array($this, 'session_data'));
		add_action('wp_ajax_nopriv_session_data', array($this, 'session_data'));

		add_action('wp_ajax_speaker_data', array($this, 'speaker_data'));
		add_action('wp_ajax_nopriv_speaker_data', array($this, 'speaker_data'));
	}

	function create_menu_items()
	{
		$ajax_obj = ['path' => plugin_dir_url(__FILE__)];
		wp_register_script('harvester_js', plugin_dir_url(__FILE__) . 'harvester.js');
		wp_localize_script('harvester_js', 'ajax_obj', $ajax_obj);
		wp_enqueue_script('harvester_js', plugin_dir_url(__FILE__) . 'harvester.js', array('jquery'));
	}

	function register_menu_harvester_cache()
	{
		add_menu_page(
			'SDC2019 Harvester Admin',
			'Harvester Admin',
			'manage_options',
			'harvester-cache',
			array($this, 'harvester_init' )
		);
	}

	function harvester_init()
	{
		echo '
		<h1>Session Speaker Data</h1>
		<div class="harvester-container">
			<p>Refresh Harvester data cache from API.</p>
			<div class="placeholder"></div>
			<div class="submit-btn">
				<br class="clear">
				<button id="harvester-cache-refresh" type="submit" class="button action">Refresh</button>
			</div>
		</div>';
	}

	function session_data()
	{
		$sessionId = $_POST['session_id'];
		echo json_encode(HarvesterClient::getSessionData($sessionId));
		wp_die();
	}

	function speaker_data()
	{
		echo json_encode(HarvesterClient::getSpeakerData($_POST['speaker_id']));
		wp_die();
	}
}
