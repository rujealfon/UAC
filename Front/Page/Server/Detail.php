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
	protected $title = "Server Detail";
	protected $id = "server-detail";
    protected $template ="/server/detail.phtml";
    protected $serverId = null;

	public function getVariables()
	{	

		// get requested user Id
        $this->serverId = control()->registry()->get('request', 'variables', 0);

        $detail = control()->database()
        	->search('server')
        	->addFilter('server_id=%s',$this->serverId)
        	->getRow();

        $user = control()->database()
        	->search('dev')
        	->innerJoinOn('user','user_id=dev_user')
            ->filterByDevServer($this->serverId)
            ->getRows();


        // control()->inspect($user);

		return array(
			'detail' => $detail,
			'user' => $user
		);
	}
    
}
