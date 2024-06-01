<?php
/**
 * TerapiasDiasdasemana Active Record
 * @author  <your-name-here>
 */
class TerapiasDiasdasemana extends TRecord
{
    const TABLENAME = 'terapias_diasdasemana';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $terapia;
    private $diasemana;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('terapia_id');
        parent::addAttribute('diasemana_id');
    }
    
    /**
     * Method set_terapia
     * Sample of usage: $terapias_diasdasemana->terapia = $object;
     * @param $object Instance of Terapias
     */
    public function set_terapia(Terapias $object)
    {
        $this->terapia = $object;
        $this->terapia_id = $object->id;
    }
    
    /**
     * Method get_terapia
     * Sample of usage: $terapias_diasdasemana->terapia->attribute;
     * @returns Terapias instance
     */
    public function get_terapia()
    {
        // loads the associated object
        if (empty($this->terapia))
            $this->terapia = new Terapias($this->terapia_id);
    
        // returns the associated object
        return $this->terapia;
    }
    
    /**
     * Method set_diasemana
     * Sample of usage: $terapias_diasdasemana->diasemana = $object;
     * @param $object Instance of Diasdasemana
     */
    public function set_diasemana(Diasdasemana $object)
    {
        $this->diasemana = $object;
        $this->diasemana_id = $object->id;
    }
    
    /**
     * Method get_diasemana
     * Sample of usage: $terapias_diasdasemana->diasemana->attribute;
     * @returns Diasdasemana instance
     */
    public function get_diasemana()
    {
        // loads the associated object
        if (empty($this->diasemana))
            $this->diasemana = new Diasdasemana($this->diasemana_id);
    
        // returns the associated object
        return $this->diasemana;
    }
}
