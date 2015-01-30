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
        if($_SESSION['user']['user_role'] == 2) {
            header('Location: /');
            exit;
        }

        // add user to server
        if(isset($_POST['addToServer']) && !empty($_POST['addToServer']))
        {
            $this->addToServer($_POST['addToServer']);
        }
        // remove user

		$users = User::getUserList();

		// Search Users
		$search = control()->database()
			->search('user');

		// Get keywords
		if(isset($_GET['keywords']) && !empty($_GET['keywords'])) {
			$keywords = sprintf('%s', $_GET['keywords']);
			$search = $search->addFilter('(user_email LIKE \'%%'.$keywords.'%%\'
				OR user_name LIKE \'%%'.$keywords.'%%\' 
				OR user_first LIKE \'%%'.$keywords.'%%\' 
				OR user_last LIKE \'%%'.$keywords.'%%\')');
			$users = $search->getRows();
		}

		// Remove User
        if(isset($_GET['del']) && trim($_GET['del']))
        {
            $this->removeUser($_GET['del']);
        }

		$users = User::getUserList();

		// typehead
		if (isset($_GET['q']) && !empty($_GET['q'])) 
        {
		// Typehead
		if (isset($_GET['q']) && !empty($_GET['q'])) {
			
			$filter = array();
	        if(!empty($_GET['q'])) 
            {
	            $keyword = trim($_GET['q']);
	            $keyword = str_replace('+','\+', $keyword);
	            $keyword = str_replace('.','\.', $keyword);
	            $filter['cat'] = $keyword;
	        }

            if(!empty($_GET['x']) && is_array($_GET['x']))
            {
                $x = implode(',', array_map(function($val) 
                    { return control()->database()->bind($val); }
                    , $_GET['x']));
            }
			
			// search for servername
			$server = control()->database()
	            ->search('server')
	            ->addFilter('server_name LIKE %s', '%'.$filter['cat'].'%');
            
            if(isset($x)) {
                $server->addFilter('(server_id NOT IN ('.$x.'))');
            }
	        
            $server = $server->getRows();
	        // encode to json
	        die(json_encode($server));
		}

		// for ajax modal
		if (isset($_GET['action']) && $_GET['action'] == 'getuser')
        { 
			$this->getUser();
		} 

		return array(
			'users' => $users
		);		
	}

	protected function getUser() 
	{
		$id = $_GET['id'];

		$user = control()->database()
			->search('user')
			->setColumns('*')
			->filterByUserId($id)
			->getRow();

		echo json_encode($user); exit;
	}

    protected function removeUser($id)
    {
        $settings = array(
            'user_id=%s',$id);
        
        $fields = array(
            'user_active'   => 2);

        control()->database()
            ->updateRows('user', $fields, array($settings));

        header('Location: /users');
        exit;
    }

    protected function addToServer($data) {
        if(!isset($data['user']) || !trim($data['user']))
        {
            die(json_encode(array(
                'type'      => 'danger',
                'msg'       => 'Something went wrong, reload the page and try again!!')));
        }

        if(!isset($data['server']) || empty($data['server']))
        {
            die(json_encode(array(
                'type'      => 'danger',
                'msg'       => 'Please select a server!')));
        }

        if(!is_array($data['server']))
        {
            die(json_encode(array(
                'type'      => 'danger',
                'msg'       => 'Something went wrong, reload the page and try again!')));
        }

        if(!isset($data['role']) || !trim($data['role']))
        {
            die(json_encode(array(
                'type'      => 'danger',
                'msg'       => 'Please select a role for the user!')));
        }

        if($data['role'] < 1 && $data['role'] > 2)
        {
            die(json_encode(array(
                'type'      => 'danger',
                'msg'       => 'Invalid role!')));
        }

        //get user information
        $user = \Mod\User::i()->getUserInfo($data['user']);
        if(empty($user))
        {
            die(json_encode(array(
                'type'      => 'danger',
                'msg'       => 'User does not exist!')));
        }

        if($user['user_active'] != 1)
        {
            die(json_encode(array(
                'type'      => 'danger',
                'msg'       => 'User is not active!')));
        }

        \Mod\User::i()->setUserId($data['user'])
            ->addToServer($data['server'], $data['role']);
    }

}
