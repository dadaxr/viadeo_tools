<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dadoux
 * Date: 08/08/12
 * Time: 02:54
 * To change this template use File | Settings | File Templates.
 */

if (!function_exists('remove_accents')) {
    function remove_accents($str, $charset = 'utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

        return $str;
    }
}