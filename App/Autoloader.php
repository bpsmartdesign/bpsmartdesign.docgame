<?php
	
	namespace QueDuSal; // une sorte de nom virtuel qui va correspondre en quelque sorte au dossier parent du fichier
	
	/**
	 * Class Autoloader
	 * @package QueDuSal
	 */
	class Autoloader{
		
		/**
		 * Enrégistre notre autoloader
		 */
		public static function register(){

			spl_autoload_register(array(__CLASS__, 'autoload'));

		}
	
		/**
		 * inclue le fichier correspondant à notre classe
		 * @param $class string Le nom de la Classe à charger
		 */
		public static function autoload($class){

			if(strpos($class, __NAMESPACE__.'\\') === 0){

				$class = str_replace(__NAMESPACE__.'\\', '', $class);
				$class = str_replace('\\', '/', $class);
				
				require __DIR__.'/'.$class.'.php';
			}

		}
	}

?>