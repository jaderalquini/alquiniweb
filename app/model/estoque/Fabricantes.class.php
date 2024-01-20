<?php
/**
 * Fabricantes Active Record
 * @author  <your-name-here>
 */
class Fabricantes extends TRecord
{
    const TABLENAME = 'fabricantes';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
    }
}
