<?php
/**
 * TiposLocalEstoque Active Record
 * @author  <your-name-here>
 */
class TiposLocalEstoque extends TRecord
{
    const TABLENAME = 'tipos_local_estoque';
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
