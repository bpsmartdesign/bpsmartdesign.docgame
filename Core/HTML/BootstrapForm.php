<?php

	namespace Core\HTML;

	class BootstrapForm {

		private $data;

		public function __construct($data = array()) {

			$this->data = $data;
		}

		private function getValue($index){
			if(is_object($this->data)){
				return $this->data->$index;
			}
			return isset($this->data[$index]) ? $this->data[$index] : null;

		}

		private function surroundForm($html) { return "<div class='col-md-12 col-sm-12 col-xs-12 form-group has-feedback'>{$html}</div>";}
		
		public function input($name, $icon = '', $type = 'text', $class = '', $id = '', $value = '', $placeholder = '', $min = '', $max = '', $readonly = '' ) {

			$icon = "<span class ='form-control-feedback left fa fa-".$icon."'></span>";
			
			if(!empty($readonly)) {
				
				$input = "<input type ='".$type."' name = '".$name."' value = '".$value."' class = 'form-control has-feedback-left ".$class."' id = '".$id."' placeholder = '".$placeholder."' readonly = '".$readonly."' />";
			}else {

				$input = "<input type ='".$type."' name = '".$name."' value = '".$value."' class = 'form-control has-feedback-left ".$class."' id = '".$id."' placeholder = '".$placeholder."' min = '".$min."'  max = '".$max."' />";
			}

			return $this->surroundForm($input.$icon);
		}

		public function submit($name, $value, $class ='', $type = 'submit') {

			return "<input type = '".$type."' class ='btn ".$class."' name = '".$name."' value ='".$value."'>";
		}
	}
?>