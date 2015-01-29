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
     
       return array();

    }
}
