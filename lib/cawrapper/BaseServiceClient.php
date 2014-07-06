<?php

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ServiceResult.php');

abstract class BaseServiceClient {
	# ----------------------------------------------
	private $opa_get_parameters;
	private $opa_request_body;
	private $ops_request_method;
	private $ops_service_url;
	private $ops_table;
	# ----------------------------------------------
	public function __construct($ps_base_url,$ps_service){
		$this->ops_service_url = $ps_base_url."/service.php/".$ps_service;

		$this->opa_get_parameters = array();
		$this->opa_request_body = array();
		$this->ops_request_method = "";
		$this->ops_table = "";
	}
	# ----------------------------------------------
	public function setRequestMethod($ps_method){
		if(!in_array($ps_method,array("GET","PUT","DELETE","OPTIONS"))){
			return false;
		}
		$this->ops_request_method = $ps_method;
	}
	# ----------------------------------------------
	public function getRequestMethod(){
		return $this->ops_request_method;
	}
	# ----------------------------------------------
	public function setRequestBody($pa_request_body){
		$this->opa_request_body = $pa_request_body;
	}
	# ----------------------------------------------
	public function getRequestBody(){
		return $this->opa_request_body;
	}
	# ----------------------------------------------
	public function setTable($ps_table){
		$this->ops_table = $ps_table;
	}
	# ----------------------------------------------
	public function getTable(){
		return $this->ops_table;
	}
	# ----------------------------------------------
	public function addGetParameter($ps_param_name,$ps_value){
		$this->opa_get_parameters[$ps_param_name] = $ps_value;
	}
	# ----------------------------------------------
	public function getAllGetParameters(){
		return $this->opa_get_parameters;
	}
	# ----------------------------------------------
	public function getGetParameter($ps_param_name){
		return $this->opa_get_parameters[$ps_param_name];
	}
	# ----------------------------------------------
	public function request(){
		if(!($vs_method = $this->getRequestMethod())){
			return false;
		}

		$va_get = array();
		foreach($this->getAllGetParameters() as $vs_name => $vs_val){
			$va_get[] = $vs_name."=".$vs_val;
		}

		$vs_get = sizeof($va_get)>0 ? "?".join("&",$va_get) : "";

		$vo_handle = curl_init($this->ops_service_url."/".$this->getTable()."/".$vs_get);

		curl_setopt($vo_handle, CURLOPT_CUSTOMREQUEST, $vs_method);
		curl_setopt($vo_handle, CURLOPT_RETURNTRANSFER, true);

		$va_body = $this->getRequestBody();
		if(is_array($va_body) && sizeof($va_body)>0){
			curl_setopt($vo_handle, CURLOPT_POSTFIELDS, json_encode($va_body));
		}

		$vs_exec = curl_exec($vo_handle);
		curl_close($vo_handle);
		
		return new ServiceResult($vs_exec);
	}
	# ----------------------------------------------
}

