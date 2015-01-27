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
class Servers extends \Page 
{	
	protected $title = "Server";
	protected $id = "server";

	public function getVariables()
	{ 

		$servers = control()->database()
			->search('server')
			->setColumns('server_name','server_root','server_ip')
			->getRows();

		return array(
			'servers' => $servers
		);
	}
}
