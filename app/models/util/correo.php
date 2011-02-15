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
 * Clase para el envío de correo electrónico.
 * 
 * @package Util
 * @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
 * @author Henry Stivens Adarme Muñoz <henry.stivens@gmail.com>
 */
class Correo {

    public static function send($para_correo, $para_nombre, $asunto, $cuerpo, $de_correo=null, $de_nombre=null) {
        //Carga las librería PHPMailer
        Load::lib('phpmailer');
        //instancia de PHPMailer
        $mail = new PHPMailer(true);

        $mail->IsSMTP();
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->SMTPSecure = 'ssl'; // sets the prefix to the servier
        $mail->Host = Config::get('config.correo.host');
        $mail->Port = Config::get('config.correo.port');
        $mail->Username = Config::get('config.correo.username');
        $mail->Password = Config::get('config.correo.password');

        if ($de_correo != null && $de_nombre != null) {
            $mail->AddReplyTo($de_correo, $de_nombre);
            $mail->From = $de_correo;
            $mail->FromName = $de_nombre;
        } else {
            $mail->AddReplyTo(Config::get('config.correo.from_mail'), Config::get('config.correo.from_name'));
            $mail->From = Config::get('config.correo.from_mail');
            $mail->FromName = Config::get('config.correo.from_name');
        }

        $mail->Subject = $asunto;
        $mail->Body = $cuerpo;
        $mail->WordWrap = 50; // set word wrap
        $mail->MsgHTML($cuerpo);
        $mail->AddAddress($para_correo, $para_nombre);
        $mail->IsHTML(true); // send as HTML
        $mail->SetLanguage('es');
        //Enviamos el correo
        $exito = $mail->Send();
        $intentos = 2;
        //esto se realizara siempre y cuando la variable $exito contenga como valor false
        while ((!$exito) && $intentos < 1) {
            sleep(5);
            $exito = $mail->Send();
            $intentos = $intentos + 1;
        }

        $mail->SmtpClose();
        return $exito;
    }

}

?>