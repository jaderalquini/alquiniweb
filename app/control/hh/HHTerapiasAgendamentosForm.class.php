<?php

use Adianti\Control\TAction;
use Adianti\Registry\TSession;
use Adianti\Widget\Base\TScript;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Wrapper\TDBUniqueSearch;

/**
 * HHPessoasForm Registration
 * @author  <your name here>
 */
class HHTerapiasAgendamentosForm extends TPage
{
    protected $form; // form
    protected $fieldlist;

    use Adianti\Base\AdiantiStandardListTrait;

    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');

        $this->setDatabase(TSession::getValue('unit_database'));              // defines the database
        $this->setActiveRecord('TerapiasAgendamentos');     // defines the active record

        // create form and table container
        $this->form = new BootstrapFormBuilder('form_TerapiasAgendamentos');
        $this->form->setFormTitle(_t('Schedules'));
        $this->form->enableClientValidation();

        $id = new THidden('id[]');
        $dtagendamento = new TDate('dtagendamento[]');
        $pessoa_id = new TDBUniqueSearch('pessoa_id[]', TSession::getValue('unit_database'), 'Pessoas', 'id', 'nome', 'nome');
        $terapia_id = new TCombo('terapia_id[]');
        $retorno = new TRadioGroup('retorno[]');
        $obs = new TEntry('obs');
        $permitir = new TCheckGroup('permitir');

        $this->fieldlist = new TFieldList;
        $this->fieldlist->generateAria();
        $this->fieldlist->width = '100%';
        $this->fieldlist->name  = 'fieldList_TerapiasAgendamentos';
        $this->fieldlist->addField( '<b>Id</b>',  $id,   ['uniqid' => true] );
        $this->fieldlist->addField( '<b>Data</b>', $dtagendamento, ['width' => '10%'] );
        $this->fieldlist->addField( '<b>Pessoa</b>',  $pessoa_id,  ['width' => '25%'] );
        $this->fieldlist->addField( '<b>Terapia</b>',   $terapia_id,   ['width' => '25%'] );
        $this->fieldlist->addField( '<b>Retorno</b>',   $retorno,   ['width' => '10%'] );
        $this->fieldlist->addField( '<b>Observação</b>',   $obs,   ['width' => '30%'] );

        // set sizes
        $id->setSize('100%');
        $pessoa_id->setSize('100%');
        $terapia_id->setSize('100%');
        $dtagendamento->setSize(100);
        $retorno->setSize(50);

        $dtagendamento->setMask('dd/mm/yyyy');
        $dtagendamento->setDatabaseMask('yyyy-mm-dd');
        $dtagendamento->setChangeAction(new TAction([$this, 'onChangeDtagendamento'], ['static' => '1']));
        $retorno->addItems( [ 'S' => _t('Yes'), 'N' => _t('No')] );
        $retorno->setUseButton(TRUE);
        $retorno->setLayout('horizontal');
        $permitir->addItems( [ 'S' => 'Permitir mais de um agendamento para a mesma data'] );

        $this->fieldlist->enableSorting();

        $this->form->addField($pessoa_id);
        $this->form->addField($terapia_id);
        $this->form->addField($dtagendamento);
        $this->form->addField($retorno);

        $this->fieldlist->addHeader();
        $this->fieldlist->addDetail( new stdClass );
        $this->fieldlist->addCloneAction();

