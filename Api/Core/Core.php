<?php
namespace Api\Core;

use Api\Helper\Route;

class Core extends Route {

	public function run() {

		$url = '/';
		if(isset($_GET['url'])) {
			$url .= $_GET['url'];
		}

		$url = $this->checkRoutes($url);

		$params = array();

		if(!empty($url) && $url != '/') {
			$url = explode('/', $url);
			array_shift($url);
			
			// Captando a area onde estao o controller
			// Percorrendo as rotas definidas acima
			foreach($url as $key => $value) :

				// VERIFICA se o valor enviado pela url é igual a key da $routers
				if(!empty($url[0]) && $url[0] == $key) {
					
					// Definindo a pasta de cada controller de acordo com a area
					if($url[0] == 'admin') {
						array_shift($url);

						// Controller-Area-Action padrão da area admin
						if(empty($url[0])) {
							$currentArea = 'Admin';
							$currentController = 'adminController';
							$currentAction = 'index';
						} else {
							
							$currentArea = 'Admin';
							
							// Capturando o controller da area admin	
							$currentController = $url[0].'Controller';
							array_shift($url);
							// Capturando a action da area admin
							if(isset($url[0]) && !empty($url[0])) {
								
								$currentAction = $url[0];
								array_shift($url);
								
							} else {
								$currentAction = 'index';
							}

							if(count($url) > 0) {
								$params = $url;
							}
						}	

					} else { 

						$currentArea = 'Blog';

						// Capturando o controller	
						$currentController = $url[0].'Controller';
						array_shift($url);

						// Capturando a action
						if(isset($url[0]) && !empty($url[0])) {
							$currentAction = $url[0];
							array_shift($url);
						} else {
							$currentAction = 'index';
						}

						if(count($url) > 0) {
							$params = $url;
						}			
					}
				}
			endforeach;
			
		} else {
			$currentArea = 'Blog';
			$currentController = 'homeController';
			$currentAction = 'index';		
		}
		

		// Verifica se o controller existe
		if(!file_exists('Api/Controllers/'.$currentArea.'/'.$currentController.'.php')) {
			$currentArea = 'Blog';
			$currentController = 'notfoundController';
			$currentAction = 'index';
		}

		// Veririca se o método existe
		if(!method_exists('Api\\Controllers\\'.$currentArea.'\\'.$currentController, $currentAction)) {
			$currentArea = 'Blog';
			$currentController = 'notfoundController';
			$currentAction = 'index';
		} 

		// Caminho do controller
		$currentController = "Api\\Controllers\\".$currentArea."\\".$currentController;

		$c = new $currentController();

		call_user_func_array(array($c, $currentAction), $params);
		
	}

	public function checkRoutes($url) {

		foreach($this->configRouter() as $pt => $newurl) {

			// Identifica os argumentos e substitui por regex
			$pattern = preg_replace('(\{[a-z0-9]{1,}\})', '([a-z0-9-]{1,})', $pt);

			// Faz o match da URL
			if(preg_match('#^('.$pattern.')*$#i', $url, $matches) === 1) {
				array_shift($matches);
				array_shift($matches);

				// Pega todos os argumentos para associar
				$itens = array();
				if(preg_match_all('(\{[a-z0-9]{1,}\})', $pt, $m)) {
					$itens = preg_replace('(\{|\})', '', $m[0]);
				}

				// Faz a associação
				$arg = array();
				foreach($matches as $key => $match) {
					$arg[$itens[$key]] = $match;
				}

				// Monta a nova url
				foreach($arg as $argkey => $argvalue) {
					$newurl = str_replace(':'.$argkey, $argvalue, $newurl);
				}

				$url = $newurl;
				
				break;
			}
		}
		return $url;
	}
}