<?php //-->


namespace Front\Page;

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
class Index extends \Page 
{	
	protected $title = "Index";
	protected $id = "home";

	public function getVariables()
	{	
        // get user information using current session
        $user = \Mod\User::i()->getUserInfo($_SESSION['user']['user_id']);
        // check if user is available
        if(!$user) 
        {
            //logout the current session
            header('Location: /logout');
            exit;
        }

        // List The Available Servers of Users
        $server = control()->database()
            ->search('dev')
            ->innerJoinOn('server','server_id = dev_server')
            ->filterByDevUser($_SESSION['user']['user_id'])
            ->getRows();

		return array(
            'user'  => $user,
            'server' => $server
        );
	}
    
}
