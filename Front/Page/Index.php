<?php //-->


namespace Front\Page;
use Front\Page\User\Detail as Detail;

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
class Index extends Detail
{

	protected $title = "User Info";
	protected $class = "user-info";


    public function getVariables()
    {
        control()->registry()->set('request', 'variables', 0, $_SESSION['user']['user_id']);

        return parent::getVariables();
    }
    
}
