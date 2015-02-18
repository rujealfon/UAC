<?php //-->


namespace Front\Page\Server;

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
	protected $title = "Add Server";
	protected $id = "add-server";
    protected $template = '/server/add.phtml';

	public function getVariables()
	{
        if($_SESSION['user']['user_role'] != 1) {
            header('Location: /');
            exit;
        }

        $serverId = control()->registry()->get('request', 'variables', 0);
        if(!$serverId) 
        {
            $_SESSION['serverMsg'] = array(
                'type'      => 'danger',
                'msg'       => 'Server not found!');

            header('Location: /servers');
            exit;
        }

        if(isset($_POST['server']) && !empty($_POST['server']))
        {
            $this->saveServer($_POST['server'], $serverId);
        }

        $data = $this->getServer($serverId);

        $msg = array();
        if(isset($_SESSION['_msg']) && is_array($_SESSION['_msg']) && !empty($_SESSION['_msg']))
        {
            $msg = $_SESSION['_msg'];
            unset($_SESSION['_msg']);
        }

		return array(
            'edit'      => true,
            'serverMsg' => $msg,
            'data'      => $data);
	}

    protected function saveServer($data, $id)
    {
        if($id != $data['id'])
        {
            $_SESSION['serverMsg'] = array(
                'type'      => 'danger',
                'msg'       => 'Something went wrong!');

            header('Location: /server');
            exit;
        }
        

        $fields = array(
            'server_root'       => $data['root'],
            'server_name'       => $data['name']);

        if(trim($data['pass']) != '')
        {
            $fields['server_pass'] = \Mod\User::i()->encode($data['pass']);
        }

        control()->database()
            ->updateRows('server', $fields, array(array('server_id=%s', $id)));
        
        $_SESSION['serverMsg'] = array(
            'type'      => 'success',
            'msg'       => 'Server updated successfully');

        header('Location: /servers');
        exit;
    }

    protected function getServer($id)
    {
        $data = control()->database()
        	->search('server')
        	->addFilter('server_id=%s',$id)
        	->getRow();

        if(empty($data))
        {
            $_SESSION['serverMsg'] = array(
                'type'      => 'danger',
                'msg'       => 'Server not found!');

            header('Location: /servers');
            exit;
        }
        
        $server = array();
        foreach($data as $k => $v)
        {
            $server[str_replace('server_', '', $k)] = $v;
        }

        return $server;
    }
}
