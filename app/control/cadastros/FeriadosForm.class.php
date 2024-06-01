<?php
/**
 * FeriadosForm Registration
 * @author  <your name here>
 */
class FeriadosForm extends TPage
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

        parent::setTargetContainer('adianti_right_panel');

        $this->setDatabase(TSession::getValue('unit_database'));              // defines the database
        $this->setActiveRecord('Feriados');     // defines the active record

        // creates the form
        $this->form = new BootstrapFormBuilder('form_Feriados');
        $this->form->setFormTitle(_t('Holiday'));
        $this->form->enableClientValidation();

        // create the form fields
        $id = new TNumeric('id', 0, ',', '.');
        $dtferiado = new TDate('dtferiado');
        $descricao = new TEntry('descricao');

        // add the fields
        $this->form->addFields( [ new TLabel(_t('Id')) ], [ $id ], [ new TLabel(_t('Date'), 'red') ], [ $dtferiado ] );
        $this->form->addFields( [ new TLabel(_t('Description'), 'red') ], [ $descricao ], [  ]);

        // set sizes
        $id->setSize(100);
        $dtferiado->setSize(120);
        $descricao->setSize('100%');

        $id->setEditable(FALSE);
        $dtferiado->setMask('dd/mm/yyyy');
        $dtferiado->setDatabaseMask('yyyy-mm-dd');
        
        $dtferiado->autofocus = 'autofocus';
        $descricao->forceUpperCase();

        // validations
        $dtferiado->addValidation(_t('Date'), new TRequiredValidator);
        $descricao->addValidation(_t('Description'), new TRequiredValidator);

        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/

        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');

        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        //$container->add(new TXMLBreadCrumb('menu.xml', 'EstadosCivisList'));
        $container->add($this->form);

        parent::add($container);
    }

    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
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

            $this->afterSaveAction = new TAction(['FeriadosList', 'onReload']);
            
            // shows the success message
            if (isset($this->useMessages) AND $this->useMessages === false)
            {
                AdiantiCoreApplication::loadPageURL( $this->afterSaveAction->serialize() );
            }
            else
            {
                new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $this->afterSaveAction);
            }
            
            return $object;
        }
        catch (Exception $e) // in case of exception
        {
            // get the form data
            $object = $this->form->getData($this->activeRecord);
            
            // fill the form with the active record data
            $this->form->setData($object);
            
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}