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
        $this->activo = 'si';
        if (!$this->rol_id) {
            $this->rol_id = 5; //El rol de Registrado
            $this->activo = 'no';
        }
    }

    public function before_save() {
        if (!$this->id) {
            $usuario = new Usuario();
            if ($usuario->find_first('nick = "' . $this->nick . '"')) {
                Flash::error('Por favor ingrese otro nick porque este ya existe.');
                return 'cancel';
            }
            if ($usuario->find_first('email = "' . $this->email . '"')) {
                Flash::error('Esta cuenta de email "' . $this->email . '" ya esta siendo utilizada.');
                return 'cancel';
            }
            Load::model('util/misc');
            if (Misc::isEmailToBan($this->email)) {
                Flash::error('El dominio de esta cuenta de email "' . $this->email . '" esta prohibido en nuestro sitio.');
                return 'cancel';
            }
            if (strlen($this->clave) < 5) {
                Flash::error('La clave debe tener al menos cinco (5) caracteres');
                return 'cancel';
            }

            if ($this->acepta_terminos != 'si') {
                Flash::error('Debe aceptar los terminos y condiciones de uso.');
                return 'cancel';
            }
            $this->clave = sha1($this->clave);
        }
    }

    public function after_save() {
        if ($this->activo == 'no' && $this->reset == '') {
            if (!$this->enviar_activacion($this->id)) {
                return 'cancel';
            }
        }
    }

    public function cambiar_clave($id, $clave, $clave2) {

        if ($clave == $clave2) {
            if (strlen($clave) < 5) {
                Flash::error(' La clave debe tener al menos cinco (5) caracteres');
                return false;
            }
            $usuario = $this->find_first($id);
            if ($usuario) {
                $usuario->clave = sha1($clave);
                Load::model('util/misc');
                $usuario->reset = Misc::generarClave(50);
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

    public function editar($id, $nombre, $nick, $web, $bio) {

        $usuario = $this->find_first($id);
        if ($usuario) {
            $usuario->nombre = $nombre;
            $usuario->nick = $nick;
            $usuario->web = $web;
            $usuario->bio = $bio;
            if ($usuario->update()) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new KumbiaException('El usuario no existe');
        }
    }

    public function findByEmail($email) {
        return $this->find_first("email = '$email'");
    }

    public function findByNick($nick) {
        return $this->find_first("nick = '$nick'");
    }

    public function enviar_activacion($id) {
        Load::model('util/misc');
        Load::model('util/correo');
        $usuario = $this->find($id);
        
        if ($usuario) {
            if ($usuario->activo == 'si') {
                throw new KumbiaException("Este usuario ya activo su cuenta: $usuario->nombre");                
            }
            
            $reset_clave = Misc::generarClave(33);
            //Para el correo
            $host = Config::get('config.sitio.dominio');
            $email = Config::get('config.sitio.email');
            $nombre = Config::get('config.sitio.nombre');
            $url = $host . "usuario/activar/$usuario->id/$reset_clave/";

            //TODO que este contenido del correo lo tome de una plantilla.
            $body = "<p>Le damos la bienvenida, $usuario->nombre.<p>
                    <p>Está a un sólo paso de abrir una cuenta en $nombre.</p>
                    <p>Únicamente le queda confirmar que tenemos su dirección de correo correcta.
                    Para ello, haga clic en el siguiente enlace o cópielo y péguelo en la ventana de
                    direcciones de su navegador Web:</p>
                    <p>
                    $url
                    </p>
                    <p>Si usted no inició esta solicitud, por favor ignorarlo.
                    Si necesita más ayuda, por favor visítenos en $host
                    o envíenos un email a $email.</p>
                    <p>Este mensaje se genera automáticamente.</p>";

            $usuario->reset = $reset_clave;
            if ($usuario->update()) {
                if ($rs = Correo::send($usuario->email, $usuario->nombre, "Gracias por conectarse a $nombre, Bienvenido.", $body)) {
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
    
    public function reenviarActivacion($email_or_username) {        
        $usuario = $this->findByEmail($email_or_username);

        if (!$usuario) {
            $usuario = $this->findByNick($email_or_username);
        }
        if ($usuario) {
            return $this->enviar_activacion($usuario->id);
        }
    }

    public function resetClaveByEmailOrUsername($email_or_username) {
        Load::model('util/misc');
        Load::model('util/correo');
        $usuario = $this->findByEmail($email_or_username);

        if (!$usuario) {
            $usuario = $this->findByNick($email_or_username);
        }
        if ($usuario) {
            $reset_clave = Misc::generarClave(33);
            //Para el correo
            $host = Config::get('config.sitio.dominio');
            $email = Config::get('config.sitio.email');
            $url = $host . "usuario/cambiar_clave/$usuario->email/$reset_clave/";
            //TODO que este contenido del correo lo tome de una plantilla.
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
                o envíenos un email a $email.</p>
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