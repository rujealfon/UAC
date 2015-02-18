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
class Pagination extends Block 
{
	/* Constants
	-------------------------------*/
	/* Public Properties
	-------------------------------*/
	/* Protected Properties
	-------------------------------*/
	protected $start = 0;
	protected $range = 25;
	protected $total = 0;
	protected $show	= 5;
	protected $query = array();
	protected $url = null;
	protected $class = null;
	
	/* Private Properties
	-------------------------------*/
	/* Magic
	-------------------------------*/
	/* Public Methods
	-------------------------------*/
	public function getVariables()
	{
		$pages 	= ceil($this->total / $this->range);
		$page 	= floor($this->start / $this->range) + 1;
		 
		$min 	= $page - $this->show;
		$max 	= $page + $this->show;
		
		if($min < 1) {
			$min = 1;
		}
		
		if($max > $pages) {
			$max = $pages;
		}
        
        $get = $_GET;
        unset($get['page']);
        $this->query = $get;
		
		return array(
			'class'	=> $this->class,
			'url'	=> $this->url,
			'query' => $this->query,
			'start' => $this->start,
			'range' => $this->range,
			'total' => $this->total,
			'show'	=> $this->show,
			'min'	=> $min,
			'max'	=> $max, 
			'pages' => $pages,
			'page'	=> $page);
	}
	
	/**
	 * Returns a template file
	 * 
	 * @param array data
	 * @return string
	 */
	public function getTemplate() 
	{
		return realpath(dirname(__FILE__).'/template/pagination.phtml');
	}
	
	public function setTotal($total)
	{
		$this->total = $total;
		return $this;
	}
	
	/**
	 * Sets start
	 *
	 * @param int
	 * @return Eden\Block\Component\Pagination 
	 */
	public function setStart($start) 
	{
		if($start < 0) {
			$start = 0;
		}
		
		$this->start = $start;
		
		return $this;
	}
	
	/**
	 * Sets range
	 *
	 * @param int
	 * @return Eden\Block\Component\Pagination  
	 */
	public function setRange($range) 
	{
		
		if($range < 0) {
			$range = 1;
		}
		
		$this->range = $range;
		
		return $this;
	}
	
	/**
	 * Sets page
	 *
	 * @param int
	 * @return Eden\Block\Component\Pagination  
	 */
	public function setPage($page) 
	{
		
		if($page < 1) {
			$page = 1;
		}
		
		$this->start = ( $page - 1 ) * $this->range;
		return $this;
	}
	
	/**
	 * Sets pages to show left and right of the current page
	 *
	 * @param int
	 * @return Eden\Block\Component\Pagination  
	 */
	public function setShow($show) 
	{
		if($show < 1) {
			$show = 1;
		}
		
		$this->show = $show;
		
		return $this;
	}
	
	/**
	 * This Block has pagination we need to pass in the GET query
	 *
	 * @param array
	 * @return Eden\Block\Component\Pagination 
	 */
	public function setQuery(array $query) 
	{
		$this->query = $query;
		return $this;
	}
	
	/**
	 * This Block has pagination we need to pass in the url
	 *
	 * @param array
	 * @return Eden\Block\Component\Pagination 
	 */
	public function setUrl($url) 
	{
		$this->url = $url;
		return $this;
	}
	
	/**
	 * Sets class for each page link
	 *
	 * @param array
	 * @return Eden\Block\Component\Pagination 
	 */
	public function setClass($class) 
	{
		$this->class = $class;
		return $this;
	}

	/* Protected Methods
	-------------------------------*/
}
