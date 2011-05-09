<?php

/**
 * Backend - KumbiaPHP Backend
 * PHP version 5
 * LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * ERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Controlador para solicitar cambio de clave y cambiar la clave del usuario.
 *
 * @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
 * @author Henry Stivens Adarme Muñoz <henry.stivens@gmail.com>
 */
Load::models('seguridad/usuario');

class UsuarioController extends AppController {

    public function before_filter() {

    }

    public function ver($nick) {        
        $usuario = new Usuario();        
        if(!$this->usuario = $usuario->findByNick($nick)){
            Flash::warning('Este usuario no existe.');
            Router::redirect('/');
        }
        $this->title = 'Perfil de ' . $this->usuario->nombre;
    }

    public function recordar_clave() {
        $this->title = 'Recordar clave';

        if (Input::hasPost('email_or_username')) {
            try {
                $email_or_username = Input::post('email_or_username');
                $usuario = new Usuario();
                if ($usuario->resetClaveByEmailOrUsername($email_or_username)) {
                    Flash::success('Se ha enviado un correo para confirmar el cambio de clave.');
                    Input::delete();
                } else {
                    Flash::error('Oops ha ocurrido un error.');
                    Input::delete();
                }
            } catch (KumbiaException $kex) {
                Input::delete();
                Flash::warning("Lo sentimos ha ocurrido un error:");
                Flash::error($kex->getMessage());
            }
        }
    }
    
    public function reportar_activacion() {
        $this->title = 'Reportar activación';

        if (Input::hasPost('email_or_username')) {
            try {
                $email_or_username = Input::post('email_or_username');
                $usuario = new Usuario();
                if ($usuario->reenviarActivacion($email_or_username)) {
                    Flash::success('En un momento recibira un correo de confirmación para activar su cuenta.');                                        
                    return Router::redirect("usuario/ver/$usuario->nick/");
                } else {
                    Flash::error('Oops ha ocurrido un error.');
                    Input::delete();
                }
            } catch (KumbiaException $kex) {
                Input::delete();
                Flash::warning("Lo sentimos ha ocurrido un error:");
                Flash::error($kex->getMessage());
            }
        }
    }

    public function cambiar_clave($email, $reset_clave) {
        $this->title = 'Cambiar clave del usuario';

        $usuario = new Usuario();
        $usuario = $usuario->findByEmail($email);
        $this->id = $usuario->id;

        if ($usuario->reset == $reset_clave) {
            if (Input::hasPost('usuario')) {
                try {
                    $data = Input::post('usuario');
                    if (Load::model('usuario')->cambiar_clave($data['id'], $data['clave'], $data['clave2'])) {
                        Flash::success('Cambio de clave realizado exitosamente.');
                        return Router::redirect('/');
                    } else {
                        Input::delete();
                    }
                } catch (KumbiaException $kex) {
                    Input::delete();
                    Flash::warning("Lo sentimos ha ocurrido un error:");
                    Flash::error($kex->getMessage());
                }
            }
        } else {
            Flash::error('La clave para reseteo es incorrecta o ya fue usado.');
            return Router::redirect('usuario/recordar_clave/');
        }
    }

    public function cambiar_clave2($nick) {
        View::select('cambiar_clave');
        $this->title = 'Cambiar clave del usuario';

        $usuario = new Usuario();
        $usuario = $usuario->findByNick($nick);

        if($usuario->id != Auth::get('id')){
            Flash::warning('Uste no puede editar la información de este usuario');
            return Router::redirect("usuario/ver/$usuario->nick/");
        }
        $this->id = $usuario->id;
        if (Input::hasPost('usuario')) {
            try {
                $data = Input::post('usuario');
                if (Load::model('usuario')->cambiar_clave($data['id'], $data['clave'], $data['clave2'])) {
                    Flash::success('Cambio de clave realizado exitosamente.');
                    return Router::redirect('/');
                } else {
                    Input::delete();
                }
            } catch (KumbiaException $kex) {
                Input::delete();
                Flash::warning("Lo sentimos ha ocurrido un error:");
                Flash::error($kex->getMessage());
            }
        }
        
    }

    /**
     * Crear un nuevo usuario.
     * @return View
     */
    public function registro() {
        
        if(Auth::is_valid()){
            return Router::redirect('/');
        }
        
        $this->title = 'Formulario de registro';

        if (Input::hasPost('usuario')) {
            $obj = Load::model('usuario');            
            if ($obj->save(Input::post('usuario'))) {
                Flash::success('En un momento recibira un correo de confirmación para activar su cuenta.');
                return Router::redirect("usuario/ver/$obj->nick/");
            }else{
                Flash::error('Falló operación');                
                $this->usuario = $obj;
                $this->usuario->clave = '';
                return false;
            }
        }        
    }

    /**
     * Edita un usuario.
     * @return View
     */
    public function editar($nick) {       

        $usuario = new Usuario();
        $this->usuario = $usuario->findByNick($nick);

        if($this->usuario->id != Auth::get('id')){
            Flash::warning('Usted no puede editar la información de este usuario');
            return Router::redirect("usuario/ver/$usuario->nick/");
        }
        
        $this->title = 'Editar perfil de ' . $this->usuario->nombre;

        if (Input::hasPost('usuario')) {
            $data = Input::post('usuario');
            if ($usuario->editar($data['id'],$data['nombre'],$data['nick'],$data['web'],$data['bio'])) {
                Flash::success('Información actualizada.');
                return Router::redirect("usuario/ver/{$data['nick']}/");
            }else{
                Flash::error('Falló operación');                
                $this->usuario = $data;
                return false;
            }
        }
    }

    public function activar($id, $reset_clave) {
        $this->title = 'Activar cuenta';
        $usuario = new Usuario();
        $usuario = $usuario->find($id);

        if ($usuario->reset == $reset_clave) {
            $usuario->activo = 'si';
            $usuario->update();
            Flash::success('Ha activado su cuenta correctamente. Ahora puede iniciar sesion:');
            return Router::redirect('usuario/login/');
        } else {
            Flash::error('La clave para activar la cuenta es incorrecta o ya fue usado.');
            return Router::redirect("usuario/ver/$usuario->nick/");
        }
    }
    
    public function login() {
        $this->title = 'Iniciar sesión';
        Load::lib('SdAuth');
        if (!SdAuth::isLogged()) {
            if(Input::hasPost('txt_login')){
                Flash::warning(SdAuth::getError());
            }
            Input::delete('txt_password');
            return FALSE;
        }else{
            return Router::redirect('/');
        }
    }
}