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
	/* Constants
    -------------------------------*/
    const ERROR_NOT = '' ;
    const RANGE = 10;

	protected $title = "Server";
	protected $id = "server";

	public function getVariables()
	{ 

		$servers = control()->database()
			->search('server')
			->setColumns('*');

		// Get keywords for Search
		if(isset($_GET['keywords']) && !empty($_GET['keywords'])) 
        {
			$keywords = sprintf('%s', $_GET['keywords']);
			$servers = $servers->addFilter('(server_name LIKE \'%%'.$keywords.'%%\'
				OR server_root LIKE \'%%'.$keywords.'%%\' 
				OR server_ip LIKE \'%%'.$keywords.'%%\')');
		}
		
		// Determine Current Page
        $page = isset($_GET['page'])? $_GET['page']: 1;

        // Get The Start In Query
        $start = (isset($_GET['page']) && $_GET['page'] != 1)?
            ($_GET['page'] - 1) * self::RANGE: 0;

        $totalServers = $servers->getRows();

        $servers = $servers->setStart($start)
            ->setRange(self::RANGE)
            ->getRows();


		return array(
			'servers' => $servers,
			'range' => self::RANGE,
			'page' => $page,
			'totalServers' => count($totalServers)
		);
	}
}
