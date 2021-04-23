<?php

	use Core\Config; // on importe la classe Config
	use Core\Database\MysqlDatabase; // on importe la classe MysqlDatabase

	/**
	 * Class App
	 */
	class App{

		private $db_instance;
		private static $_instance;

		/**
		 * renvoi l'objet selectionné
		 * une sorte de getter global
		 */
		public static function getInstance(){
			if(is_null(self::$_instance)){
				self::$_instance = new App();
			}
			return self::$_instance;
		}

		/**
		 * Charge l'autoloader et appelle la fonction register()
		 */
		public static function load(){
			session_start();

			require ROOT.'/App/Autoloader.php';
			QueDuSal\Autoloader::register();

			require ROOT.'/Core/Autoloader.php';
			Core\Autoloader::register();

		}

		// Factory (un peu comme une fonction recursive)
		public function getTable($name){
			$class_name = '\\QueDuSal\\Table\\'.ucfirst($name).'Table';
			return new $class_name($this->getDb());
		}

		/**
		 * fonction getDb()
		 * crée une connexion à la base de données en se servant des constantes définies en paramètres
		 * @return self::$database qui correspond au résultat de la connexion à la base de données
		 */
		public function getDb(){
			$config = Config::getInstance(ROOT.'/Config/Config.php');
			if(is_null($this->db_instance)){
				$this->db_instance = new MysqlDatabase($config->get('db_name'), $config->get('db_user'), $config->get('db_pass'), $config->get('db_host'));
			}
			return $this->db_instance;
		}

		public function disconnect(){
			$_SESSION = array();
			session_destroy();
		}
	}
?>