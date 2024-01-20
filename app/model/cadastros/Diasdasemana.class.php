<?php
/**
 * Diasdasemana Active Record
 * @author  <your-name-here>
 */
class Diasdasemana extends TRecord
{
    const TABLENAME = 'diasdasemana';
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
