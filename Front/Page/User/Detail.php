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
	protected $title = "User Detail";
	protected $id = "user-detail";
    protected $template = '/user/detail.phtml';
	protected $userId = null;

	public function getVariables()
	{ 
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

        $server = control()->database()
            ->search('dev')
            ->innerJoinOn('server','server_id=dev_server')
            ->filterByDevUser($this->userId)
            ->getRows();

		return array(
			'detail' => $detail,
            'server' => $server
		);

	}
}
