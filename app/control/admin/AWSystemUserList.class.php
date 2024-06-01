<?php
/**
 * AWSystemUserList
 *
 * @version    7.6
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class AWSystemUserList extends TStandardList
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
        
        parent::setDatabase('permission');            // defines the database
        parent::setActiveRecord('SystemUser');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('name', 'like', 'name'); // filterField, operator, formField
        parent::addFilterField('email', 'like', 'email'); // filterField, operator, formField
        parent::addFilterField('active', '=', 'active'); // filterField, operator, formField
        parent::setLimit(TSession::getValue(__CLASS__ . '_limit') ?? 10);
        $criteria = new TCriteria;

        TTransaction::open('permission');
        $system_users_units = SystemUserUnit::where('system_unit_id', 'in', TSession::getValue('userunitids'))->load();
        
        if ($system_users_units)
        {
            $users = [];
            foreach ($system_users_units as $system_user_unit)
            {
                $users[] = $system_user_unit->system_user_id;
            }
            $criteria->add(new TFilter('id', 'in', $users));
            
            foreach ($users as $user)
            {
                $user = SystemUser::find($user);
                $usergroupids = $user->getSystemUserGroupIds();
                
                if (in_array(1, $usergroupids))
                {
                    $criteria->add(new TFilter('id', '<>', $user->id));
                }
            }
        }

        TTransaction::close();

        $this->setCriteria($criteria); // define a standard filter

        parent::setAfterSearchCallback( [$this, 'onAfterSearch' ] );
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_SystemUser');
        $this->form->setFormTitle(_t('Users'));
        
        // create the form fields
        $id = new TEntry('id');
        $name = new TEntry('name');
        $email = new TEntry('email');
        $active = new TCombo('active');
        
        $active->addItems( [ 'Y' => _t('Yes'), 'N' => _t('No') ] );
        
        // add the fields
        $this->form->addFields( [new TLabel(_t('Id'))], [$id] );
        $this->form->addFields( [new TLabel(_t('Name'))], [$name] );
        $this->form->addFields( [new TLabel(_t('Email'))], [$email] );
        $this->form->addFields( [new TLabel(_t('Active'))], [$active] );
        
        $id->setSize('30%');
        $name->setSize('100%');
        $email->setSize('100%');
        $active->setSize('100%');
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('SystemUser_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', _t('Id'), 'center', 50);
        $column_name = new TDataGridColumn('name', _t('Name'), 'left');
        $column_login = new TDataGridColumn('login', _t('Login'), 'left');
        $column_email = new TDataGridColumn('email', _t('Email'), 'left');
        $column_active = new TDataGridColumn('active', _t('Active'), 'center');
        $column_2fa = new TDataGridColumn('otp_secret', '2FA', 'center');
        $column_term_policy = new TDataGridColumn('accepted_term_policy', _t('Terms of use and privacy policy'), 'center');
        
        $column_login->enableAutoHide(500);
        $column_email->enableAutoHide(500);
        $column_active->enableAutoHide(500);
        $column_term_policy->enableAutoHide(500);
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_name);
        $this->datagrid->addColumn($column_login);
        $this->datagrid->addColumn($column_email);
        $this->datagrid->addColumn($column_2fa);
        $this->datagrid->addColumn($column_active);
        
        if (!empty($ini['general']['require_terms']) && $ini['general']['require_terms'] == '1')
        {
            $this->datagrid->addColumn($column_term_policy);
        }

        $column_id->setTransformer([$this, 'formatRow']);
        
        $column_2fa->setTransformer( function($value, $object, $row) {
            $class = (empty($value)) ? 'danger' : 'success';
            $label = (empty($value)) ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:10pt;";
            $div->add($label);
            return $div;
        });
        
        $column_active->setTransformer( function($value, $object, $row) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:10pt;";
            $div->add($label);
            return $div;
        });
        
        $column_term_policy->setTransformer( function($value, $object, $row, $cell) {
            $cell->href = '#';
            $class = (empty($value) || $value=='N') ? 'danger' : 'success';
            $label = (empty($value) || $value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);

            if ($value == 'Y')
            {
                $contents = [];
                $contents[] = TElement::tag('b',  _t('Date') . ':' ) . TElement::tag('p', TDateTime::convertToMask($object->accepted_term_policy_at, 'yyyy-mm-dd hh:ii:ss', 'dd/mm/yyyy hh:ii'));

                if ($object->accepted_term_policy_data)
                {
                    $data = json_decode($object->accepted_term_policy_data, true);

                    foreach($data as $key => $value)
                    {
                        $contents[] = TElement::tag('b', "{$key}:") . TElement::tag('p', $value);
                    }
                }
                $content = TElement::tag('div', implode('', $contents), ["style"=>"max-height: 200px;overflow-y:auto;"]);
                $div->{'poptitle'} = _t('Terms of use and privacy policy');
                $div->{'popcontent'} = $content->getContents();
                $div->{'popover'} = "true";
                $div->{'poptrigger'} = "click";
                $div->{'popside'} = "left";

                $div = TElement::tag('div', $div, ['title' => _t('Click here for more information')]);
            }

            return $div;
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
        
        $order_name = new TAction(array($this, 'onReload'));
        $order_name->setParameter('order', 'name');
        $column_name->setAction($order_name);
        
        $order_login = new TAction(array($this, 'onReload'));
        $order_login->setParameter('order', 'login');
        $column_login->setAction($order_login);
        
        $order_email = new TAction(array($this, 'onReload'));
        $order_email->setParameter('order', 'email');
        $column_email->setAction($order_email);
        
        // create EDIT action
        $action_edit = new TDataGridAction(array('AWSystemUserForm', 'onEdit'), ['register_state' => 'false'] );
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
        
        // create CLONE action
        $action_clone = new TDataGridAction(array($this, 'onClone'));
        $action_clone->setButtonClass('btn btn-default');
        $action_clone->setLabel(_t('Clone'));
        $action_clone->setImage('far:clone green');
        $action_clone->setField('id');
        $this->datagrid->addAction($action_clone);
        
        // create ONOFF action
        $action_onoff = new TDataGridAction(array($this, 'onTurnOnOff'));
        $action_onoff->setButtonClass('btn btn-default');
        $action_onoff->setLabel(_t('Activate/Deactivate'));
        $action_onoff->setImage('fa:power-off orange');
        $action_onoff->setField('id');
        $this->datagrid->addAction($action_onoff);
        
        // create ONOFF action
        $action_person = new TDataGridAction(array($this, 'onImpersonation'));
        $action_person->setButtonClass('btn btn-default');
        $action_person->setLabel(_t('Impersonation'));
        $action_person->setImage('far:user-circle gray');
        $action_person->setFields(['id','login']);
        $this->datagrid->addAction($action_person);
        
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
        
        $form_search = new TForm('form_search_name');
        $form_search->style = 'float:left;display:flex';
        $form_search->add($name, true);
        $form_search->add($btnf, true);
        
        $panel->addHeaderWidget($form_search);
        
        $panel->addHeaderActionLink(_t('New'), new TAction(['AWSystemUserForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');
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
            TTransaction::open('permission');
            foreach ($selected_objects as $id)
            {
                $object = SystemUser::find($id);
                if ($object)
                {
                    $object->delete();
                }
            }
            TTransaction::close();

            new TMessage('info', AdiantiCoreTranslator::translate('Record deleted')); // success message
        }
        TSession::setValue(__CLASS__.'_selected_objects', []);
        $this->onReload();
    }
    
    /**
     *
     */
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
            $obj->name = TSession::getValue(get_class($this).'_filter_data')->name;
            TForm::sendData('form_search_name', $obj);
        }
    }
    
    /**
     *
     */
    public static function onChangeLimit($param)
    {
        TSession::setValue(__CLASS__ . '_limit', $param['limit'] );
        AdiantiCoreApplication::loadPage(__CLASS__, 'onReload');
    }
    
    /**
     *
     */
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
    
    /**
     * Turn on/off an user
     */
    public function onTurnOnOff($param)
    {
        try
        {
            TTransaction::open('permission');
            $user = SystemUser::find($param['id']);
            if ($user instanceof SystemUser)
            {
                $user->active = $user->active == 'Y' ? 'N' : 'Y';
                $user->store();
            }
            
            TTransaction::close();
            
            $this->onReload($param);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Clone group
     */
    public function onClone($param)
    {
        try
        {
            TTransaction::open('permission');
            $user = new SystemUser($param['id']);
            $user->cloneUser();
            TTransaction::close();
            
            $this->onReload();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Impersonation user
     */
    public function onImpersonation($param)
    {
        try
        {
            $login_impersonated = TSession::getValue('login');

            TTransaction::open('permission');
            TSession::regenerate();
            $user = SystemUser::validate( $param['login'] );
            ApplicationAuthenticationService::loadSessionVars($user);
            SystemAccessLogService::registerLogin(true, $login_impersonated);
            AdiantiCoreApplication::gotoPage('EmptyPage');
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
