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

		// typehead
		if (isset($_GET['q']) && !empty($_GET['q'])) {
			
			$filter = array();
	        if(!empty($_GET['q'])) {
	            $keyword = trim($_GET['q']);
	            $keyword = str_replace('+','\+', $keyword);
	            $keyword = str_replace('.','\.', $keyword);
	            $filter['cat'] = $keyword;
	        }
			
			// search for servername
			$server = control()->database()
	            ->search('server')
	            ->setColumns('server_name')
	            ->addFilter('server_name LIKE %s', '%'.$filter['cat'].'%')
	            ->getRows();
	        // encode to json
	        die(json_encode($server));
		}

		// for ajax modal
		if (isset($_GET['action']) && $_GET['action'] == 'getuser') { 
			$this->getUser();
		}  

		return array(
			'users' => $users
		);		
	}

	public function getUser() 
	{
		$id = $_GET['id'];

		$user = control()->database()
			->search('user')
			->setColumns('*')
			->filterByUserId($id)
			->getRow();

		echo json_encode($user); exit;
	}

}
