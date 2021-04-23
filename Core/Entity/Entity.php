<?php

	namespace Core\Entity; // Un peu comme le repertoire du fichier actuel
	
	/**
	 * Class Entity
	 * @package Core\Entity
	 */
	class Entity{
		
		// fonction magique
		public function __get($key){

			$method = 'get'.ucfirst($key);
			$this->$key = $this->$method();

			return $this->$key;
		}
	}

?>