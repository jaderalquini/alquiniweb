<?php
/**
 * TiposIptu Active Record
 * @author  <your-name-here>
 */
class TiposIptu extends TRecord
{
    const TABLENAME = 'tipos_iptu';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
    }


}
