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
 * Controlador para listar, crear, editar y eliminar roles.
 * 
* @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
 * @author Henry Stivens Adarme Muñoz <henry.stivens@gmail.com>
 */
//Carga de modelos necesarios
Load::model('seguridad/usuario');

class UsuarioController extends AppController {

    /**
     * Variable para modificar el titulo.
     * @var type 
     */
    public $titulo = 'Usuarios';

    /**
     * Lista de forma paginada a los usuarios.
     * @param int $page Número de página a visualizar
     */
    public function index($page=1) {
        $this->results = Load::model('usuario')->paginate("page: $page", 'order: id desc');
    }

    /**
     * Crear un nuevo usuario.
     * @return View 
     */
    public function crear() {
        if (Input::hasPost('usuario')) {

            $obj = Load::model('usuario');
            //En caso que falle la operación de guardar
            if (!$obj->save(Input::post('usuario'))) {
                Flash::error('Falló operación');
                //se hacen persistente los datos en el formulario
                $this->usuario = $obj;
                return false;
            }
            return Router::redirect();
        }
        // Solo es necesario para el autoForm
        $this->usuario = Load::model('usuario');
    }

    /**
     * Edita un usuario, todos los datos excepto su clave.
     * @param int $id
     * @return View 
     */
    public function editar($id) {

        //se verifica si se ha enviado via POST los datos
        if (Input::hasPost('usuario')) {
            $obj = Load::model('usuario');
            if (!$obj->update(Input::post('usuario'))) {
                Flash::error('Falló operación');
                //se hacen persistente los datos en el formulario
                $this->usuario = Input::post('usuario');
            } else {
                return Router::redirect();
            }
        }

        //Aplicando la autocarga de objeto, para comenzar la edición
        $this->usuario = Load::model('usuario')->find((int) $id);
    }

    /**
     * Muestra la informaición de un usuario
     * @param int $id 
     */
    public function ver($id) {
        $this->result = Load::model('usuario')->find_first((int) $id);
    }

    /**
     * Cambia la clave de un usuario.
     * @param long $id
     * @return View
     */
    public function cambiar_clave($id = null) {
        if ($id) {
            if (Input::hasPost('usuario')) {
                try {
                    $data = Input::post('usuario');

                    if (Load::model('usuario')->cambiar_clave($id, $data['clave'], $data['clave2'])) {
                        Flash::success('Cambio de clave realizado exitosamente.');
                        return Router::route_to('action: index');
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
            Flash::warning('No es un usuario válido.');
            return Router::redirect();
        }
    }
}