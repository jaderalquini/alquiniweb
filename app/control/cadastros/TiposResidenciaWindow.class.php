<?php
/**
 * TiposResidenciaWindow Registration
 * @author  <your name here>
 */
class TiposResidenciaWindow extends TWindow
{
    protected $form; // form
    
    use Adianti\Base\AdiantiStandardFormTrait; // Standard form methods
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        parent::setTitle('Tipo de Residência: Novo Registro');
        parent::setSize(0.7, null);

        $this->setDatabase(TSession::getValue('unit_database'));              // defines the database
        $this->setActiveRecord('TiposResidencia');     // defines the active record

        // creates the form
        $this->form = new BootstrapFormBuilder('form_TiposResidencia');
        $this->form->enableClientValidation();

        // create the form fields
        $id = new TEntry('id');
        $descricao = new TEntry('descricao');

        // add the fields
        $this->form->addFields( [ new TLabel(_t('Id')) ], [ $id ], [ new TLabel(_t('Description'), 'red') ], [ $descricao ] );

        // set sizes
        $id->setSize(100);
        $descricao->setSize('100%');
        
        $id->setEditable(FALSE); 
        $descricao->autofocus = 'autofocus';       
        $descricao->forceUpperCase();
        
        // validations
        $descricao->addValidation(_t('Description'), new TRequiredValidator);
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        
        $this->setAfterSaveAction(new TAction( [ $this, 'onClose' ] ));
        $this->setUseMessages(TRUE);
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);
        
        parent::add($container);
    }
    
    public function onSave()
    {
        try
        {            
            // open a transaction with database
            TTransaction::open($this->database);
            
            // get the form data
            $object = $this->form->getData($this->activeRecord);
            
            // validate data
            $this->form->validate();
            
            // stores the object
            $object->store();
            
            // fill the form with the active record data
            $this->form->setData($object);
            
            // close the transaction
            TTransaction::close();
            
            TSession::setValue(__CLASS__.'_object', $object);
            
            // shows the success message
            if (isset($this->useMessages) AND $this->useMessages === false)
            {
                AdiantiCoreApplication::loadPageURL( $this->afterSaveAction->serialize() );
            }
            else
            {
                new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $this->afterSaveAction);
            }
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
    
    public function onSetup($param=NULL)
    {
        TSession::setValue(__CLASS__.'_receive_form', $param['receive_form']);
        TSession::setValue(__CLASS__.'_receive_fields', $param['receive_fields']);
        
        $this->onEdit($param);
    }
    
    public function onClose()
    {
        if (!empty(TSession::getValue(__CLASS__.'_receive_form'))&&!empty(TSession::getValue(__CLASS__.'_receive_fields')))
        {
            $receive_form = TSession::getValue(__CLASS__.'_receive_form');
            $receive_fields = TSession::getValue(__CLASS__.'_receive_fields');
            
            $object = TSession::getValue(__CLASS__.'_object');
            $obj = new StdClass;
            
            foreach ($receive_fields as $receive => $send)
            {
                $obj->$receive = $object->$send;
            }
                    
            TForm::sendData($receive_form, $obj);
        }
        
        parent::closeWindow();
    }
}
