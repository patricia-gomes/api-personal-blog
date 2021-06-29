<?php
namespace Api\Helper;

class Route {
	
	private $routes = array();

	protected function configRouter()
	{
		//Endpoints Blog
		$routes['/home'] = '/home/index';
		$routes['/blog/{slug}'] = '/blog/index/:slug';
		$routes['/categories/{namecat}'] = '/categories/index/:namecat';
		$routes['/login'] = '/login/index';


		//Endpoints Painel de controle
		$routes['/admin'] = '/admin/index';
		$routes['/editor/register/{id}'] = '/editor/index/:id';
		$routes['/editor/edit/{id}'] = '/editor/edit/:id';
		$routes['/posts/delete/{id}'] = '/editor/delete/:id';
		$routes['/categories'] = '/categories/index';
		$routes['/categories/register/{id}'] = '/categories/register/:id';
		$routes['/categories/edit/{id_user}'] = '/categories/edit/:id_user';
		$routes['/categories/delete/{id_user}'] = '/categories/delete/:id_user';
		
		$routes['/'] = '/home/index';
		
		return $routes;
	}

}