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
class Edit extends \Page 
{	
	protected $title = "Edit User";
	protected $id = "edit-user";
    protected $template = '/user/add.phtml';

	public function getVariables()
	{ 
        if($_SESSION['user']['user_role'] != 1) {
            header('Location: /');
            exit;
        }
        
        $userId = control()->registry()->get('request', 'variables', 0);
        if(isset($_POST['user']) && !empty($_POST['user'])) 
        {
            $this->saveUser($_POST['user'], $userId);
        }

        if(!$userId)
        {
            $_SESSION['userMsg'] = array(
                'type'  => 'danger',
                'msg'   => 'Cannot find user');

            header('Location: /users');
            exit;
        }
        
        $data = $this->getUserInfo($userId);

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
       if($id != $data['id'])
        {
            $_SESSION['_msg'] = array(
                'type'  => 'danger',
                'msg'   => 'Something went wrong!! Please try again!');
            
            header('Location: /user/edit/'.$id);
            exit;
        }
        
        $fields = array(
            'user_first'    => $data['first'],
            'user_last'     => $data['last'],
            'user_role'     => $data['role']);

        control()->database()
            ->updateRows('user', $fields, array(array('user_id=%s', $id)));
        
        $_SESSION['userMsg'] = array(
            'type'      => 'success',
            'msg'       => 'User updated successfully');

        header('Location: /users');
        exit;
    }

    protected function getUserInfo($id) 
    {
        $data = \Mod\User::i()->getUserInfo($id);
        if(empty($data))
        {
            $_SESSION['userMsg'] = array(
                'type'  => 'danger',
                'msg'   => 'Cannot find user!');

            header('Location: /users');
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
