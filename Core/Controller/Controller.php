<?php
	
	namespace Core\Controller; // Un peu comme le repertoire du fichier actuel

	/**
	 * Class Controller
	 * @package Core\Controller
	 */
	class Controller{

		protected $viewPath; // Dossier dans le quel se trouve la vue
		protected $template; // Dossier dans le quel se trouve le template
		
		protected function render($view, $variables=[]){

			ob_start();
			if(isset($variables)){
				extract($variables);
			}
			require $this->viewPath.str_replace('.', '/', $view).'.php';
			$content = ob_get_clean();
			
			require($this->viewPath.'template/en_'.$this->template.'.php');
		}
	}
?>