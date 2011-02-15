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

    public function index() {
        View::select('mail_reset');
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

    public function cambiar_clave($email, $reset_clave) {
        $this->title = 'Cambiar clave del usuario';

        $usuario = new Usuario();
        $usuario = $usuario->getUsuarioByEmail($email);
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
            return Router::redirect('usuario/mail_reset/');
        }
    }

}

?>