<?php
/**
 * TerapiasAgendamentos Active Record
 * @author  <your-name-here>
 */
class TerapiasAgendamentos extends TRecord
{
    const TABLENAME = 'terapias_agendamentos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    private $pessoa;
    private $terapia;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('terapia_id');
        parent::addAttribute('dtagendamento');
        parent::addAttribute('retorno');
        parent::addAttribute('compareceu');
        parent::addAttribute('obs');
    }
    
    /**
     * Method set_pessoa
     * Sample of usage: $terapias_agendamentos->pessoa = $object;
     * @param $object Instance of Pessoas
     */
    public function set_pessoa(Pessoas $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }
    
    /**
     * Method get_pessoa
     * Sample of usage: $terapias_agendamentos->pessoa->attribute;
     * @returns Pessoas instance
     */
    public function get_pessoa()
    {
        // loads the associated object
        if (empty($this->pessoa))
            $this->pessoa = new Pessoas($this->pessoa_id);
    
        // returns the associated object
        return $this->pessoa;
    }
    
    /**
     * Method set_terapia
     * Sample of usage: $terapias_agendamentos->terapia = $object;
     * @param $object Instance of Terapias
     */
    public function set_terapia(Terapias $object)
    {
        $this->terapia = $object;
        $this->terapia_id = $object->id;
    }
    
    /**
     * Method get_terapia
     * Sample of usage: $terapias_agendamentos->terapia->attribute;
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
    
    public static function getAgendamentos($pessoa = NULL, $terapia = NULL, $dtagendamento = NULL)
    {
        $repository = new TRepository('TerapiasAgendamentos');
        $criteria = new TCriteria;
        if (!empty($pessoa))
        {
            $criteria->add(new TFilter('pessoa_id', '=', $pessoa));
        }
        if (!empty($terapia))
        {
            $criteria->add(new TFilter('terapia_id', '=', $terapia));
        }
        if (!empty($dtagendamento))
        {
            $criteria->add(new TFilter('dtagendamento', '=', $dtagendamento));
        }
        $objects = $repository->load($criteria);
        
        return $objects;
    }
    
    public static function onImportData()
    {
        try
        {
            TTransaction::open('caeb');
            $conn = TTransaction::get();
            
            $query = "select * from calendar_event";
            $query .= " where id > 13482";
            $results = $conn->query($query);
            
            TTransaction::close();
            
            if ($results)
            {
                /*TTransaction::open(TSession::getValue('unit_database'));
                    
                $repository = new TRepository('TerapiasAgendamentos');
                $repository->delete();
                
                TTransaction::close();*/
                
                foreach ($results as $result)
                {
                    TTransaction::open(TSession::getValue('unit_database'));
                    
                    $object = new TerapiasAgendamentos;
                    $object->id = $result['id'];
                    $object->pessoa_id = strtoupper($result['person_id']);
                    $object->terapia_id = $result['terapy_id'];
                    $object->dtagendamento = substr($result['start_time'], 0, 10);
                    $object->retorno = strtoupper($result['description']) == 'RETORNO' ? 'S' : 'N';
                    $object->compareceu = $result['showedup'];
                    $object->store();   
                    
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
