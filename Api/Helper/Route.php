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
		$routes['/admin/editor'] = '/admin/editor';
		$routes['/editor/register'] = '/editor/index';
		$routes['/categories'] = '/categories/index';
		$routes['/categories/register/{id}'] = '/categories/register/:id';
		$routes['/editor/edit/{id}'] = '/editor/edit/:id';
		$routes['/categories/edit/{id}'] = '/categories/edit/:id';
		
		$routes['/'] = '/home/index';
		
		return $routes;
	}

}