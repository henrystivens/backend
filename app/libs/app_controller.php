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

    public $title = 'Backen de ejemplo';        

    final protected function initialize() {       
        
        $recurso = '';
        
        if ($this->module_name == 'admin') {
            
            View::template('admin');
            
            if ($this->module_name) {
                $recurso = "$this->module_name/$this->controller_name/$this->action_name/";
            } else {
                $recurso = "$this->controller_name/$this->action_name/";
            }
            
            if(Auth::get('rol_id') == 5){
                Flash::warning("No tienes permiso para acceder.");
                View::select(null, '401');
                return FALSE;
            }
            
            Load::lib('SdAuth');
            if (!SdAuth::isLogged()) {                
                $this->error_msj = SdAuth::getError();
                View::template('login');
                return FALSE;
            }
            
            $ku_acl = new KuAcl();
            $ku_acl->cargarPermisos(Auth::get('id'));

            if (!$ku_acl->check($recurso, Auth::get('id'))) {
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
}