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
 * @package Seguridad
 * @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
 * @author Henry Stivens Adarme Muñoz <henry.stivens@gmail.com>
 */
class Permiso extends ActiveRecord {

    public function initialize() {
        //Relaciones
        $this->belongs_to('rol');
        $this->belongs_to('recurso');
        $this->belongs_to('menu');
    }

    /**
     * Obtiene el menu de acuerdo al perfil
     *
     * @param int $rol_id
     * @param int $menu     
     * @return resulset
     */
    public function getSubMenu($rol_id=null, $menu=null, $estado='A', $visible=null) {
        if ($visible) {
            return $this->find("rol_id = $rol_id AND estado = '$estado' AND menu_id = $menu AND visible= $visible");
        } else {
            return $this->find("rol_id = $rol_id AND estado = '$estado' AND menu_id = $menu");
        }
    }

    /**
     * Obtiene el Menu
     *
     * @param int $rol_ides
     * @return resulset
     */
    public function getMenuX($rol_id=1) {
        return $this->find("rol_id=$rol_id", 'group: menu_id', 'columns: menu_id');
    }

}

?>
