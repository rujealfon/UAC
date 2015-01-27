<?php //-->


namespace Front\Page;
use \Mod\User;

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
class Users extends \Page 
{	
	protected $title = "User";
	protected $id = "user";

	public function getVariables()
	{ 

		$users = User::getUserList();

		return array(
			'users' => $users);		
	}

}
