<?php

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BaseServiceClient.php');

class ModelService extends BaseServiceClient {
	# ----------------------------------------------
	public function __construct($ps_base_url,$ps_table){
		parent::__construct($ps_base_url,"model");

		$this->setRequestMethod("GET");
		$this->setTable($ps_table);
	}
	# ----------------------------------------------
}