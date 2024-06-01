<?php

use Adianti\Core\AdiantiCoreApplication;

/**
 * HHCursosForm Registration
 * @author  <your name here>
 */
class HHCursosForm extends TPage
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
        $this->setActiveRecord('Cursos');     // defines the active record

        // creates the form
        $this->form = new BootstrapFormBuilder('form_HHCursos');
        $this->form->setFormTitle(_t('Courses'));
        $this->form->enableClientValidation();

        // create the form fields
        $id = new TNumeric('id', 0, ',', '.');
        $nome = new TEntry('nome');
        $pessoa_id = new TDBUniqueSearch('pessoa_id[]', TSession::getValue('unit_database'), 'Pessoas', 'id', 'nome', 'nome');
        $diasdasemana = new TDBCheckGroup('diasdasemana', TSession::getValue('unit_database'), 'Diasdasemana', 'id', 'nome', 'id');

        // add the fields
        $this->form->addFields( [ new TLabel(_t('Id')) ], [ $id ], [ new TLabel(_t('Name'), 'red') ], [ $nome ] );
        $this->form->addFields( [ new TLabel(_t('Teacher'), 'red') ], [ $pessoa_id ], [ new TLabel(_t('Days of the Week'), 'red') ], [ $diasdasemana ] );

        // set sizes
        $id->setSize(100);
        $nome->setSize('100%');
        $pessoa_id->setSize('100%');

        $id->setEditable(FALSE);

        $nome->autofocus = 'autofocus';
        $nome->forceUpperCase();

        $nome->addValidation(_t('Name'), new TRequiredValidator);
        $pessoa_id->addValidation(_t('Tecger'), new TRequiredValidator);

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
        //$container->add(new TXMLBreadCrumb('menu.xml', 'HHCursosList'));
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

    public function onSave( $param )
    {
        try
        {
            TTransaction::open(TSession::getValue('unit_database'));

            // get the form data
            $object = $this->form->getData($this->activeRecord);

            $data = $this->form->getData();
            // validate data
            $this->form->validate();

            // stores the object
            $object->store();
            
            $object->clearParts();

            if( !empty($data->diasdasemana) )
            {
                foreach( $param['diasdasemana'] as $diasemana )
                {
                    $object->addDiadaSemana( new Diasdasemana($diasemana) );
                }

                $object->store();
            }

            // fill the form with the active record data
            $this->form->setData($object);

            // close the transaction
            TTransaction::close();

            $this->afterSaveAction = new TAction(['HHCursosList', 'onReload']);

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
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];

                TTransaction::open(TSession::getValue('unit_database'));

                $object = new Cursos($key);

                $items = CursosDiasdasemana::where('curso_id', '=', $key)->load();
                if ($items)
                {
                    $diasdasemana = [];
                    foreach ($items as $item)
                    {
                        $diasdasemana[] = $item->diasemana_id;
                    }
                    $object->diasdasemana = $diasdasemana;
                }

                $this->form->setData($object);

                TTransaction::close();
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
}