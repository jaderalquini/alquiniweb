<?php
/**
 * Administradorascondominio Active Record
 * @author  <your-name-here>
 */
class AdministradorasCondominio extends TRecord
{
    const TABLENAME = 'administradoras_condominio';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
    }
}
