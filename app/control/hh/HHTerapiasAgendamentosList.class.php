<?php

use Adianti\Registry\TSession;
use Adianti\Widget\Base\TElement;

/**
 * HHPessoasList Listing
 * @author  <your name here>
 */
class HHTerapiasAgendamentosList extends TPage
{
    protected $form;     // registration form
    protected $html;
    protected $terapias = [];

    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->form = new TForm('form_search_TerapiasAgendamentos');
        
        $dtagendamento = new TDate('dtagendamento');
        
        $dtagendamento->setSize(100);
        $dtagendamento->setMask('dd/mm/yyyy');
        $dtagendamento->setDatabaseMask('yyyy-mm-dd');
        $dtagendamento->setExitAction(new TAction( [ $this, 'onExitDtagendamento' ] ));
        
        if (empty(TSession::getValue('TerapiasAgendamentos_filter_dtagendamento')))
        {
            TSession::setValue('TerapiasAgendamentos_filter_dtagendamento', date('d/m/Y'));
        }
        
        $dtagendamento->setValue(TSession::getValue('TerapiasAgendamentos_filter_dtagendamento'));
        
        $this->html = new TElement('html');
        
        $panel = new TPanelGroup(_t('Schedules'));
        $this->form->add($panel);
        $this->form->add($this->html);
        
        $table = new TTable;
        $table->width = '100%';
        
        $btnPrev = new TButton('prev');
        $btnPrev->setAction(new TAction([$this, 'onPrev'], ['register_state' => 'false']));
        $btnPrev->setImage('fas:chevron-left');
        
        $btnNext = new TButton('next');
        $btnNext->setAction(new TAction([$this, 'onNext'], ['register_state' => 'false']));
        $btnNext->setImage('fas:chevron-right');
        
        $btnToday = new TButton('today');
        $btnToday->setAction(new TAction([$this, 'Today'], ['register_state' => 'false']), _t('Today'));
        
        $btnNew = new TButton('new');
        $btnNew->setAction(new TAction(['HHTerapiasAgendamentosForm', 'onEdit'], ['register_state' => 'false']), _t('New'));
        $btnNew->setImage('fa:plus green');
        
        $btnPrint = new TButton('print');
        $btnPrint->setAction(new TAction([$this, 'onInputDialog'], ['register_state' => 'false']), _t('Print'));
        $btnPrint->setImage('fas:print blue');
        
        $row = $table->addRow();
        $prev = $row->addCell($btnPrev);
        $prev->width = 25;
        $next = $row->addCell($btnNext);
        $next->width = 25;
        $today = $row->addCell($btnToday);
        $today->width = 50;
        $dt = $row->addCell($dtagendamento);
        $next = $row->addCell($btnNew);
        $next->width = 75;
        $print = $row->addCell($btnPrint);
        $print->width = 75;
        
        $panel->add($table);
        
        $this->form->setFields( [ $btnPrev, $btnNext, $btnToday, $dtagendamento, $btnNew, $btnPrint ] );
        
        $this->onReload();
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    public static function onExitDtagendamento($param)
    {
        if ($param['dtagendamento'] != TSession::getValue(('TerapiasAgendamentos_filter_dtagendamento')))
        {
            TSession::setValue('TerapiasAgendamentos_filter_dtagendamento', $param['dtagendamento']);
            AdiantiCoreApplication::loadPage('HHTerapiasAgendamentosList', 'onReload');
        }
        
    }
        
