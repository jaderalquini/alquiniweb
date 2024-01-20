<?php
/**
 * UnidadesMedida Active Record
 * @author  <your-name-here>
 */
class UnidadesMedida extends TRecord
{
    const TABLENAME = 'unidades_medida';
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
