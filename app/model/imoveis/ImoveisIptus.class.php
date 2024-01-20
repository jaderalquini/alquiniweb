<?php
/**
 * ImoveisIptus Active Record
 * @author  <your-name-here>
 */
class ImoveisIptus extends TRecord
{
    const TABLENAME = 'imoveis_iptus';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $tipoiptu;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('imovel_id');
        parent::addAttribute('codigo');
        parent::addAttribute('tipo_iptu_id');
        parent::addAttribute('valor');
    }
    
    /**
     * Method set_tipoiptu
     * Sample of usage: $imoveisiptus->tipoiptu = $object;
     * @param $object Instance of TiposIptu
     */
    public function set_tipoiptu(TiposIptu $object)
    {
        $this->tipoiptu = $object;
        $this->tipoiptu_id = $object->id;
    }
    
    /**
     * Method get_tipoiptu
     * Sample of usage: $imoveisiptus->tipoiptu->attribute;
     * @returns TiposIptu instance
     */
    public function get_tipoiptu()
    {
        // loads the associated object
        if (empty($this->tipoiptu))
            $this->tipoiptu = new TiposIptu($this->tipoiptu_id);
    
        // returns the associated object
        return $this->tipoiptu;
    }
}
