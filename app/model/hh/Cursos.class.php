<?php
use Adianti\Widget\Base\TElement;

/**
 * Cursos Active Record
 * @author  <your-name-here>
 */
class Cursos extends TRecord
{
    const TABLENAME = 'cursos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}

    use SystemChangeLogTrait;

    private $diasdasemana;
    private $pessoas;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('pessoa_id');
    }

    public function addDiadaSemana($diasemana)
    {
        $object = new CursosDiasdasemana();
        $object->terapia_id = $this->id;
        $object->diasemana_id = $diasemana->id;
        $object->store();
    }

    public function getDiasdaSemanaList()
    {
        $list = new TElement('ul');
        $cursosdiasdasemana = CursosDiasdasemana::where('curso_id', '=', $this->id)->load();

        if ($cursosdiasdasemana)
        {
            foreach ($cursosdiasdasemana as $cursodiasdasemana)
            {
                $diadasemana = new Diasdasemana($cursodiasdasemana->diasemana_id);
                $tem_list = new TElement('li');
                $tem_list->add($diadasemana->nome);
                $list->add($tem_list);
            }
        }

        return $list;
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        CursosDiasdasemana::where('curso_id', '=', $this->id)->delete();
    }
    
    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        $this->diasdasemana = CursosDiasdasemana::where('curso_id', '=', $id)->load();

        // load the object itself
        return parent::load($id);
    }
    
    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        parent::deleteComposite('CursosDiasdasemana', 'curso_id', $id);

        // delete the object itself
        parent::delete($id);
    }
}
