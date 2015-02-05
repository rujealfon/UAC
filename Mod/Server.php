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

        $pass = \Mod\User::i()->decode($server['server_pass']);
        exec('sh '.$file['addServer'].' '.$server['server_root'].' '.$server['server_ip'].' '.$pass.' &');
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

    public function removeServer($id)
    {
        $server = control()->database()
            ->search('server')
            ->filterByServerId($id)
            ->getRow();

        if(!$server)
        {
            return false;
        }

        //get users
        $users = control()->database()
            ->search('dev')
            ->addInnerJoinOn('user', 'user_id = dev_user')
            ->filterByDevServer($id)
            ->getRows();
        
        foreach($users as $v)
        {   
            \Mod\User::i()->setUserId($v['user_id'])
                ->removeUser($server['server_id']);
        }

        control()->database()
            ->deleteRows('dev', array(array('dev_server=%s', $id)));

        control()->database()
            ->deleteRows('server', array(array('server_id=%s', $id)));

        return true;
    }
}
