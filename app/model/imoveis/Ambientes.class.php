<?php
/**
 * Ambientes Active Record
 * @author  <your-name-here>
 */
class Ambientes extends TRecord
{
    const TABLENAME = 'ambientes';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('singular');
        parent::addAttribute('plural');
    }
}
