<?php
/**
 * TiposImovel Active Record
 * @author  <your-name-here>
 */
class TiposImovel extends TRecord
{
    const TABLENAME = 'tipos_imovel';
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
        parent::addAttribute('pede_areaterreno');
        parent::addAttribute('pede_areaconstruida');
        parent::addAttribute('pede_ambientes');
    }
}
