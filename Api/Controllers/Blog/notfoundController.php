<?php
namespace Api\Controllers\Blog;

use Api\Core\Controller;

class notfoundController extends Controller 
{

	public function index() {
		$array = array('error'=>'Nada encontrado!');

		$this->return_json($array);
	}
}