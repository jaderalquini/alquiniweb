<?php
/**
 * TiposGarantiaCampos Active Record
 * @author  <your-name-here>
 */
class TiposGarantiaCampos extends TRecord
{
    const TABLENAME = 'tipos_garantia_campos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo_garantia_id');
        parent::addAttribute('campo');
    }
}
