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
class User extends EdenController
{	
    protected $userId = NULL;

    public function getUserInfo($userId)
    {
        $user = control()->database()
            ->search('user')
            ->filterByUserId($userId)
            ->getRow();

        if(empty($user))
        {
            return false;
        }

        return $user;
    }

    public static function getUserList()
    {
        $users = control()->database()
            ->search('user')
            ->setColumns('*')
            ->getRows();

        if (empty($users)) {
            return false;
        }

        return $users;
    }

    public function setUserId($id)
    {
        $this->userId = $id;
        return $this;
    }

    public function addToServer($serverIds, $role = '2')
    {
        $file = control('system')->file(control()->path('config').'/front/scripts.php')->getData();

        foreach($serverIds as $v)
        {
            $exist = control()->database()
                ->search('dev')
                ->filterByDevUser($this->userId)
                ->filterByDevServer($v)
                ->getRow();

            if(!empty($exist))
            {
                continue;
            }
            
            //get user info
            $user = $this->getUserInfo($this->userId);
            $server = control()->database()
                ->search('server')
                ->filterByServerId($v)
                ->getRow();

            $setting = array(
                'dev_user'      => $this->userId,
                'dev_server'    => $v,
                'dev_status'    => 1,
                'dev_role'      => $role);
            
            $r = 'wheel';
            if($role != '1') 
            {
                $r = 'developer';
            }

            control()->database()
                ->insertRow('dev', $setting);
            
            exec('sh '.$file['addUser'].' '.$server['server_ip'].' '.$server['server_pass'].' '.base64_decode($user['user_pass']).' '.$user['user_name'].' '.$server['server_root'].' '.$r.' &');
        }
        
        return;
    }

    public function removeUser($serverId = NULL)
    {
        if(!$this->userId)
        {
            return false;
        }

        $user = $this->getUserInfo($this->userId);
        if(empty($user))
        {
            return false;
        }
        
        $file = control('system')->file(control()->path('config').'/front/scripts.php')->getData();
        if($serverId === NULL)
        {
            //
            $server = \Mod\Server::i()->getServerByUser($this->userId);
            foreach($server as $v)
            {
                //
                control()->database()
                    ->deleteRows('dev', array(array('dev_user=%s', $this->userId), array('dev_server=%s', $v['server_id'])));

                exec('sh '.$file['removeUser'].' '.$v['server_root'].' '.$v['server_ip'].' '.$v['server_pass'].' '.$user['user_name'].'&');
            }

            return true;
        }

        $server = \Mod\Server::i()->setId($serverId);
        if(empty($server))
        {
            return false;
        }

        control()->database()
            ->deleteRows('dev', array(array('dev_user=%', $this->userId), array('dev_server=%s', $serverId)));

        exec('sh '.$file['removeUser'].' '.$server['server_root'].' '.$server['server_ip'].' '.$server['server_pass'].' '.$user['user_name'].'&');die();
        return false;
    }
}
