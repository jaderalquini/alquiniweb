<?php
/**
 * TiposResidenciaList Listing
 * @author  <your name here>
 */
class TiposResidenciaList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    use Adianti\base\AdiantiStandardListTrait;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase(TSession::getValue('unit_database'));            // defines the database
        $this->setActiveRecord('TiposResidencia');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('descricao', 'like', 'descricao'); // filterField, operator, formField

        $this->form = new TForm('form_search_TiposResidencia');
        
        $id = new TEntry('id');
        $descricao = new TEntry('descricao');

        $id->exitOnEnter();
        $descricao->exitOnEnter();

        $id->setSize('100%');
        $descricao->setSize('100%');

        $id->tabindex = -1;
        $descricao->tabindex = -1;

        $id->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        $descricao->setExitAction( new TAction([$this, 'onSearch'], ['static'=>'1']) );
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', _t('Id'), 'right', 100);
        $column_descricao = new TDataGridColumn('descricao', _t('Description'), 'left');
        
        $column_id->setTransformer([$this, 'formatRow']);

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_descricao);
        
        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_descricao = new TAction(array($this, 'onReload'));
        $order_descricao->setParameter('order', 'descricao');
        $column_descricao->setAction($order_descricao);
        
        $action0 = new TDataGridAction([$this, 'onSelect'], ['id' => '{id}', 'register_state' => 'false']);
        $action1 = new TDataGridAction(['TiposResidenciaForm', 'onEdit'], ['id'=>'{id}']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        
        $action0->setButtonClass('btn btn-default');
        
        $this->datagrid->addAction($action0, _t('Select'), 'far:square fa-fw black');
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
        $this->datagrid->addAction($action2 ,_t('Delete'), 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // add datagrid inside form
        $this->form->add($this->datagrid);
        
        // create row with search inputs
        $tr = new TElement('tr');
        $this->datagrid->prependRow($tr);
        
        $tr->add( TElement::tag('td', ''));
        $tr->add( TElement::tag('td', ''));
        $tr->add( TElement::tag('td', ''));
        $tr->add( TElement::tag('td', $id));
        $tr->add( TElement::tag('td', $descricao));

        $this->form->addField($id);
        $this->form->addField($descricao);

        // keep form filled
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data'));
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup(_t('Residence Types'));
        $panel->add($this->form);
        $panel->addFooter($this->pageNavigation);
        
        // header actions
        $panel->addHeaderActionLink( _t('New'),  new TAction(['TiposResidenciaForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green' );
        $panel->addHeaderActionLink( _T('Clear Filter'), new TAction([$this, 'clearFilters']), 'fa:ban red' );
        $panel->addHeaderActionLink( _t('Delete Selected'), new TAction([$this, 'onDeleteSelected']), 'far:trash-alt red');
        
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table fa-fw blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['title' => _t('Listing of') . ' ' . _t('Residence Types'), 'register_state' => 'false', 'static'=>'1']), 'far:file-pdf fa-fw red' );
        $dropdown->addAction( _t('Save as XML'), new TAction([$this, 'onExportXML'], ['register_state' => 'false', 'static'=>'1']), 'fa:code fa-fw green' );
        $panel->addHeaderWidget( $dropdown );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel);
        
        parent::add($container);
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
                $object = TiposResidencia::find($id);
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
}