    /**
     * Load the datagrid with the database objects
     */
    public function onReload()
    {
        try
        {
            // open a transaction with database
            TTransaction::open(TSession::getValue('unit_database'));
            $conn = TTransaction::get();

            $dtagendamento = TDate::date2us(TSession::getValue('TerapiasAgendamentos_filter_dtagendamento'));
            $diasemana = date('N', strtotime($dtagendamento));

            $query = "SELECT distinct t.id as id
                    FROM terapias_diasdasemana td
                    JOIN terapias t on t.id = td.terapia_id
                    WHERE td.diasemana_id = $diasemana
                    ORDER BY t.nome";

            $results = $conn->query($query);

            $this->html->clearChildren();

            if ($results)
            {
                foreach($results as $result)
                {
                    $terapia = new Terapias($result['id']);

                    $objects = TerapiasAgendamentos::getAgendamentos(NULL, $terapia->id, $dtagendamento);
                    $count = count($objects);
                    $vagas = $terapia->vagas - $count;

                    $datagrid = new BootstrapDatagridWrapper(new TDataGrid);
                    $datagrid->style = 'width: 100%';

                    $column_pessoa_id = new TDataGridColumn('{pessoa->nome}', _t('Person'), 'left');
                    $column_retorno = new TDataGridColumn('retorno', _t('Return'), 'left', 100);
                    $column_compareceu = new TDataGridColumn('compareceu', _t('Showed Up'), 'left', 100);
                    $column_obs = new TDataGridColumn('obs', _t('Observation'), 'left');

                    $column_retorno->setTransformer( function($value, $object, $row) {
                        $class = ($value=='N') ? 'danger' : 'success';
                        $label = ($value=='N') ? _t('No') : _t('Yes');
                        $div = new TElement('span');
                        $div->class="label label-{$class}";
                        $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
                        $div->add($label);
                        return $div;
                    });

                    $column_compareceu->setTransformer( function($value, $object, $row) {
                        switch ($value)
                        {
                            case 'S':
                                $class = 'success';
                                $label = _t('Yes');
                                break;
                                
                            case'N':
                                $class = 'danger';
                                $label = _t('No');
                                break;
                                
                            default:
                                $class = NULL;
                                $label = NULL;
                                break;
                        }

                        if (!empty($class) && !empty($label))
                        {
                            $div = new TElement('span');
                            $div->class="label label-{$class}";
                            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
                            $div->add($label);
                            return $div;
                        }
                    });

                    $datagrid->addColumn($column_pessoa_id);
                    $datagrid->addColumn($column_retorno);
                    $datagrid->addColumn($column_compareceu);
                    $datagrid->addColumn($column_obs);

                    $action1 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
                    $action2 = new TDataGridAction([$this, 'onSetCompareceu'], ['id'=>'{id}', 'compareceu'=>'S']);
                    $action3 = new TDataGridAction([$this, 'onSetCompareceu'], ['id'=>'{id}', 'compareceu'=>'N']);

                    $action2->setDisplayCondition( [$this, 'displayCompareceu'] );
                    $action3->setDisplayCondition( [$this, 'displayNaoCompareceu'] );

                    $datagrid->addAction($action1 ,_t('Delete'), 'far:trash-alt red');
                    $datagrid->addAction($action2, _t('Showed Up'), 'fas:check-circle green');
                    $datagrid->addAction($action3, _t('Didn\'t Show Up'), 'far:times-circle red');

                    $datagrid->createModel();

                    $div = new TElement('div');
                    $div->id = 'div' . $terapia->id;
                                        
                    $this->html->add($div);
                    $div->add(new TFormSeparator($terapia->nome));

                    if ($objects)
                    {
                        foreach ($objects as $object)
                        {
                             $datagrid->addItem($object);
                        }
                    }

                    $div->add($datagrid);

                    $div->add(new TLabel('Pacientes agendados: ' . $count, NULL, NULL, 'bold'));
                    $div->add(new TElement('br'));
                    $div->add(new TLabel('Vagas disponíveis: ' . $vagas, NULL, NULL, 'bold'));
                    $div->add(new TElement('hr'));

                    $this->terapias[$terapia->id] = $div;
                }
            }

            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onPrev()
    {
        $data = $this->form->getData();

        $obj = new StdClass;
        $obj->dtagendamento = date('d/m/Y', strtotime('-1 days', strtotime($data->dtagendamento)));

        TSession::setValue('TerapiasAgendamentos_filter_dtagendamento', $obj->dtagendamento);

        $this->form->setData($obj);

        AdiantiCoreApplication::loadPage('HHTerapiasAgendamentosList', 'onReload');
    }

    public function onNext()
    {
        $data = $this->form->getData();

        $obj = new StdClass;
        $obj->dtagendamento = date('d/m/Y', strtotime('+1 days', strtotime($data->dtagendamento)));

        TSession::setValue('TerapiasAgendamentos_filter_dtagendamento', $obj->dtagendamento);

        $this->form->setData($obj);

        AdiantiCoreApplication::loadPage('HHTerapiasAgendamentosList', 'onReload');
    }

    public function Today()
    {
        $obj = new StdClass;
        $obj->dtagendamento = date('d/m/Y');

        TSession::setValue('TerapiasAgendamentos_filter_dtagendamento', $obj->dtagendamento);

        $this->form->setData($obj);

        AdiantiCoreApplication::loadPage('HHTerapiasAgendamentosList', 'onReload');
    }

    public function onInputDialog($param)
    {
        $form = new BootstrapFormBuilder('input_form');
        $form->style = 'padding:20px';

        $terapia_id = new TCombo('terapia_id');

        $form->addFields( [ new TLabel(_t('Therapy')) ], [ $terapia_id ] );

        TTransaction::open(TSession::getValue('unit_database'));
        $conn = TTransaction::get();

        $dtagendamento = TDate::date2us(TSession::getValue('TerapiasAgendamentos_filter_dtagendamento'));
        $diasemana = date('N', strtotime($dtagendamento));

        $query = "SELECT distinct t.id as id
                FROM terapias_diasdasemana td
                JOIN terapias t on t.id = td.terapia_id
                WHERE td.diasemana_id = $diasemana
                ORDER BY t.nome";

        $results = $conn->query($query);

        if ($results)
        {
            $array_terapias = [];
            foreach ($results as $result)
            {
                $terapia = new Terapias($result['id']);
                $array_terapias[$terapia->id] = $terapia->nome;
            }
            $terapia_id->addItems($array_terapias);
        }

        TTransaction::close();

        $form->addAction( _t('Confirm'), new TAction( [ $this, 'onPrint' ] ), 'fa:check-circle green' );

        new TInputDialog('Imprimir', $form);
    }

    public function Print($terapia_id, $output, $paper, $orientation, $title)
    {
        if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
        {
            $filter = "<b>Filtro Utilizado:</b> " . TSession::getValue('TerapiasAgendamentos_filter_dtagendamento');

            // string with HTML contents
            $html = !empty($terapia_id) ? clone $this->terapias[$terapia_id] : clone $this->html;
            $contents = file_get_contents('app/resources/styles-print.html') .
                        "<table width=\"100%\">" .
                        "<tr>" .
                        "<td width=\"80%\"><span class=\"systemunit\">" .
                        "<img src=\"" . TSession::getValue('userunitlogo') . "\" width=\"100\"/>" .
                        "</span></td>" . 
                        "<td width=\"20%\" style=\"text-align: right;\">" . _t("Date") . ": " . date("d/m/Y") . "<br>" . _t("Hour") . ": " . date("H:i:s") . "</td>" .
                        "</tr>" .
                        "<tr>" .
                        "<td colspam=\"2\" width=\"80%\"><span class=\"title\"><b>$title</b></span></td>" .
                        "</tr>" .
                        "<tr>" .
                        "<td colspam=\"2\" width=\"80%\"><span class=\"filter\">$filter</span></td>" .
                        "</tr>" .
                        "</table>" .
                        "<div class=\"footer\">" .
                        "<hr>" .
                        "<span class=\"pull-right page-number\">" . _t("Page") . " </span>" .
                        "</div>" . 
                        $html->getContents();

            $options = new \Dompdf\Options();
            $options-> setChroot (getcwd());
            
            // converts the HTML template into PDF
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf-> loadHtml ($contents);
            $dompdf-> setPaper ('A4', 'portrait');
            $dompdf-> render ();

            // write and open file
            file_put_contents($output, $dompdf->output());
        }
        else
        {
            throw new Exception(_t('Permission denied') . ': ' . $output);
        }
    }
    
    public function onPrint($param)
    {
        try
        {
            $terapia_id = $param['terapia_id'];
            
            $class = 'TerapiasAgendamentosList';
            $title = _t('Listing of') . ' ' . _t('Schedules');
            $paper = 'A4';
            $orientation = 'portrait';
            
            $output = 'app/output/'.uniqid().'.pdf';
            $this->Print($terapia_id, $output, $paper, $orientation, $class, $title);
            
            $window = TWindow::create('Export', 0.8, 0.8);
            $object = new TElement('object');
            $object->{'data'}  = $output;
            $object->{'type'}  = 'application/pdf';
            $object->{'style'} = "width: 100%; height:calc(100% - 10px)";
            $window->add($object);
            $window->show();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    public function onSetCompareceu($param)
    {
        try
        {
            TTransaction::open(TSession::getValue('unit_database'));
            
            $object = TerapiasAgendamentos::find($param['id']);
            if ($object)
            {
                $object->compareceu = $param['compareceu'];
                $object->store();
            }
            
            TTransaction::close();
            
            $this->onReload();
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    public function displayCompareceu($object)
    {
        if ($object->compareceu == 'N' || $object->compareceu == NULL)
        {
            return TRUE;
        }
    }
    
    public function displayNaoCompareceu($object)
    {
        if ($object->compareceu == 'S' || $object->compareceu == NULL)
        {
            return TRUE;
        }
    }
    
    public function onDelete($param)
    {
        // define the delete action
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }

    public function Delete($param)
    {
        try
        {
            // get the parameter $key
            $key=$param['key'];
            // open a transaction with database
            TTransaction::open(TSession::getValue('unit_database'));

            // instantiates object
            $object = new TerapiasAgendamentos($key, FALSE);

            // deletes the object from the database
            $object->delete();
            
            // close the transaction
            TTransaction::close();

            // reload the listing
            $this->onReload( $param );
            // shows the success message
            new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}