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
class Verify extends \Page 
{   
    protected $title = "Verify";
    protected $id = "verify";

    public function getVariables()
    {
        // if user is logged in
        // redirect to index
        if(isset($_SESSION['user']) && !empty($_SESSION['user']))
        {
            header('Location: /');
            exit;
        }
        
        // if token is not set
        // redirect to login
        if(!isset($_GET['token']) || !trim($_GET['token']))
        {
            header('Location: /login');
            exit;
        }
        
        //get user by token
        $user = \Mod\User::i()->getUserByToken($_GET['token']);
        if(!$user)
        {
            $_SESSION['loginError'] = array(
                'type'  => 'danger',
                'msg'   => 'Invalid token!');
                
            header('Location: /login');
            exit;
        }
        

        if($user['user_active'] != '0')
        {
            $_SESSION['loginError'] = array(
                'type'  => 'danger',
                'msg'   => 'Invalid token!');

            header('Location: /login');
            exit;
        }

        if(isset($_POST['verify']) && !empty($_POST['verify']))
        {
            $this->verify($_POST['verify'], $user['user_id']);
        }

        $msg = array();
        if(isset($_SESSION['verifyMsg']) && is_array($_SESSION['verifyMsg']) && !empty($_SESSION['verifyMsg']))
        {
            $msg = $_SESSION['verifyMsg'];
            unset($_SESSION['verifyMsg']);
        }
        
        return array(
            'verifyMsg'   => $msg,
            'user'  => $user);

    }

    protected function verify($data, $userId)
    {
        if(!isset($data['password']) || !trim($data['password']))
        {
            $this->setVerifyMsg('Password invalid!', 'danger');
        }

        if(!isset($data['password_repeat']) || !trim($data['password_repeat']))
        {
            $this->setVerifyMsg('Password did not match!', 'danger');
        }

        if($data['password'] != $data['password_repeat'])
        {
            $this->setVerifyMsg('Password did not match!', 'danger');
        }

        $pass = \Mod\User::i()->encode($data['password']);

        $fields = array(
            'user_pass'     => $pass,
            'user_active'   => 1);
        
        $query = array(array('user_id=%s', $userId));

        control()->database()
            ->updateRows('user', $fields, $query);
        
        $_SESSION['loginError'] = array(
            'type'  => 'success',
            'msg'   => 'Your account has been activated. You can now login using your username and password');

        header('Location: /login');
        exit;
    }

    protected function setVerifyMsg($msg, $type = 'info')
    {
        $_SESSION['verifyMsg'] = array(
            'type'  => $type,
            'msg'   => $msg);

        header('Location: /verify?token='.$_GET['token']);
        exit;
    }
}
