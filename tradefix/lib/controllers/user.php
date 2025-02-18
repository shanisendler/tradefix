<?php

/**
 * Car Controller
 *
 */

class User
{

	function addUser()
	{

		global $user;

        $data = array('name' => $_REQUEST['name'], 'phone' => $_REQUEST['phone'], 'email' => $_REQUEST['email'], 'password' => $_REQUEST['password']);

		if(!$user->isUserExists($_REQUEST['phone']))
		  echo $user->addUser($data);
		else echo '-1';
		
	}


	
}
