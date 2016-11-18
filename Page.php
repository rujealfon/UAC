<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */

class Page extends Eden\Block\Base 
{	
	/* CONSTANTS
    -------------------------------*/
	const TEMPLATE_EXTENSION = 'phtml';
	/* Public Properties
    -------------------------------*/
    /* Protected Properties
    -------------------------------*/
	protected $meta	= array();
	protected $head = array();
	protected $body = array();
	protected $foot = array();
	protected $active = '';
	
	protected $id = NULL;
	protected $title = NULL;
	
	protected $messages = array();

    public function __construct() {
    
        $page = control()->registry()->get('page');
        if(($page != 'Front\Page\Login' && $page != 'Front\Page\Verify') && !isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        //logout
        if(isset($_GET['logout'])) {
            unset($_SESSION['user']);
            header('Location: /login');
            exit;
        }
    }

	/**
	 * returns variables used for templating
	 *
	 * @return array
	 */
	public function getVariables() 
	{
		return $this->body;
	}
	
	/**
	 * returns location of template file
	 *
	 * @return string
	 */
	public function getTemplate() 
	{
		if(!$this->template) {
			$start = strrpos(get_class($this), '\\');
			
			$this->template = control('type', get_class($this))
				->str_replace('\\', DIRECTORY_SEPARATOR)
				->substr($start)
				->strtolower().'.'
				.static::TEMPLATE_EXTENSION;
		}
		
		return $this->template;
	}
	/**
	 * Transform block to string
	 *
	 * @param array
	 * @return string
	 */
	public function render() 
	{
		$messages = array();
        if(isset($_SESSION['messages'])) {
            $messages = $_SESSION['messages'];
            $_SESSION['messages'] = array();
        }
		
		$path = control()->path('template');
		$template = $this->getTemplate();
		
		$helpers = $this->getHelpers();
		
		$head = array_merge($helpers, array(
			'head' => $this->head,
			'active' =>  $this->active
		));

		$body = array_merge($helpers, $this->getVariables());
		$foot = array_merge($helpers, $this->foot);
		
		$file = $path.'/defaults/head.'.static::TEMPLATE_EXTENSION;
		$head = control()->trigger('head')->template($file, $head);
		
		$file = $path.$template;
		$body = control()->trigger('body')->template($file, $body);
		
		$file = $path.'/defaults/foot.'.static::TEMPLATE_EXTENSION;
		$foot = control()->trigger('foot')->template($file, $foot);
		
		$page = array_merge($helpers, array(
			'meta' 			=> $this->meta,
			'title'			=> $this->title,
			'class'			=> $this->id,
			'active'		=> $this->active,
			'head'			=> $head,
			'messages'		=> $messages,
			'body'			=> $body,
			'foot'			=> $foot));
		
		//page
		$file = $path.'/defaults/page.'.static::TEMPLATE_EXTENSION;
		return control()->template($file, $page);
	}
	
	protected function addStyles($styles) 
	{
		if(!isset($this->header)) {
			$this->header = array();
		}

		if(is_string($styles)) {
			$styles = array($styles);
		}

		if(!isset($this->header['styles'])) {
			$this->header['styles'] = $styles;
		} else {
			$this->header['styles'] = array_merge($this->header['styles'], $styles);
		}

		return $this;
	}

	/**
	 * Adds flash messaging
	 *
	 * @param string
	 * @param string
	 * @param boolean
	 * @return Front\Page
	 */
	protected function addMessage($message, $type = 'info' , $flash = true)
	{	
		$_SESSION['messages'][] = array(
		'type' 		=> $type,
		'message' 	=> $message);

		return $this;
	}
	
	protected function getHelpers() 
	{
		$urlRoot 	= control()->path('url');
		$cdnRoot	= control()->path('cdn');
		$language 	= control()->language();
		
		return array(
			'url' => function() use ($urlRoot) {
				echo $urlRoot;
			},
			
			'cdn' => function() use ($cdnRoot) {
				echo $cdnRoot;
			},
			
			'_' => function($key) use ($language) {
				echo $language[$key];
			});
	}

	/**
	* send email template
	*
	* @param string
	* @param array
	* @param array
	*/
	protected function sendEmail($subject, $to, $vars)
	{
		//set email account
		$smtp = eden('mail')->smtp(
			'smtp.gmail.com',
			self::EMAIL, 
			self::PASSWORD, 465, true);

		//parse the template with its variables
		$message = control()->template($this->emailTemp, $vars);
		
		//now send the parsed message to the user's email
		$smtp->setSubject($subject)
		    ->setBody($message, true);

		 foreach ($to as $email) {
	    	$smtp->addTo($email);
	    }   
		   
		return $smtp->send();
	}

	protected function error($key, $value) {
		$this->errors[] = array(
			$key => $value
		);
	}
}
