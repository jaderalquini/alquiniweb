<?php
/**
 * ReceitasList Listing
 * @author  Jáder Alexandre Alquini
 */
class ReceitasList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;

    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();

        $ini  = AdiantiApplicationConfig::get();

        $criteria = new TCriteria();
        $criteria->add(new TFilter('tipo', '=', 'E'));

        parent::setDatabase(TSession::getValue('unit_database'));            // defines the database
        parent::setActiveRecord('MovimentosCaixa');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('descricao', 'like', 'descricao'); // filterField, operator, formField
        parent::addFilterField('pessoa_id', '=', 'pessoa_id'); // filterField, operator, formField
        parent::addFilterField('nrdoc', '=', 'nrdoc'); // filterField, operator, formField
        parent::addFilterField('tipo_pagamento_id', '=', 'tipo_pagamento_id'); // filterField, operator, formField
        parent::addFilterField('forma_pagamento_id', '=', 'forma_pagamento_id'); // filterField, operator, formField
        parent::addFilterField('numrecibo', '=', 'numrecibo'); // filterField, operator, formField
        parent::setOrderCommand('pessoa->nome', '(SELECT nome FROM pessoas WHERE movimentos_caixa.pessoa_id = id)');
        parent::setOrderCommand('tipo_pagamento->descricao', '(SELECT descricao FROM tipos_pagamento WHERE movimentos_caixa.tipo_pagamento_id = id)');
        parent::setOrderCommand('forma_pagamento->descricao', '(SELECT descricao FROM formas_pagamento WHERE movimentos_caixa.forma_pagamento_id = id)');
        parent::setLimit(TSession::getValue(__CLASS__ . '_limit') ?? 10);
        parent::setCriteria($criteria);
        parent::setAfterSearchCallback( [$this, 'onAfterSearch' ] );

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_FormasPagamento');
        $this->form->setFormTitle(_t('Payment Methods'));

        // create the form fields
        $id = new TNumeric('id', 0, ',', '.');
        $descricao = new TEntry('descricao');
        $pessoa_id = new TDBCombo('pessoa_id[]', TSession::getValue('unit_database'), 'Pessoas', 'id', 'nome', 'nome');
        $nrdoc = new TNumeric('nrdoc', 0, ',', '.');
        $tipo_pagamento_id = new TDBCombo('tipo_pagamento_id', TSession::getValue('unit_database'), 'TiposPagamento', 'id', 'descricao', 'descricao');
        $forma_pagamento_id = new TDBCombo('forma_pagamento_id', TSession::getValue('unit_database'), 'FormasPagamento', 'id', 'descricao', 'descricao');
        $numrecibo = new TNumeric('numrecibo', 0, ',', '.');
        $liquidado = new TCheckGroup('liquidado');

        // add the fields
        $this->form->addFields( [new TLabel(_t('Id'))], [$id] );
        $this->form->addFields( [new TLabel(_t('Description'))], [$descricao] );
        $this->form->addFields( [new TLabel(_t('Creditor'))], [$pessoa_id] );
        $this->form->addFields( [new TLabel(_t('Document Number'))], [$nrdoc] );
        $this->form->addFields( [new TLabel(_t('Payment Type'))], [$tipo_pagamento_id] );
        $this->form->addFields( [new TLabel(_t('Payment Method'))], [$forma_pagamento_id] );
        $this->form->addFields( [new TLabel(_t('Receipt Number'))], [$numrecibo] );
        $this->form->addFields( [new TLabel(_t('Liquidated'))], [$liquidado] );

        $id->setSize(100);
        $descricao->setSize('100%');
        $pessoa_id->setSize('100%');
        $pessoa_id->enableSearch();
        $nrdoc->setSize(200);
        $tipo_pagamento_id->setSize('100%');
        $forma_pagamento_id->setSize('100%');
        $numrecibo->setSize(200);
        $liquidado->setSize('100%');
        $liquidado->addItems( [ 'S' => _t('Yes'), 'N' => _t('No') ]);
        $liquidado->setLayout('horizontal');
        $liquidado->setUseButton();

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Receitas_filter_data') );

        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear Filter'), new TAction(array($this, 'onClearFilter')), 'fa:ban red');

        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', _t('Id'), 'center', 50);
        $column_descricao = new TDataGridColumn('descricao', _t('Description'), 'left');
        $column_pessoa_id = new TDataGridColumn('{pessoa->nome}', _t('Creditor'), 'left');
        $column_nrdoc = new TDataGridColumn('{nrdoc}', _t('Document Number'), 'left', 100);
        $column_tipo_pagamento_id = new TDataGridColumn('{{tipo_pagamento->descricao}}', _t('Payment Type'), 'left');
        $column_dtvencto = new TDataGridColumn('dtvencto', _t('Duo Date'), 'left', 100);
        $column_parcela = new TDataGridColumn('parcela', _t('Parcel'), 'left', 100);
        $column_valor = new TDataGridColumn('valor', _t('Value'), 'left', 100);
        $column_dtpagto = new TDataGridColumn('dtpagto', _t('Payday'), 'left', 100);
        $column_vlpago = new TDataGridColumn('vlpago', _t('Amount Paid'), 'left', 100);
        $column_forma_pagamento_id = new TDataGridColumn('{forma_pagamento->descricao}', _t('Payment Method'), 'left');
        $column_numrecibo = new TDataGridColumn('numrecibo', _t('Receipt Number'), 'left', 100);
        $column_liquidado = new TDataGridColumn('liquidado', _t('Liquidated'), 'left', 100);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_pessoa_id);
        $this->datagrid->addColumn($column_nrdoc);
        $this->datagrid->addColumn($column_tipo_pagamento_id);
        $this->datagrid->addColumn($column_dtvencto);
        $this->datagrid->addColumn($column_parcela);
        $this->datagrid->addColumn($column_valor);
        $this->datagrid->addColumn($column_dtpagto);
        $this->datagrid->addColumn($column_vlpago);
        $this->datagrid->addColumn($column_forma_pagamento_id);
        $this->datagrid->addColumn($column_numrecibo);
        $this->datagrid->addColumn($column_liquidado);

        $column_id->setTransformer([$this, 'formatRow'] );

        // creates the datagrid actions
        $action1 = new TDataGridAction([$this, 'onSelect'], ['id' => '{id}', 'register_state' => 'false']);
        //$action1->setUseButton(TRUE);
        $action1->setButtonClass('btn btn-default');
        
        // add the actions to the datagrid
        $this->datagrid->addAction($action1, _t('Select'), 'far:square fa-fw black');

        $column_parcela->setTransformer( function($value, $object, $row) {
            return $value . "/" . $object->totparcelas;
        });

        $column_dtvencto->setTransformer( function($value, $object, $row) {
            if ($value)
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
            return $value;
        });

        $column_valor->setTransformer( function($value, $object, $row) {
            return 'R$ ' . number_format($value, 2, ',', '.');
        });

        $column_dtpagto->setTransformer( function($value, $object, $row) {
            if ($value)
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
            return $value;
        });

        $column_vlpago->setTransformer( function($value, $object, $row) {
            return 'R$ ' . number_format($value, 2, ',', '.');
        });

        $column_liquidado->setTransformer( function($value, $object, $row) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_descricao = new TAction(array($this, 'onReload'));
        $order_descricao->setParameter('order', 'descricao');
        $column_descricao->setAction($order_descricao);

        $order_pessoa_id = new TAction(array($this, 'onReload'));
        $order_pessoa_id->setParameter('order', 'pessoa->nome');
        $column_pessoa_id->setAction($order_pessoa_id);

        $order_tipo_pagamento_id = new TAction(array($this, 'onReload'));
        $order_tipo_pagamento_id->setParameter('order', 'tipo_pagamento->descricao');
        $column_tipo_pagamento_id->setAction($order_tipo_pagamento_id);

        $order_dtvencto = new TAction(array($this, 'onReload'));
        $order_dtvencto->setParameter('order', 'dtvencto');
        $column_dtvencto->setAction($order_dtvencto);

        $order_valor = new TAction(array($this, 'onReload'));
        $order_valor->setParameter('order', 'valor');
        $column_valor->setAction($order_valor);

        $order_dtpagto = new TAction(array($this, 'onReload'));
        $order_dtpagto->setParameter('order', 'dtpagto');
        $column_dtpagto->setAction($order_dtpagto);

        $order_vlpago = new TAction(array($this, 'onReload'));
        $order_vlpago->setParameter('order', 'vlpago');
        $column_vlpago->setAction($order_vlpago);

        $order_forma_pagamento_id = new TAction(array($this, 'onReload'));
        $order_forma_pagamento_id->setParameter('order', 'forma_pagamento->descricao');
        $column_forma_pagamento_id->setAction($order_forma_pagamento_id);

        $order_liquidado = new TAction(array($this, 'onReload'));
        $order_liquidado->setParameter('order', 'liquidado');
        $column_liquidado->setAction($order_liquidado);

        // create EDIT action
        $action_edit = new TDataGridAction(array('ReceitasForm', 'onEdit'), ['register_state' => 'false'] );
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('far:edit blue');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('far:trash-alt red');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);

        // create the datagrid model
        $this->datagrid->createModel();

        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup;
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        $panel->addFooter($this->pageNavigation);

        $btnf = TButton::create('find', [$this, 'onSearch'], '', 'fa:search');
        $btnf->style= 'height: 37px; margin-right:4px;';

        $form_search = new TForm('form_search_descricao');
        $form_search->style = 'float:left;display:flex';
        $form_search->add($descricao, true);
        $form_search->add($btnf, true);

        $panel->addHeaderWidget($form_search);

        $panel->addHeaderActionLink(_t('New'), new TAction(['ReceitasForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');
        $panel->addHeaderActionLink( _t('Delete Selected'), new TAction([$this, 'onDeleteSelected']), 'far:trash-alt red');
        $this->filter_label = $panel->addHeaderActionLink('Filtros', new TAction([$this, 'onShowCurtainFilters']), 'fa:filter');
        $panel->addHeaderActionLink( _t('Clear Filter'), new TAction(array($this, 'onClearFilter')), 'fa:ban red');

        // header actions
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->style = 'height:37px';
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table fa-fw blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf fa-fw red' );
        $dropdown->addAction( _t('Save as XML'), new TAction([$this, 'onExportXML'], ['register_state' => 'false', 'static'=>'1']), 'fa:code fa-fw green' );
        $panel->addHeaderWidget( $dropdown );

        // header actions
        $dropdown = new TDropDown( TSession::getValue(__CLASS__ . '_limit') ?? '10', '');
        $dropdown->style = 'height:37px';
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( 10,   new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '10']) );
        $dropdown->addAction( 20,   new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '20']) );
        $dropdown->addAction( 50,   new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '50']) );
        $dropdown->addAction( 100,  new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '100']) );
        $dropdown->addAction( 1000, new TAction([$this, 'onChangeLimit'], ['register_state' => 'false', 'static'=>'1', 'limit' => '1000']) );
        $panel->addHeaderWidget( $dropdown );

        if (TSession::getValue(get_class($this).'_filter_counter') > 0)
        {
            $this->filter_label->class = 'btn btn-primary';
            $this->filter_label->setLabel('Filtros ('. TSession::getValue(get_class($this).'_filter_counter').')');
        }

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        //$container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }

    public function onClearFilter()
    {
        $this->form->clear();
        $this->onSearch();
    }

    /**
     * Save the object reference in session
     */
    public function onSelect($param)
    {
        // get the selected objects from session 
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        $id = $param['id'];
        if (isset($selected_objects[$id]))
        {
            unset($selected_objects[$id]);
        }
        else
        {
            $selected_objects[$id] = $id;
        }
        TSession::setValue(__CLASS__.'_selected_objects', $selected_objects); // put the array back to the session
        
        // reload datagrids
        $this->onReload( func_get_arg(0) );
    }
    
    /**
     * Highlight the selected rows
     */
    public function formatRow($value, $object, $row)
    {
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        if ($selected_objects)
        {
            if (in_array( (int) $value, array_keys( $selected_objects ) ) )
            {
                $row->style = "background: #abdef9";
                
                $button = $row->find('i', ['class'=>'far fa-square fa-fw black'])[0];
                
                if ($button)
                {
                    $button->class = 'far fa-check-square fa-fw black';
                }
            }
        }
        
        return $value;
    }

    public function onDeleteSelected($param)
    {
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        if (!empty($selected_objects))
        {
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), new TAction(array($this, 'deleteSelected')));
        }
    }

    /**
     * Delete selected records
     */
    public function deleteSelected()
    {
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        if ($selected_objects)
        {
            TTransaction::open($this->database);
            foreach ($selected_objects as $id)
            {
                $object = MovimentosCaixa::find($id);
                if ($object)
                {
                    $object->delete();
                }
            }
            TTransaction::close();
            
            new TMessage('info', 'Records deleted');
        }
        TSession::setValue(__CLASS__.'_selected_objects', []);
        $this->onReload();
    }

    public function onAfterSearch($datagrid, $options)
    {
        if (TSession::getValue(get_class($this).'_filter_counter') > 0)
        {
            $this->filter_label->class = 'btn btn-primary';
            $this->filter_label->setLabel('Filtros ('. TSession::getValue(get_class($this).'_filter_counter').')');
        }
        else
        {
            $this->filter_label->class = 'btn btn-default';
            $this->filter_label->setLabel('Filtros');
        }
        
        if (!empty(TSession::getValue(get_class($this).'_filter_data')))
        {
            $obj = new stdClass;
            $obj->descricao = TSession::getValue(get_class($this).'_filter_data')->descricao;
            TForm::sendData('form_search_descricao', $obj);
        }
    }

    public static function onChangeLimit($param)
    {
        TSession::setValue(__CLASS__ . '_limit', $param['limit'] );
        AdiantiCoreApplication::loadPage(__CLASS__, 'onReload');
    }

    public static function onShowCurtainFilters($param = null)
    {
        try
        {
            // create empty page for right panel
            $page = new TPage;
            $page->setTargetContainer('adianti_right_panel');
            $page->setProperty('override', 'true');
            $page->setPageName(__CLASS__);
            
            $btn_close = new TButton('closeCurtain');
            $btn_close->onClick = "Template.closeRightPanel();";
            $btn_close->setLabel("Fechar");
            $btn_close->setImage('fas:times');
            
            // instantiate self class, populate filters in construct 
            $embed = new self;
            $embed->form->addHeaderWidget($btn_close);
            
            // embed form inside curtain
            $page->add($embed->form);
            $page->setIsWrapped(true);
            $page->show();
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
}