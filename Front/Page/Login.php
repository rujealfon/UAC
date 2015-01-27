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
class Login extends \Page  {

    protected $errors = array();

    protected $title = "Login";
    protected $id = "login";
    protected $public = true;
    protected $template = "/login.phtml";

    public function getVariables() {
        
        if(isset($_POST['login']) && !empty($_POST['login'])) {
            $this->login($_POST['login']);
        }

        $msg = array();
        if(isset($_SESSION['loginError'])
            && !empty($_SESSION['loginError'])
            && is_array($_SESSION['loginError'])) {
            
            $msg = $_SESSION['loginError'];
            unset($_SESSION['loginError']);
        }

        return array(
            'loginMsg'   => $msg);
    }

    protected function login($data) {
        $error = array();
        if(!isset($data['email']) || !trim($data['email'])) {
            $this->loginError(array(
                'type'  => 'danger',
                'msg'   => 'Email Address cannot be empty!'));
        }

        if(!isset($data['password']) || !trim($data['password'])) {
            $this->loginError(array(
                'type'  => 'danger',
                'msg'   => ' Password is invalid!'));
        }

        $pass = md5($data['password']);
        $email = control()->database()->bind($data['email']);
        $account = control()->database()
            ->search('user')
            ->filterByUserPass($pass)
            ->addFilter('(user_email = '.$email.' OR user_name = '.$email.')')
            ->getRow();
        
        if(empty($account)) {
            $this->loginError(array(
                'type'      => 'danger',
                'msg'       => 'Username and Password did not match'));
        }

        $_SESSION['user'] = $account;
        header('Location: /');
        exit;
    }

    protected function loginError($data) {
        $_SESSION['loginError'] = $data;
        header('Location: /login');
        exit;
    }

}
