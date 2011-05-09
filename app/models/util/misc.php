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
 * Clase que contienes varias funciones de utilidad.
 * 
 * @package Util
 * @license http://www.gnu.org/licenses/agpl.txt GNU AFFERO GENERAL PUBLIC LICENSE version 3.
 * @author Henry Stivens Adarme Muñoz <henry.stivens@gmail.com>
 */
class Misc {

    /**
     * Genera una clave aleatoria, dado un tamaño.
     *
     * @param int $length
     */
    public static function generarClave($length) {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $cad = '';
        for ($i = 0; $i < $length; $i++) {
            $cad .= substr($str, rand(0, 62), 1);
        }
        return $cad;
    }

    /**
     * Genera un hash md5 para intentar identificar el usuario. Capura el navegador,
     * Ip del proxy + la IP, si tiene habilitado cookies...
     */
    public static function fingerPrint() {
        $str = $_SERVER['HTTP_USER_AGENT'];

        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $pipaddress = getenv('HTTP_X_FORWARDED_FOR');
            $ipaddress = getenv('REMOTE_ADDR');
            $str .= $pipaddress . $ipaddress;
        } else {
            $ipaddress = getenv('REMOTE_ADDR');
            $str .= $ipaddress;
        }

        setcookie('test', 1, time() + 3600);

        if (count($_COOKIE) > 0) {
            $str .= "cookie=yes";
        } else {
            $str .= "cookie=no";
        }

        $str .= $_SERVER['HTTP_ACCEPT'] . $_SERVER['HTTP_ACCEPT_LANGUAGE'];

        return md5($str);
    }
    
    /**
     * Retorna la Ip del proxy si la hay y la IP
     * IpProxy/IP o solo la IP
     * @return type 
     */
    public static function getIp() {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $pipaddress = getenv('HTTP_X_FORWARDED_FOR');
            $ipaddress = getenv('REMOTE_ADDR');
            return $pipaddress .'/'. $ipaddress;
        } else {
            $ipaddress = getenv('REMOTE_ADDR');
            return $ipaddress;
        }
    }

    /**
     * Verifica que el email no este en una lista negra.
     */
    public static function isEmailToBan($email) {
        $domain = explode('@', $email);
        $key = 'ca9a190d1e53a1f4b199c21cadc14946';
        $request = 'http://check.block-disposable-email.com/api/json/' . $key . '/' . $domain[1];        

        $response = file_get_contents($request);
        $dea = json_decode($response);        

        if ($dea->request_status == 'success') {            
            if ($dea->domain_status == 'ok') {         
                return false;
            }

            if ($dea->domain_status == 'block') {
                return true;
            }
        }else{
            return true;
        }
    }
}