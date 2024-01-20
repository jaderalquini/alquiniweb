<?php
/**
 * TiposNegocioCampos Active Record
 * @author  <your-name-here>
 */
class TiposNegocioCampos extends TRecord
{
    const TABLENAME = 'tipos_negocio_campos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo_negocio_id');
        parent::addAttribute('campo');
    }
}
