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
class Setting extends \Page 
{	
	protected $title = "Setting";
	protected $id = "settings";
    protected $template = '/user/add.phtml';

	public function getVariables()
	{ 
        
        if(isset($_POST['user']) && !empty($_POST['user'])) 
        {
            $this->saveUser($_POST['user']);
        }
        
        $data = $this->getUserInfo();

        if(isset($_POST['user']) && !empty($_POST['user'])) 
        {
            $this->addUser($_POST['user']);
        }

        $msg = array();
        if(isset($_SESSION['_msg']) && !empty($_SESSION['_msg']) && is_array($_SESSION['_msg']))
        {
            $msg = $_SESSION['_msg'];
            unset($_SESSION['_msg']);
        }

		return array(
            '__msg'     => $msg,
            'edit'      => true,
            'data'      => $data);
	}

    protected function saveUser($data, $id) 
    {
       if($_SESSION['user']['user_id'] != $data['id'])
        {
            $_SESSION['_msg'] = array(
                'type'  => 'danger',
                'msg'   => 'Something went wrong!! Please try again!');
            
            header('Location: /setting');
            exit;
        }
        
        $fields = array(
            'user_first'    => $data['first'],
            'user_last'     => $data['last']);

        control()->database()
            ->updateRows('user', $fields, array(array('user_id=%s', $_SESSION['user']['user_id'])));
        
        $_SESSION['userMsg'] = array(
            'type'      => 'success',
            'msg'       => 'User updated successfully');

        header('Location: /');
        exit;
    }

    protected function getUserInfo() 
    {
        $data = \Mod\User::i()->getUserInfo($_SESSION['user']['user_id']);
        if(empty($data))
        {
            $_SESSION['userMsg'] = array(
                'type'  => 'danger',
                'msg'   => 'Cannot find user!');

            header('Location: /');
            exit;
        }
        
        $user =  array();
        foreach($data as $k => $v)
        {
            $user[str_replace('user_', '', $k)] = $v;
        }

        return $user;
    }
}
