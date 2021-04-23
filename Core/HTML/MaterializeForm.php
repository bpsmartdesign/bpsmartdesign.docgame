<?php

	namespace Core\HTML;

	class MaterializeForm {

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

		public function surround($html) {

			return "<div class='input-field'>{$html}</div>";
		}

		public function input($name, $label, $icon = '', $type = 'text', $class = '', $id = '' ) {

			$icon = "<i class ='material-icons prefix'>".$icon."</i>";
			$input = "<input type ='".$type."' name = '".$name."' value = '".$this->getValue($name)."' class = '".$class."' id = '".$id."' />";
			$label = "<label for = '".$id."'>".$label."</label>";

			return $this->surround($icon.$input.$label);
		}
	}
?>