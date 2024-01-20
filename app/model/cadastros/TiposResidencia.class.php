<?php
/**
 * TiposResidencia Active Record
 * @author  <your-name-here>
 */
class TiposResidencia extends TRecord
{
    const TABLENAME = 'tipos_residencia';
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
