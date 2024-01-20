<?php
/**
 * RamosAtividade Active Record
 * @author  <your-name-here>
 */
class RamosAtividade extends TRecord
{
    const TABLENAME = 'ramos_atividade';
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
