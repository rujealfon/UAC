<?php //-->
 
namespace Front\Block;

use Eden\Block\Base as Block;
/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
class Search extends Block 
{
	/* Constants
	-------------------------------*/
	/* Public Properties
	-------------------------------*/
	/* Protected Properties
	-------------------------------*/
	protected $placeholder = 'Enter keyword';
	protected $keywords = null;
	protected $action = null;
	protected $fields = array();
	
	/* Private Properties
	-------------------------------*/
	/* Magic
	-------------------------------*/
	/* Public Methods
	-------------------------------*/
	public function getVariables()
	{
		return array(
			'keywords' => $this->keywords,
			'placeholder' => $this->placeholder,
			'action' => $this->action,
			'fields' => $this->fields
		);
	}
	
	/**
	 * Returns a template file
	 * 
	 * @param array data
	 * @return string
	 */
	public function getTemplate() 
	{
		return realpath(dirname(__FILE__).'/template/search.phtml');
	}
	
	public function addField($field)
	{
		$this->fields[] = $field;
		return $this;
	}
	
	/**
	 * Set the form placeholder
	 *
	 * @param string
	 * @return this
	 */
	public function setPlaceholder($placeholder)
	{
		$this->placeholder = $placeholder;
		return $this;
	}
	
	public function setAction($action)
	{
		$this->action = $action;
		return $this;
	}
	
	public function setKeyword($keywords)
	{
		$this->keywords = $keywords;
		return $this;
	}
	
	/* Protected Methods
	-------------------------------*/
}