<?php //-->


namespace Front\Page\User;

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
class Detail extends \Page 
{	
    const ERROR_NOT = '' ;
    const RANGE = 10;

	protected $title = "User Detail";
	protected $id = "user-detail";
    protected $template = '/user/detail.phtml';
	protected $userId = null;

	public function getVariables()
	{
        if($_SESSION['user']['user_role'] != 1 && isset($_SERVER['REDIRECT_URL'])) {
            header('Location: /');
            exit;
        }

		// get requested user Id
        $this->userId = control()->registry()->get('request', 'variables', 0);
        
        if (!$this->userId || !is_numeric($this->userId)) {
        	// throw a message
            $this->addMessage('Unknown user. Please select users from the list below.', 'danger');
            // redirect
            control()->redirect('/users');
        }

        $detail = control()->database()
        	->search('user')
        	->setColumns('*')
        	->addFilter('user_id=%s', $this->userId)
        	->getRow();
        
        // remove server from user
        if(isset($_GET['remove']) && trim($_GET['remove']))
        {
            $stat = \Mod\User::i()->setUserId($this->userId)
                ->removeUser($_GET['remove']);
            
            if(!$stat)
            {
                $_SESSION['userMsg'] = array(
                    'type'      => 'danger',
                    'msg'       => 'Something went wrong. Please try again!');

                header('Location: /user/detail/'.$this->userId);
                exit;
            }

            $_SESSION['userMsg'] = array(
                'type'      => 'success',
                'msg'       => 'Server has been removed');

            header('Location: /user/detail/'.$this->userId);
            exit;

        }

        $server = control()->database()
            ->search('dev')
            ->innerJoinOn('server','server_id=dev_server')
            ->filterByDevUser($this->userId);

         // Determine Current Page
        $page = isset($_GET['page'])? $_GET['page']: 1;

        // Get The Start In Query
        $start = (isset($_GET['page']) && $_GET['page'] != 1)?
            ($_GET['page'] - 1) * self::RANGE: 0;

        $totalServers = $server->getTotal();

        $server = $server->setStart($start)
            ->setRange(self::RANGE)
            ->getRows();

        $msg = array();
        if(isset($_SESSION['userMsg']) && !empty($_SESSION['userMsg']))
        {
            $msg = $_SESSION['userMsg'];
            unset($_SESSION['userMsg']);
        }
        
		return array(
            'userMsg'   => $msg,
			'detail'    => $detail,
            'server'    => $server,
            'page' => $page,
            'range' => self::RANGE,
            'totalServers' => $totalServers
		);

	}
}
