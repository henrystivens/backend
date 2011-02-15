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
class Usuario extends ActiveRecord {

    public function initialize() {
        //Relaciones
        $this->belongs_to('rol');

        //Validaciones
        $this->validates_presence_of('rol_id', 'message: Por favor seleccione un rol');
        $this->validates_email_in('email', 'message: Campo de correo electrónico incorrecto');

        //Tamaño
        $this->validates_length_of('nick', 40, 5, "too_short: El nick debe tener al menos 5 caracteres", "too_long: El nick debe tener maximo 40 caracteres");
        $this->validates_length_of('nombre', 150, 5, "too_short: El nombre debe tener al menos 5 caracteres", "too_long: El nombre debe tener maximo 150 caracteres");
    }

    public function before_validation_on_create() {
        $this->estado = '1';
    }

    public function before_save() {
        if (!$this->id) {
            if ($this->find_first('nick = "' . $this->nick . '"')) {
                Flash::error('Por favor ingrese otro nick porque este ya existe.');
                return 'cancel';
            }
            if (strlen($this->clave) < 5) {
                Flash::error(' La clave debe tener al menos cinco (5) caracteres');
                return 'cancel';
            }
            $this->clave = sha1($this->clave);
        }
    }

    public function cambiar_clave($usuario_id, $clave, $clave2) {

        if ($clave == $clave2) {
            if (strlen($clave) < 5) {
                Flash::error(' La clave debe tener al menos cinco (5) caracteres');
                return false;
            }
            $usuario = $this->find_first($usuario_id);
            if ($usuario) {
                $usuario->clave = sha1($clave);
                //TODO: Cambio de clave de reseteo
                  $correo = new Correo();
                  $reset_clave = $correo->generarClave(50);
                  $usuario->reset = $reset_clave;
                if ($usuario->update()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                throw new KumbiaException('El usuario no existe');
            }
        } else {
            throw new KumbiaException('Las claves no coinciden');
        }
    }

    public function getUsuarioByEmail($email) {
        return $this->find_first("email = '$email'");
    }

    public function getUsuarioByNick($nick) {
        return $this->find_first("nick = '$nick'");
    }

    public function resetClaveByEmailOrUsername($email_or_username) {
        Load::model('util/misc');
        Load::model('util/correo');
        $usuario = $this->getUsuarioByEmail($email_or_username);        

        if (!$usuario) {
            $usuario = $this->getUsuarioByNick($email_or_username);
        }
        if ($usuario) {
            $reset_clave = Misc::generarClave(33);
            //Para el correo
            $host = Config::get('config.kuportal.site_domain');
            $url = $host . 'usuario/cambiar_clave/' . "$usuario->email/$reset_clave";
            $body = "<p>Alguien (probablemente usted) solicitó que le enviemos
                este mensaje porque usted se ha olvidado de
                la contraseña de su cuenta.</p>
                <p>Si hace clic en el enlace de abajo, que le llevará a una página
                que tiene más indicaciones para cambiar o recuperar su contraseña.</p>
                <p>Si hace clic en el enlace y no funciona, copie y pegue el enlace
                en la barra de direcciones de su navegador.</p>
                <p>$url.</p>
                <p>Si usted no inició esta solicitud, por favor ignorarlo.
                Si necesita más ayuda, por favor visítenos en $host
                o envíenos un email a soporte@gruposantamariasa.com.</p>
                <p>Este mensaje se genera automáticamente.</p>
                <p>Has recibido este correo electrónico porque un restablecimiento de contraseña se solicitó para su cuenta.</p>";

            $usuario->reset = $reset_clave;
            if ($usuario->update()) {
                if (Correo::send($usuario->email, $usuario->nombre, 'Restablecimiento de clave', $body)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            throw new KumbiaException('El usuario no existe con este email o nombre de usuario.');
        }
    }

}