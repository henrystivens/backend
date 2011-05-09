<?php
/**
 * KumbiaPHP web & app Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://wiki.kumbiaphp.com/Licencia
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@kumbiaphp.com so we can send you a copy immediately.
 *
 * Helper para Form
 * 
 * @category   KumbiaPHP
 * @package    Helpers 
 * @copyright  Copyright (c) 2005-2010 KumbiaPHP Team (http://www.kumbiaphp.com)
 * @license    http://wiki.kumbiaphp.com/Licencia     New BSD License
 */
class FormHtml5 extends Form
{    	
    /**
     * Campo search
     *
     * @param string $field nombre de campo
     * @param string|array $attrs atributos de campo
     * @param string $value
     * @return string
     */
    public static function search($field, $attrs = NULL, $value = NULL)
    {
        if(is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }
        
        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(self::_getFieldData($field, $value === NULL), EXTR_OVERWRITE);
        
        return "<input id=\"$id\" name=\"$name\" type=\"search\" value=\"$value\" $attrs/>";
    }    
    
    
    /**
     * Campo tel
     *
     * @param string $field nombre de campo
     * @param string|array $attrs atributos de campo
     * @param string $value
     * @return string
     */
    public static function tel($field, $attrs = NULL, $value = NULL)
    {
        if(is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }
        
        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(self::_getFieldData($field, $value === NULL), EXTR_OVERWRITE);
        
        return "<input id=\"$id\" name=\"$name\" type=\"tel\" value=\"$value\" $attrs/>";
    }


    /**
     * Campo url
     *
     * @param string $field nombre de campo
     * @param string|array $attrs atributos de campo
     * @param string $value
     * @return string
     */
    public static function url($field, $attrs = NULL, $value = NULL)
    {
        if(is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }

        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(self::_getFieldData($field, $value === NULL), EXTR_OVERWRITE);

        return "<input id=\"$id\" name=\"$name\" type=\"url\" value=\"$value\" $attrs/>";
    }


    /**
     * Campo email
     *
     * @param string $field nombre de campo
     * @param string|array $attrs atributos de campo
     * @param string $value
     * @return string
     */
    public static function email($field, $attrs = NULL, $value = NULL)
    {
        if(is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }

        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(self::_getFieldData($field, $value === NULL), EXTR_OVERWRITE);

        return "<input id=\"$id\" name=\"$name\" type=\"email\" value=\"$value\" $attrs/>";
    }


    /**
     * Campo datetime
     *
     * @param string $field nombre de campo
     * @param string|array $attrs atributos de campo
     * @param string $value
     * @return string
     */
    public static function datetime($field, $attrs = NULL, $value = NULL)
    {
        if(is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }

        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(self::_getFieldData($field, $value === NULL), EXTR_OVERWRITE);

        return "<input id=\"$id\" name=\"$name\" type=\"datetime\" value=\"$value\" $attrs/>";
    }

    /**
     * Campo date
     *
     * @param string $field nombre de campo
     * @param string|array $attrs atributos de campo
     * @param string $value
     * @return string
     */
    public static function dateNew($field, $attrs = NULL, $value = NULL)
    {
        if(is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }

        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(self::_getFieldData($field, $value === NULL), EXTR_OVERWRITE);

        return "<input id=\"$id\" name=\"$name\" type=\"date\" value=\"$value\" $attrs/>";
    }

    /**
     * Campo month
     *
     * @param string $field nombre de campo
     * @param string|array $attrs atributos de campo
     * @param string $value
     * @return string
     */
    public static function month($field, $attrs = NULL, $value = NULL)
    {
        if(is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }

        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(self::_getFieldData($field, $value === NULL), EXTR_OVERWRITE);

        return "<input id=\"$id\" name=\"$name\" type=\"month\" value=\"$value\" $attrs/>";
    }

    /**
     * Campo week
     *
     * @param string $field nombre de campo
     * @param string|array $attrs atributos de campo
     * @param string $value
     * @return string
     */
    public static function week($field, $attrs = NULL, $value = NULL)
    {
        if(is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }

        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(self::_getFieldData($field, $value === NULL), EXTR_OVERWRITE);

        return "<input id=\"$id\" name=\"$name\" type=\"week\" value=\"$value\" $attrs/>";
    }

    /**
     * Campo time
     *
     * @param string $field nombre de campo
     * @param string|array $attrs atributos de campo
     * @param string $value
     * @return string
     */
    public static function time($field, $attrs = NULL, $value = NULL)
    {
        if(is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }

        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(self::_getFieldData($field, $value === NULL), EXTR_OVERWRITE);

        return "<input id=\"$id\" name=\"$name\" type=\"time\" value=\"$value\" $attrs/>";
    }

    /**
     * Campo datetime-local
     *
     * @param string $field nombre de campo
     * @param string|array $attrs atributos de campo
     * @param string $value
     * @return string
     */
    public static function datetimeLocal($field, $attrs = NULL, $value = NULL)
    {
        if(is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }

        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(self::_getFieldData($field, $value === NULL), EXTR_OVERWRITE);

        return "<input id=\"$id\" name=\"$name\" type=\"datetime-local\" value=\"$value\" $attrs/>";
    }


    /**
     * Campo number
     *
     * @param string $field nombre de campo
     * @param string|array $attrs atributos de campo
     * @param string $value
     * @return string
     */
    public static function number($field, $attrs = NULL, $value = NULL)
    {
        if(is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }

        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(self::_getFieldData($field, $value === NULL), EXTR_OVERWRITE);

        return "<input id=\"$id\" name=\"$name\" type=\"number\" value=\"$value\" $attrs/>";
    }

    /**
     * Campo range
     *
     * @param string $field nombre de campo
     * @param string|array $attrs atributos de campo
     * @param string $value
     * @return string
     */
    public static function range($field, $attrs = NULL, $value = NULL)
    {
        if(is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }

        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(self::_getFieldData($field, $value === NULL), EXTR_OVERWRITE);

        return "<input id=\"$id\" name=\"$name\" type=\"range\" value=\"$value\" $attrs/>";
    }

    /**
     * Campo color
     *
     * @param string $field nombre de campo
     * @param string|array $attrs atributos de campo
     * @param string $value
     * @return string
     */
    public static function color($field, $attrs = NULL, $value = NULL)
    {
        if(is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }

        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(self::_getFieldData($field, $value === NULL), EXTR_OVERWRITE);

        return "<input id=\"$id\" name=\"$name\" type=\"color\" value=\"$value\" $attrs/>";
    }
}
