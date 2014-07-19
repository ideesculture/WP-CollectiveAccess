<?php
class simpleview_idc {
	private $vars;
	private $html_template;
	private $css_template;
	private $js_template;

	public function __construct($html_template,$html_subtemplate_suffix = ""){
		$this->vars = new stdClass();
		$suffix = ($html_subtemplate_suffix ? "__".$html_subtemplate_suffix : "");
		if(!defined(SIMPLEVIEW_IDC_DIR)) 
			define("SIMPLEVIEW_IDC_DIR",__DIR__."/views/");
		if(SIMPLEVIEW_IDC_DIR !== "") {
			if ($suffix) {
				$template_file = SIMPLEVIEW_IDC_DIR."local/".$html_template."_html".$suffix.".php";
			}
			if(!is_file($template_file)) 
				$template_file = SIMPLEVIEW_IDC_DIR."local/".$html_template."_html.php";
			if(!is_file($template_file) && $suffix) 
				$template_file = SIMPLEVIEW_IDC_DIR.$html_template."_html".$suffix.".php";
			if(!is_file($template_file)) 
				$template_file = SIMPLEVIEW_IDC_DIR.$html_template."_html.php";
			if(!is_file($template_file)) 
				die("Error : Required view html template cannot be found.");
		}
		$this->html_template = $template_file;
		$this->js_template = str_replace("_html","_js",$template_file);
		if(!is_file($this->js_template))
			$this->js_template = null;
		$this->css_template = str_replace("_html","_css",$template_file);
		if(!is_file($this->css_template))
			$this->css_template = null;
	}

	public function setVar($name, $value){
		$this->vars->$name = $value;
	}

	private function getVar($name){
		return $this->vars->$name;
	}
	public function render(){
   		ob_start();
   		if($this->css_template) {
   			print "<style>\n";
   			include($this->css_template);
   			print "</style>\n";
   		}
   		if($this->js_template) {
   			print "<script type='text/javascript'>\n";
   			include($this->js_template);
   			print "</script>\n";
   		}
		include($this->html_template);
   		$result = ob_get_contents();
   		ob_end_clean();
   		return $result;
	}
}