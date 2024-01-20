<?php
/**
 * TiposNegocio Active Record
 * @author  <your-name-here>
 */
class TiposNegocio extends TRecord
{
    const TABLENAME = 'tipos_negocio';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $campos;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('campovalor');
    }
    
    public function getCampos()
    {
        $objects = TiposNegocioCampos::where('tipo_negocio_id', '=', $this->id)->load();
        
        $campos = array();
        if ($objects)
        {
            foreach ($objects as $object)
            {
                $campos[] = $object->campo;
            }
        }
        
        return $campos;
    }
    
    public function getCamposNomes()
    {
        $field_list = Imoveis::getListFields();
        $camposnomes = array();
        $campos = $this->getCampos();
        if ($campos)
        {
            foreach ($campos as $campo)
            {
                $camposnomes[] = $field_list[$campo];
            }
        }
        
        return implode('<br>', $camposnomes);
    }
    
    public function get_campos()
    {
        return $this->getCamposNomes();
    }
}