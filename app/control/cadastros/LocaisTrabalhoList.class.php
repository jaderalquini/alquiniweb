<?php
/**
 * LocaisTrabalhoList Listing
 * @author  <your name here>
 */
class LocaisTrabalhoList extends TStandardList
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

        $this->setDatabase(TSession::getValue('unit_database'));            // defines the database
        $this->setActiveRecord('LocaisTrabalho');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        parent::setLimit(TSession::getValue(__CLASS__ . '_limit') ?? 10);

        parent::setAfterSearchCallback( [$this, 'onAfterSearch' ] );

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_LocaisTrabalho');
        $this->form->setFormTitle(_t('Civil Status'));

        $id = new TNumeric('id', 0, ',', '.');
        $nome = new TEntry('nome');

        // add the fields
        $this->form->addFields( [new TLabel(_t('Id'))], [$id] );
        $this->form->addFields( [new TLabel(_t('Name'))], [$nome] );

        $id->setSize(100);
        $nome->setSize('100%');

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('LocaisTrabalho_filter_data') );

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
        $column_id = new TDataGridColumn('id', _t('Id'), 'right', 100);
        $column_nome = new TDataGridColumn('nome', _t('Name'), 'left');
        $column_fone = new TDataGridColumn('fone', _t('Phone'), 'left');
        $column_endereco = new TDataGridColumn('endereco', _t('Address'), 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_fone);
        $this->datagrid->addColumn($column_endereco);

        $column_id->setTransformer([$this, 'formatRow']);

        $column_endereco->setTransformer( function ($value, $object) {
            if ($object->rua)
            {

                $endereco = $object->rua;

                if (!empty($object->numero))
                {
                    $endereco .= ', ' . $object->numero;
                }

                if (!empty($object->bairro))
                {
                    $endereco .= ' - ' . $object->bairro;
                }

                if (!empty($object->cidade))
                {
                    $endereco .= '<br>' . $object->cidade;
                }

                if (!empty($object->estado))
                {
                    $endereco .= ' - ' . $object->estado;
                }

                if (!empty($object->cep))
                {
                    $endereco .= '<br>' . $object->cep;   
                }

                $endereco_link = str_replace('<br>', ', ', $endereco);                
                $endereco_link = explode(' ', $endereco_link);
                $endereco_link = 'https://www.google.com/maps/search/' . implode('+', $endereco_link);

                $icon  = "<i class='fa fa-map-marked-alt' aria-hidden='true'></i>";
                return "{$icon} <a generator='adianti' href='index.php?class=PessoasList&method=openURL&url={$endereco_link}&static=1'>$endereco</td></a>";
            }
        });

        // creates the datagrid actions
        $action1 = new TDataGridAction([$this, 'onSelect'], ['id' => '{id}', 'register_state' => 'false']);
        //$action1->setUseButton(TRUE);
        $action1->setButtonClass('btn btn-default');

        // add the actions to the datagrid
        $this->datagrid->addAction($action1, _t('Select'), 'far:square fa-fw black');

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);
        
        // create EDIT action
        $action_edit = new TDataGridAction(array('LocaisTrabalhoForm', 'onEdit'), ['register_state' => 'false'] );
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

        $form_search = new TForm('form_search_nome');
        $form_search->style = 'float:left;display:flex';
        $form_search->add($nome, true);
        $form_search->add($btnf, true);

        $panel->addHeaderWidget($form_search);

        $panel->addHeaderActionLink(_t('New'), new TAction(['LocaisTrabalhoForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');
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
                $object = LocaisTrabalho::find($id);
                if ($object)
                {
                    $object->delete();
                }
            }
            TTransaction::close();
            
            new TMessage('info', _t('Records deleted'));
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
            $obj->nome = TSession::getValue(get_class($this).'_filter_data')->nome;
            TForm::sendData('form_search_nome', $obj);
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
