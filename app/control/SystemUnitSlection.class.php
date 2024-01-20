<?php
/**
 * SystemUnitSlection
 *
 * @version    1.0
 * @package    control
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemUnitSlection extends TElement
{    
    public function __construct($param)
    {    
        parent::__construct('ul');
        
        try
        {
            TTransaction::open('permission');
            
            $system_unit = new SystemUnit(TSession::getValue('userunitid'));
            
            if ($param['theme'] == 'alquiniweb1')
            {
                $this->class = 'dropdown-menu';
                
                $a = new TElement('a');
                $a->{'class'} = "dropdown-toggle";
                $a->{'data-toggle'}="dropdown";
                $a->{'href'} = "#";
                
                $a->add( TElement::tag('i',    '', array('class'=>"fa fa-building fa-fw")) );
                $a->add( TElement::tag('span', $system_unit->name, array('class'=>"hidden-xs")) );
                $a->show();
                
                // load the units to the logged user
                $system_user_units = SystemUserUnit::where('system_user_id', '=', TSession::getValue('userid'))->where('system_unit_id', '!=', $system_unit->id)->load();
                
                if ($system_user_units)
                {
                    foreach ($system_user_units as $system_user_unit)
                    {
                        $system_unit = new SystemUnit($system_user_unit->system_unit_id);
                        
                        parent::add(TElement::tag('li', TElement::tag('a', $system_unit->name,
                            ['href'=> (new TAction(['AWLoginForm', 'reloadPermissions'], ['static' => '1', 'unit_id' => $system_unit->id]))->serialize(),
                             'generator'=>'adianti'] ), ['class'=>'footer'] ));
                    }
                }
            }
            else if ($param['theme'] == 'alquiniweb2')
            {
                $this->class = 'dropdown-menu';
                
                $a = new TElement('a');
                $a->{'class'} = "dropdown-toggle";
                $a->{'data-toggle'}="dropdown";
                $a->{'href'} = "#";
                
                $a->add( TElement::tag('i',    '', array('class'=>"fa fa-building fa-fw")) );
                $a->add( TElement::tag('span', $system_unit->name, array('class' =>"hidden-xs")) );
                $a->add( TElement::tag('i', '', array('class'=>"fa fa-caret-down")) );
                $a->show();
                
                // load the units to the logged user
                $system_user_units = SystemUserUnit::where('system_user_id', '=', TSession::getValue('userid'))->where('system_unit_id', '!=', $system_unit->id)->load();
                
                if ($system_user_units)
                {
                    foreach ($system_user_units as $system_user_unit)
                    {  
                        $system_unit = new SystemUnit($system_user_unit->system_unit_id);
                        
                        parent::add(TElement::tag('li', TElement::tag('a', $system_unit->name,
                            ['href'=> (new TAction(['AWLoginForm', 'reloadPermissions'], ['static' => '1', 'unit_id' => $system_unit->id]))->serialize(),
                             'generator'=>'adianti'] ), ['class'=>'footer'] ));
                        
                    }
                }
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
