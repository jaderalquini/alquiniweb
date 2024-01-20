<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

/**
 * SystemRequestPasswordResetForm
 *
 * @version    7.6
 * @package    control
 * @subpackage admin
 * @author     Jáder Alexandre Alquini
 * @copyright  Copyright (c) 2024 AlquiniWEB. (http://web.alquini.com.br)
 * @license    
 */
class AWSystemRequestPasswordResetForm extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct($param)
    {
        parent::__construct();
        
        $this->style = 'clear:both';
        // creates the form
        $this->form = new BootstrapFormBuilder('form_login');
        $this->form->setFormTitle( _t('Reset password') );
        
        // create the form fields
        $login = new TEntry('login');
        
        // define the sizes
        $login->setSize('70%', 40);
        
        $login->style = 'height:35px; font-size:14px;float:left;border-bottom-left-radius: 0;border-top-left-radius: 0;';
        $login->placeholder = _t('Email');
        $user = '<span style="float:left;margin-left:44px;height:35px;" class="login-avatar"><span class="fa fa-envelope"></span></span>';
        $this->form->addFields( [$user, $login] );
        $login->autofocus = 'autofocus';

        $br = new TElement('br');

        $this->form->addContent( [$br] );

        $btn = new TButton('send');
        $btn->setAction(new TAction(array($this, 'onRequest')));
        $btn->setLabel(_t('Send'));
        $btn->class = 'btn btn-primary';
        $btn->style = 'height: 40px; width: 100%; display: block; margin: auto;font-size:17px;';

        $this->form->addFields( [$btn] );
        
        $this->form->addContent( [$br] );

        $div = new TElement('div');
        $div->style = 'width: 100%; text-align: center;';
        
        $link = new TElement('a');
        $link->href = 'index.php?class=AWLoginForm';
        $link->add('Voltar');
        
        $div->add($link);
        
        $this->form->addContent( [$div] );
        
        $wrapper = new TElement('div');
        $wrapper->style = 'margin:auto; max-width:460px;';
        $wrapper->id    = 'login-wrapper';
        $nav = new TElement('nav');
        $nav->class = 'main-header navbar';
        $nav->role = 'navigation';
        $nav->style = 'margin-bottom: 0; text-align:center';
        $wrapper->add($nav);
        $a = new TElement('a');
        $a->class = 'navbar-brand';
        $a->href = 'http://web.alquini.com.br';
        $nav->add($a);
        $img = new TElement('img');
        $img->src = 'app/templates/alquiniweb1/img/logo.png';
        $img->width = '350';
        $a->add($img);
        $wrapper->add($this->form);
        
        // add the form to the page
        parent::add($wrapper);
    }
    

    /**
     * Authenticate the User
     */
    public static function onRequest($param)
    {
        $ini = AdiantiApplicationConfig::get();
        
        try
        {
            if ($ini['permission']['reset_password'] !== '1')
            {
                throw new Exception( _t('The password reset is disabled') );
            }
            
            if (empty($ini['general']['seed']) OR $ini['general']['seed'] == 's8dkld83kf73kf094')
            {
                throw new Exception(_t('A new seed is required in the application.ini for security reasons'));
            }
            
            TTransaction::open('permission');
            
            $login = $param['login'];
            $user  = SystemUser::newFromLogin($login);
            
            if ($user instanceof SystemUser)
            {
                if ($user->active == 'N')
                {
                    throw new Exception(_t('Inactive user'));
                }
                else
                {
                    $key = APPLICATION_NAME . $ini['general']['seed'];
                    
                    $token = array(
                        "user" => $user->login,
                        "expires" => strtotime("+ 3 hours")
                    );
                    
                    $jwt = JWT::encode($token, $key, 'HS256');
                    
                    $referer = $_SERVER['HTTP_REFERER'];
                    $url = substr($referer, 0, strpos($referer, 'index.php'));
                    $url .= 'index.php?class=SystemPasswordResetForm&method=onLoad&jwt='.$jwt;
                    
                    $replaces = [];
                    $replaces['name'] = $user->name;
                    $replaces['link'] = $url;
                    $html = new THtmlRenderer('app/resources/system_reset_password.html');
                    $html->enableSection('main', $replaces);
                    
                    MailService::send( $user->email, _t('Password reset'), $html->getContents(), 'html' );
                    new TMessage('info', _t('Message sent successfully'));
                }
            }
            else
            {
                throw new Exception(_t('User not found'));
            }
        }
        catch (Exception $e)
        {
            new TMessage('error',$e->getMessage());
            TTransaction::rollback();
        }
    }
}
