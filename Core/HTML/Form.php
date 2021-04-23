<?php 
	
	namespace Core\HTML; // Un peu comme le repertoire du fichier actuel
	
	/**
	 * Class Form
	 * @package Core\Html
	 */
	class Form{

		private $data;
		public $surround = 'p';

	 	// Constructeur
		public function __construct($data = array()){

			$this->data = $data;
		}

		/**
		 * récupère la value
		 */
		private function getValue($index){
			if(is_object($this->data)){
				return $this->data->$index;
			}
			return isset($this->data[$index]) ? $this->data[$index] : null;

		}
		
		private function surround($html){ return "<{$this->surround}>{$html}</{$this->surround}>"; }
		
		public function input($name, $label, $options = []){
			$type = isset($options['type']) ? $options['type'] : 'text';
			$label = '<label>'.$label.'</label>';
			if($type === 'textarea'){
				$input = '<textarea name="'.$name.'">'.$this->getValue($name).'</textarea>';
			}else{
				$input = '<input type="'.$type.'" name="'.$name.'" value="'.$this->getValue($name).'">';
			}

			return $this->surround($label.$input);
		}

		/**
		 * génération des textarea
		 * @param $name tring (la valeur de l'atribut "name" dans l'input) 
		 * @param $label string (Le label)
		 * @param $option array (un tableau qui contient les options)
		 */
		public function select($name, $label, $options){
			$label = '<label>'.$label.'</label>';
			$input = '<select name="'.$name.'">';
				foreach($options as $k => $v){
					$attributs = '';
					if($k == $this->getValue($name)){
						$attributs = 'selected';
					}
					$input.= "<option value='$k' $attributs>$v</option>";
				}
			$input.= '</select>';

			return $this->surround($label.$input);
		}

		public function submit($label){ return $this->surround('<button type="submit">'.$label.'</button>'); }

	}
 ?>