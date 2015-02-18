<?php //-->


namespace Front\Page\Server;

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
    const RANGE = 5;

	protected $title = "Server Detail";
	protected $id = "server-detail";
    protected $template ="/server/detail.phtml";
    protected $serverId = null;

	public function getVariables()
	{	
        if($_SESSION['user']['user_role'] != 1) {
            header('Location: /');
            exit;
        }
		// get requested user Id
        $this->serverId = control()->registry()->get('request', 'variables', 0);

        $detail = control()->database()
        	->search('server')
        	->addFilter('server_id=%s',$this->serverId)
        	->getRow();

        $user = control()->database()
        	->search('dev')
        	->innerJoinOn('user','user_id=dev_user')
            ->filterByDevServer($this->serverId);

        // Determine Current Page
        $page = isset($_GET['page'])? $_GET['page']: 1;

        // Get The Start In Query
        $start = (isset($_GET['page']) && $_GET['page'] != 1)?
            ($_GET['page'] - 1) * self::RANGE: 0;

        $totalUsers = $user->getTotal();

        $user = $user->setStart($start)
            ->setRange(self::RANGE)
            ->getRows();

		return array(
			'detail' => $detail,
			'user' => $user,
            'range' => self::RANGE,
            'page' => $page,
            'totalUsers' => $totalUsers
		);
	}
    
}
