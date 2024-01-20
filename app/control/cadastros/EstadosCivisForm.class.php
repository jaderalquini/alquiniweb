<?php
/**
 * EstadosCivisForm Registration
 * @author  <your name here>
 */
class EstadosCivisForm extends TPage
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
        $this->setActiveRecord('EstadosCivis');     // defines the active record
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_EstadosCivis');
        $this->form->setFormTitle(_t('Civil Status'));
        $this->form->enableClientValidation();
        
        // create the form fields
        $id = new TNumeric('id', 0, ',', '.');
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
        $this->form->addAction(_t('New'),  new TAction([$this, 'onEdit']), 'fa:plus green');
        $this->form->addAction(_t('Close'),new TAction(array($this,'onClose')),'fa:times red');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        //$container->add(new TXMLBreadCrumb('menu.xml', 'EstadosCivisList'));
        $container->add($this->form);
        
        parent::add($container);
    }

    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
