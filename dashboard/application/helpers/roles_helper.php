<?php
/**
 * Created by PhpStorm.
 * User: jedi
 * Date: 9/14/17
 * Time: 5:56 PM
 */

if ( ! function_exists('has_role'))
{
    function has_role($role_name)
    {
        $CI = & get_instance();  //get instance, access the CI superobject
        $user = $CI->session->userdata('user');
        if ($user && $user->is_admin == 1) {
            return true;
        }
        $roles = $CI->session->userdata('roles');
        return array_key_exists($role_name, $roles);
    }
}
