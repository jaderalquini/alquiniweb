<?php
/**
 * Terapias Active Record
 * @author  <your-name-here>
 */
class Terapias extends TRecord
{
    const TABLENAME = 'terapias';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $diasdasemana;
    private $agendamentos;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
        parent::addAttribute('vagas');
        parent::addAttribute('diasdasemana');
    }
    
    public function addDiadaSemana(Diasdasemana $diadasemana)
    {
        $object = new TerapiasDiasdasemana();
        $object->terapia_id = $this->id;
        $object->diasemana_id = $diadasemana->id;
        $object->store();
    }
    
    public function setDiasdaSemana()
    {
        $terapiasdiasdasemana = TerapiasDiasdasemana::where('terapia_id', '=', $this->id)->load();
        
        if ($terapiasdiasdasemana)
        {
            $diasdasemana = [];
            foreach ($terapiasdiasdasemana as $terapiadiadasemana)
            {
                $diadasemana = new Diasdasemana($terapiadiadasemana->diasemana_id);
                $diasdasemana[] = $diadasemana->nome;               
            }
            
            $this->diasdasemana = implode("\n", $diasdasemana);
        }
    }
    
    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        TerapiasDiasdasemana::where('terapia_id', '=', $this->id)->delete();
    }
    
    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        $this->diasdasemana = TerapiasDiasdasemana::where('terapia_id', '=', $id)->load();
        $this->agendamentos = TerapiasAgendamentos::where('terapia_id', '=', $id)->load();    
       
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
        parent::deleteComposite('TerapiasDiasdasemana', 'terapia_id', $id);
        parent::deleteComposite('TerapiasAgendamentos', 'terapia_id', $id);
    
        // delete the object itself
        parent::delete($id);
    }
    
    public function store()
    {
        parent::store();
        
        $this->setDiasdaSemana();
        
        parent::store();
    }
    
    public static function onImportData()
    {
        try
        {
            TTransaction::open('caeb');
            $conn = TTransaction::get();
            
            $query = "select * from terapy";                    
            $results = $conn->query($query);
            
            TTransaction::close();
            
            if ($results)
            {
                TTransaction::open(TSession::getValue('unit_database'));
                    
                $repository = new TRepository('Terapias');
                $repository->delete();
                
                TTransaction::close();
                    
                foreach ($results as $result)
                {
                    TTransaction::open(TSession::getValue('unit_database'));
                    
                    $object = new Terapias;
                    $object->id = $result['id'];
                    $object->nome = strtoupper($result['name']);
                    $object->descricao = $result['description'];
                    $object->vagas = $result['vacancies'];
                    $object->store();   
                    
                    TTransaction::close();
                }
            }
            
            TTransaction::open('caeb');
            $conn = TTransaction::get();
            
            $query = "select * from terapy_weekday";                    
            $results = $conn->query($query);
            
            TTransaction::close();
            
            if ($results)
            {
                foreach ($results as $result)
                {
                    TTransaction::open(TSession::getValue('unit_database'));
                    
                    $object = new TerapiasDiasdasemana;
                    $object->id = $result['id'];
                    $object->terapia_id = $result['terapy_id'];
                    $object->diasemana_id = $result['weekday_id'];
                    $object->store();
                    
                    $terapia= new Terapias($object->terapia_id);
                    $diasemana = new Diasdasemana($object->diasemana_id);
                    if ($terapia->diasdasemana == '' || $terapia->diasdasemana == NULL)
                    {
                        $terapia->diasdasemana = $diasemana->nome;
                    }
                    else
                    {
                        $terapia->diasdasemana = $terapia->diasdasemana . ", " . $diasemana->nome;
                    }
                    $terapia->store();
                    
                    TTransaction::close();
                }
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
