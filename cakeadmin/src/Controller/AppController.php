<?php

// In src/Controller/AppController.php
namespace App\Controller;
use Cake\Controller\Controller;
class AppController extends Controller{
	public function initialize()
	{
	$this->loadComponent('Flash');
	$this->loadComponent('Auth', [
	'authenticate' => [
	'Form' => [
	'fields' => [
	'username' => 'email',
	'password' => 'password'
	]
	]
	],
	'loginAction' => [
	'controller' => 'Users',
	'action' => 'login'
	]
	]);
	
	
	// Allow the display action so our pages controller
	// continues to work.
	$this->Auth->allow(['display']);
	}
}

?>