        // add field list to the form
        $this->form->addContent( [$this->fieldlist] );
        $this->form->addContent( [$permitir] );

        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave'], ['static' => '1']), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');

        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');

        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        //$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }

    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
    
    public function onAfterSave()
    {
        AdiantiCoreApplication::loadPage('TerapiasAgendamentosList', 'onReload');
    }

    public function onSave( $param )
    {
        try
        {
            // open a transaction with database
            TTransaction::open(TSession::getValue('unit_database'));

            // get the form data
            $data = $this->form->getData('TerapiasAgendamentos');

            // validate data
            $this->onValidate($param);

            // stores the object
            $dtagendamentos = $param['dtagendamento'];
            $pessoas = $param['pessoa_id'];
            $terapias = $param['terapia_id'];
            $retornos = $param['retorno'];

            $length = count($dtagendamentos);
            for ($i = 0; $i < $length; $i++)
            {
                $object = new TerapiasAgendamentos();
                $object->pessoa_id = $pessoas[$i];
                $object->terapia_id = $terapias[$i];
                $object->dtagendamento = TDate::date2us($dtagendamentos[$i]);
                $object->retorno = $retornos[$i];
                $object->store();
            }

            // fill the form with the active record data
            $this->form->setData($data);

            // close the transaction
            TTransaction::close();

            $this->afterSaveAction = new TAction(['HHTerapiasAgendamentosList', 'onReload']);

            // shows the success message
            if (isset($this->useMessages) AND $this->useMessages === false)
            {
                AdiantiCoreApplication::loadPageURL( $this->afterSaveAction->serialize() );
            }
            else
            {
                new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $this->afterSaveAction);
            }

            return $data;
        }
        catch (Exception $e) // in case of exception
        {
            // get the form data
            $object = $this->form->getData();

            // fill the form with the active record data
            $this->form->setData($object);

            // shows the exception error message
            new TMessage('error', $e->getMessage());

            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    public function onEdit($param)
    {
        try
        {
            $this->form->clear( true );
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public static function onChangeDtagendamento($param)
    {
        TTransaction::open(TSession::getValue('unit_database'));
        $conn = TTransaction::get();

        $sep = explode("_", $param["_field_id"]);
        $id = $sep[1];

        $dtagendamento = TDate::date2us($param['_field_value']);
        $diasemana = date('N', strtotime($dtagendamento));
        $query = "SELECT distinct t.id as id, t.nome as nome, t.vagas as vagas
                    FROM terapias_diasdasemana td
                    JOIN terapias t on t.id = td.terapia_id
                    WHERE td.diasemana_id = $diasemana
                    ORDER BY t.nome";

        $results = $conn->query($query);

        if ($results)
        {
            $terapias = [];
            $terapias[NULL] = NULL;
            foreach($results as $result)
            {
                $repository = new TRepository('TerapiasAgendamentos');
                $criteria = new TCriteria;
                $criteria->add(new TFilter('terapia_id', '=', $result['id']));
                $criteria->add(new TFilter('dtagendamento', '=', $dtagendamento));
                $vagas = $repository->count($criteria);

                if ($result['vagas'] > $vagas)
                {
                    $terapias[$result['id']] = $result['nome'];
                }
            }
            TCombo::reload("form_TerapiasAgendamentos", "terapia_id_{$id}", $terapias);
        }

        TTransaction::close();
    }
    
    public static function onValidate($param)
    {
        $errors = [];

        $dtagendamentos = $param['dtagendamento'];
        $pessoas = $param['pessoa_id'];
        $terapias = $param['terapia_id'];
        $permitir = isset($param['permitir']) ? TRUE : FALSE;

        TTransaction::open(TSession::getValue('unit_database'));

        $length = count($dtagendamentos);
        for ($i = 0; $i < $length; $i++)
        {
            $terapia = new Terapias($terapias[$i]);
            $pessoa = new Pessoas($pessoas[$i]);
            $vagas = count(TerapiasAgendamentos::getAgendamentos(NULL, $terapias[$i], TDate::date2us($dtagendamentos[$i])));

            if ($terapia->vagas <= $vagas)
            {
                $errors[] = "Não há vagas para $terapia->nome para o dia " . $dtagendamentos[$i];
            }

            $agendamento = TerapiasAgendamentos::getAgendamentos($pessoas[$i], $terapias[$i], TDate::date2us($dtagendamentos[$i]));

            if ($agendamento)
            {
                $errors[] = "Já existe um agendamento para $pessoa->nome na terapia $terapia->nome para o dia " . $dtagendamentos[$i];
            }

            $agendamento = TerapiasAgendamentos::getAgendamentos($pessoas[$i], NULL, TDate::date2us($dtagendamentos[$i]));

            if ($agendamento && !$permitir)
            {
                $errors[] = "Já existe um agendamento para $pessoa->nome para o dia " . $dtagendamentos[$i];
            }

            $feriados = Feriados::where('dtferiado', '=', TDate::date2us($dtagendamentos[$i]))->load();

            if ($feriados)
            {
                $errors[] = "Agendamentos não permitidos para a data  " . $dtagendamentos[$i];
            }
        }

        TTransaction::close();

        if (!empty($errors) > 0)
        {
            throw new Exception(implode("<br>", $errors));
        }
    }
}