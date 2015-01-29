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
class Add extends \Page 
{	
	protected $title = "Add Server";
	protected $id = "add-server";
    protected $template = '/server/add.phtml';

	public function getVariables()
	{
        if(isset($_POST['server']) && !empty($_POST['server']) && is_array($_POST['server']))
        {
            $this->addServer($_POST['server']);
        }

        $msg = $data = array();
        if(isset($_SESSION['_msg']) && is_array($_SESSION['_msg']) && !empty($_SESSION['_msg']))
        {
            $msg = $_SESSION['_msg'];
            $data = $_SESSION['data'];
            unset($_SESSION['_msg']);
            unset($_SESSION['data']);
        }

		return array(
            'serverMsg' => $msg,
            'data'      => $data);
	}

    protected function addServer($data)
    { 
        if(!isset($data['name']) || !trim($data['name']))
        {
            $this->setMsg('Server name is required!', 'danger');
        }

        if(!isset($data['root']) || !trim($data['root']))
        {
            $this->setMsg('Server username is invalid!', 'danger');
        }

        if(!isset($data['pass']) || !trim($data['pass']))
        {
            $this->setMsg('Password is invalid', 'danger');
        }

        if(!isset($data['ip']) || !trim($data['ip']))
        {
            $this->setMsg('Server IP is invalid!', 'danger');
        }

        $server = control()->database()
            ->search('server')
            ->filterByServerIp($data['ip'])
            ->getRow();

        if(!empty($server))
        {
            $this->setMsg('Server IP already exist!', 'danger');
        }
        
        $fields = array(
            'server_name'   => $data['name'],
            'server_root'   => $data['root'],
            'server_pass'   => $data['pass'],
            'server_ip'     => $data['ip']);

        control()->database()
            ->insertRow('server', $fields);

        
        $this->setMsg('Server added', 'success');
        
    }

    protected function setMsg($msg, $type = 'info')
    {
        $_SESSION['_msg'] = array(
            'msg'   => $msg,
            'type'  => $type);
        
        $_SESSION['data'] = $_POST['server'];
        header('Location: /server/add');
        exit;
    }
}
