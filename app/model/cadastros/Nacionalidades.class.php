<?php
/**
 * Nacionalidades Active Record
 * @author  <your-name-here>
 */
class Nacionalidades extends TRecord
{
    const TABLENAME = 'nacionalidades';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('pedepais');
    }
}
