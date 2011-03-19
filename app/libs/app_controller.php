<?php

/**
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 * */
// @see Controller nuevo controller
require_once CORE_PATH . 'kumbia/controller.php';
Load::models('seguridad/ku_acl');

class AppController extends Controller {

    public $modulo = '';

    final protected function initialize() {

        if ($this->module_name == 'admin') {
            View::template('admin');
            
            Load::lib('SdAuth');
            if (!SdAuth::isLogged()) {                
                $this->error_msj = SdAuth::getError();
                View::template('login');
                return FALSE;
            }        
            
            $ku_acl = new KuAcl();
            $ku_acl->cargarPermisos();

            if ($this->module_name) {
                $recurso = "$this->module_name/$this->controller_name/$this->action_name/";
            } else {
                $recurso = "$this->controller_name/$this->action_name/";
            }

            if (!$ku_acl->check($recurso, 1)) {
                Flash::warning("No tienes permiso para acceder al siguiente recurso: <b>$recurso</b>");
                View::select(null, '401');
                return FALSE;
            }            
        }
    }

    final protected function finalize() {
        
    }
    
    public function logout() {
        Load::lib('SdAuth');
        SdAuth::logout();                
        return Router::redirect('../');
    }
    
    /*public function logout () {
        Load::lib('SdAuth');
        SdAuth::logout();
        //View::template('login2');
        Router::redirect();
    }*/

}
