<?php
/**
 * EstadosCivis Active Record
 * @author  <your-name-here>
 */
class EstadosCivis extends TRecord
{
    const TABLENAME = 'estados_civis';
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
    }
}
