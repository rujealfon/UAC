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
        /*$a = control('mail')->smtp('smtp.gmail.com', 'cgalgo@openovate.com', 'cgalgo201105', 465, true)
            ->addTo('hackpswrd21@gmail.com')
            ->setSubject('testing')
            ->setBody('<h1>Head</h1><p>body</p>', true)
            ->send();

        control()->inspect($a); exit;*/
        if(isset($_POST['user']) && !empty($_POST['user'])) 
        {
            $this->addUser($_POST['user']);
        }

        $msg = $data = array();
        if(isset($_SESSION['_msg']) && !empty($_SESSION['_msg']) && is_array($_SESSION['_msg']))
        {
            $msg = $_SESSION['_msg'];
            $data = $_SESSION['data'];

            unset($_SESSION['data']);
            unset($_SESSION['_msg']);
        }

		return array(
            '__msg'     => $msg,
            'data'  => $data);
	}

    protected function addUser($user)
    {
        // check data

        // validate email email
        if(!isset($user['email']) && !trim($user['email']))
        {
            $this->setMsg('Email is invalid!', 'danger');
        }

        if(!preg_match('/^[A-Za-z0-9\._\-]+\@[A-Za-z0-9\.\-]+\.[A-Za-z]{2,6}$/i', $user['email']))
        {
            $this->setMsg('Email is invalid!', 'danger');
        }
        
        $account = control()->database()
            ->search('user')
            ->filterByUserEmail($user['email'])
            ->getRow();

        if(!empty($account))
        {
            $this->setMsg('Email is already taken', 'danger');
        }

        // validate username
        if(!isset($user['name']) || !trim($user['name']))
        {
            $this->setMsg('Username is invalid!', 'danger');
        }

        $account = control()->database()
            ->search('user')
            ->filterByUserName($user['name'])
            ->getRow();

        if(!empty($account))
        {
            $this->setMsg('Username is already taken', 'danger');
        }
        
        if(!isset($user['first']) || !trim($user['first']))
        {
            $this->setMsg('Please provide a firstname', 'danger');
        }

        if(!isset($user['last']) || !trim($user['last']))
        {
            $this->setMsg('Please provide a lastname', 'danger');
        }
        
        if(!isset($user['role']) || ($user['role'] < 0) && $data['role'] > 1)
        {
            $this->setMsg('Invalid role!', 'danger');
        }
        
        $user['status'] = '0';
        $fields = array(
            'user_email'    => $user['email'],
            'user_name'     => $user['name'],
            'user_first'    => $user['first'],
            'user_last'     => $user['last'],
            'user_role'     => $user['role'],
            'user_pass'     => '',
            'user_active'   => $user['status']);
        
        $token = \Mod\User::i()->encode($user['email']);
        $html = '<h3>User Access Control Account Verification</h3>'
            .'<p>Hi '.ucwords($user['first']).',</p><br>'
            .'<p>Please verify your account for User Access Control</p>'
            .'<p>To verify your account, Visit the link below and create your password.</p>'
            .'<p><a href="http://'.$_SERVER['HTTP_HOST'].'/verify?token='.$token.'">http://'.$_SERVER['HTTP_HOST'].'/verify?token='.$token.'</a></p>'
            .'<br>'
            .'<p>Thanks</p>'
            .'<p>Openovate Team</p>';

        control('mail')->smtp('smtp.gmail.com', 'cgalgo@openovate.com', 'cgalgo201105', 465, true)
            ->addTo($user['email'])
            ->setSubject('Account Verification')
            ->setBody($html, true)
            ->send();

        control()->database()
           ->insertRow('user', $fields);
        
        $this->setMsg('User '.$user['name'].' has been created', 'success');
    }

    protected function setMsg($msg, $type = 'warning')
    {
        $_SESSION['_msg'] = array(
            'msg'       => $msg,
            'type'      => $type);
        
        $_SESSION['data'] = $_POST['user'];
        
        header('Location: /user/add');
        exit;
    }
}
