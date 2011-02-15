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
 * Controlador para listar, crear, editar y eliminar accesos.
 * 
 * @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
 * @author Henry Stivens Adarme Muñoz <henry.stivens@gmail.com>
 */
//Carga de modelos necesarios
Load::models('seguridad/permiso','seguridad/rol','seguridad/recurso','seguridad/menu');

class PermisoController extends AppController {
    
    /**
     * Variable para modificar el titulo.
     * @var string 
     */
    public $titulo = 'Accesos';

    public function index() {
        $rol = new Rol();
        $this->roles = $rol->find();
    }

    /**
     * Crea un Registro
     * @return Object Permiso  
     */
    public function crear() {
        if (Input::hasPost('permiso')) {

            $obj = Load::model('permiso');
            //En caso que falle la operación de guardar
            if (!$obj->save(Input::post('permiso'))) {
                Flash::error('Falló operación');
                //se hacen persistente los datos en el formulario
                $this->permiso = $obj;
                return;
            }
            return Router::redirect();
        }
        // Solo es necesario para el autoForm
        $this->permiso = Load::model('permiso');
    }

    /**
     * Edita un acceso.
     * @param int $id
     * @return Object Permiso 
     */
    public function editar($id) {

        //se verifica si se ha enviado via POST los datos
        if (Input::hasPost('permiso')) {
            $obj = Load::model('permiso');
            if (!$obj->update(Input::post('permiso'))) {
                Flash::error('Falló operación');
                //se hacen persistente los datos en el formulario
                $this->permiso = Input::post('permiso');
            } else {
                return Router::redirect();
            }
        }

        //Aplicando la autocarga de objeto, para comenzar la edición
        $this->permiso = Load::model('permiso')->find((int) $id);
    }

    /**
     * Elimina un acceso.
     * @param int $id
     */
    public function borrar($id) {
        if (!Load::model('permiso')->delete((int) $id)) {
            Flash::error('Falló Operación');
        }        
        Router::redirect();
    }

}