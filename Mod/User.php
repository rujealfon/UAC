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
}
