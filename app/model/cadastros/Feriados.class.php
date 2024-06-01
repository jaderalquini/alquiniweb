<?php
/**
 * Feriados Active Record
 * @author  <your-name-here>
 */
class Feriados extends TRecord
{
    const TABLENAME = 'feriados';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('dtferiado');
        parent::addAttribute('descricao');
    }
}
