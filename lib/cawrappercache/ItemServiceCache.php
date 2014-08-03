<?php

require_once(dirname(__FILE__) . '/../cawrapper/ItemService.php');

class ItemServiceCache extends ItemService {
	public $wpdb;
	public $duration;
	public $service;
	public $base_url;
	public $table;
	public $mode;
	public $id;
	# ----------------------------------------------
	public function __construct($wpdb,$duration,$ps_base_url,$ps_table,$ps_mode,$pn_id=null){
		// Initializing vars to allow cache database fetching
		$this->wpdb = $wpdb;
		$this->duration = $duration;
		$this->service = "ItemService";
		$base_url_components = explode("@",$ps_base_url);
		$this->base_url = $base_url_components[1];
		$this->table = $ps_table;
		$this->mode = $ps_mode;
		$this->id = $pn_id;

		parent::__construct($ps_base_url,$ps_table,$ps_mode,$pn_id);

	}
	# ----------------------------------------------
	public function request() {
        $wpdb=$this->wpdb;
        $prefix=$this->wpdb->prefix;
        $duration=$this->duration;
        $service=$this->service;
        $base_url=$this->base_url;
        $table = $this->table;
        $mode=$this->mode;
        $id=$this->id;

		// Test if JSON result already in db, if present & not older than $duration seconds, send it back in a ServiceResult
        $db_query = "SELECT result, (adddate(time, INTERVAL {$duration} SECOND) < now()) as expired FROM {$prefix}collectiveaccess_cache WHERE service=\"{$service}\" AND base_url=\"{$base_url}\" AND catable=\"{$table}\" AND mode=\"{$mode}\" AND query=\"{$id}\";";
        $db_results = $this->wpdb->get_results($db_query);
		
        // If there's only one result in cache
		if (count($db_results) == 1) {
            $db_result = reset($db_results);
            // and if not expired, send it back
            if (!$db_result->expired) return new ServiceResult($db_result->result);
        }
        // No valid result & something in cache, clear it
        if(count($db_results) > 0) {
            // problem : more than one cache retrieved, deleting former cached results
            $this->wpdb->query("DELETE FROM {$prefix}collectiveaccess_cache WHERE service=\"{$service}\" AND base_url=\"{$base_url}\" AND catable=\"{$table}\" AND mode=\"{$mode}\" AND query=\"{$id}\";");
		}
		// Generating a new result
        $result = parent::request();

        // caching it
        $db_query = "INSERT INTO {$prefix}collectiveaccess_cache (service, base_url, catable, mode, query, result)"
            ." values (\"{$service}\", \"{$base_url}\", \"{$table}\", \"{$mode}\", \"{$id}\", \"".addslashes(json_encode($result->getRawData(), JSON_UNESCAPED_UNICODE))."\")";
        $db_result = $wpdb->get_results($db_query);

        return $result;
	}
	# ----------------------------------------------

}