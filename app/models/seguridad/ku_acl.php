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
 * Clase utilizada para cargar y verificar los permisos que tiene un usuario.
 * 
 * @package Seguridad
 * @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
 * @author Henry Stivens Adarme Muñoz <henry.stivens@gmail.com>
 */
//Carga de modelos necesarios
Load::models('seguridad/usuario', 'seguridad/rol', 'seguridad/permiso', 'seguridad/recurso');
//Carga la libreria ACL2
Load::coreLib('acl2');

class KuAcl {

    protected $adapter;

    /**
     * Carga los roles, rescursos, el usuario y permisos de la base de datos.
     */
    public function cargarPermisos($usuario_id) {

        $this->adapter = Acl2::factory();
        $rol = new Rol();
        $roles = $rol->find();

        foreach ($roles as $value) {
            $permiso = new Permiso();
            $roles_recursos = $permiso->find("conditions: rol_id=$value->id");
            $resources = array();

            foreach ($roles_recursos as $value2) {
                $resources[] = $value2->getRecurso()->url;
            }
            //Establece a que recursos tiene acceso un rol.
            $this->adapter->allow($value->nombre, $resources);
        }

        //Consulta el usuario
        $usuario = new Usuario();
        $usuario1 = $usuario->find($usuario_id);

        $this->adapter->user($usuario1->id, array($usuario1->getRol()->nombre));
        
    }

    /**
     * Verifica que un usuario tenga acceso al recurso determinado.
     * 
     * @param String $resource
     * @param String $user
     * @return boolean 
     */
    public function check($resource, $user) {
        return $this->adapter->check($resource, $user);
    }

}

?>