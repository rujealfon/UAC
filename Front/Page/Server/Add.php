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
class Add extends \Page 
{	
	protected $title = "Add Server";
	protected $id = "add-server";
    protected $template = '/server/add.phtml';

	public function getVariables()
	{ 
		return array();
	}
}

