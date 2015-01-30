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

           // control()->database()
             //   ->insertRow('dev', $setting);
            
            //echo shell_exec('sh '.$file['addUser'].' '.$server['server_ip'].' '.$server['server_pass'].' '.base64_decode($user['user_pass']).' '.$user['user_name'].' '.$server['server_root']);
            
            exec('/usr/bin/php /server/public/uac.dev/repo/stack/test.php');
            die(exec('whoami'));
       }
    }
}
