<?php

require_once(dirname(__FILE__).'/../cawrapper/BrowseService.php');

class BrowseServiceCache extends BrowseService {
    public $wpdb;
    public $duration;
    public $service;
    public $base_url;
    public $table;
    public $mode;

	# ----------------------------------------------
	public function __construct($wpdb,$duration,$ps_base_url,$ps_table,$ps_mode){
        // Initializing vars to allow cache database fetching
        $this->wpdb = $wpdb;
        $this->duration = $duration;
        $this->service = "BrowseService";
        $base_url_components = explode("@",$ps_base_url);
        $this->base_url = $base_url_components[1];
        $this->table = $ps_table;
        $this->mode = $ps_mode;

		parent::__construct($ps_base_url,$ps_table,$ps_mode);
	}
	# ----------------------------------------------
}