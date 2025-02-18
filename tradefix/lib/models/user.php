<?php

/**
 * User Model 
 */

class UserModel
{
	

	function addUser($user_info)
	{
		
    global $db;

    $user_info['phone'] = str_replace('-', '', $user_info['phone']);
    
    $query = $db->prepare("INSERT INTO users(name, phone_number, email, password) VALUES(:name, :phone, :email, :password)");
		$query->execute([':name' => $user_info['name'], ':phone' => $user_info['phone'], ':email' => $user_info['email'], ':password' => md5($user_info['password'])]);
		
    // Get the last inserted ID
    return $db->lastInsertId();

	}

  function isUserExists($phone){

     global $db;

     $phone = str_replace('-', '', $phone);

     $query = $db->prepare("SELECT id FROM users WHERE phone_number = :phone LIMIT 1");
		 $query->execute([':phone' => $phone]);     
     $results = $query->fetchAll();
          
     return count($results) > 0;

  }
	
	


}
