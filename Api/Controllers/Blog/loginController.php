<?php
namespace Api\Controllers\Blog;

use Api\Core\Controller;

class loginController extends Controller
{

	public function index() { 
		$array = array('error'=>'test');

		$this->return_json($array);
	}
	
}