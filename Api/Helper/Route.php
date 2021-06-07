<?php
namespace Api\Helper;

class Route {
	
	private $routes = array();

	protected function configRouter() {
		//Endpoints Blog
		$routes['/home'] = '/home/index';
		$routes['/blog/{slug}'] = '/blog/index/:slug';
		$routes['/categories/{namecat}'] = '/categories/index/:namecat';
		$routes['/login'] = '/login/index';


		//Endpoints Painel de controle
		$routes['/admin'] = '/admin/index';
		$routes['/editor'] = '/admin/editor';
		$routes['/editor/register'] = '/editor/index';
		$routes['/categories'] = '/categories/index';
		$routes['/categories/register'] = '/categories/register';
		$routes['/editor/edit/{id}'] = '/editor/edit/:id';
		$routes['/categories/edit/{id}'] = '/categories/edit/:id';
		
		$routes['/'] = '/home/index';
		
		return $routes;
	}

}