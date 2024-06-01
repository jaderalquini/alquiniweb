<?php
/**
 * CursosDiasdasemana Active Record
 * @author  <your-name-here>
 */
class CursosDiasdasemana extends TRecord
{
    const TABLENAME = 'cursos_diasdasemana';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $curso;
    private $diasemana;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('curso_id');
        parent::addAttribute('diasemana_id');
    }
    
    /**
     * Method set_terapia
     * Sample of usage: $cursos_diasdasemana->curso = $object;
     * @param $object Instance of Cursos
     */
    public function set_curso(Terapias $object)
    {
        $this->curso = $object;
        $this->curso_id = $object->id;
    }
    
    /**
     * Method get_curso
     * Sample of usage: $cursos_diasdasemana->curso->attribute;
     * @returns Terapias instance
     */
    public function get_curso()
    {
        // loads the associated object
        if (empty($this->curso))
            $this->curso = new Cursos($this->curso_id);
    
        // returns the associated object
        return $this->curso;
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