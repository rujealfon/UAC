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
class Add extends \Page 
{	
	protected $title = "Add User";
	protected $id = "add-user";
    protected $template = '/user/add.phtml';

	public function getVariables()
	{ 

		// control()->inspect()
		return array();
	}
}
