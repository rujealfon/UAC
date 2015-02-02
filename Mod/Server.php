<?php //-->

namespace Mod;

use Eden\Core\Controller as EdenController;



/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
class Server extends EdenController
{
    protected $serverId = NULL;

    public function setId($id)
    {
        $this->serverId = $id;
        return $this;
    }

    public function getServerById()
    {
        return control()->database()
            ->search('server')
            ->filterByServerId($this->serverId)
            ->getRow();
    }

    public function addServer() 
    {
        $file = control('system')->file(control()->path('config').'/front/scripts.php')->getData();
        $server = $this->getServerById();

        if(!$server)
        {
            return false;
        }
        
        exec('sh '.$file['addServer'].' '.$server['server_root'].' '.$server['server_ip'].' '.$server['server_pass'].' ');
        return true;
    }

    public function getServerByUser($userId)
    {
        return control()->database()
            ->search('dev')
            ->filterByDevUser($userId)
            ->addInnerJoinOn('server', 'server_id = dev_server')
            ->getRows();

    }
}
