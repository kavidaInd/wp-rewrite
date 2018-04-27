<?php
global $wp_query;
$custom_page = $wp_query->query_vars['teach_codes_custom_page'];
//var_dump($wp_query);
if($custom_page){
    echo 'Hi I am the custom page</br>';
    echo "Custom Page".$custom_page.'</br>';
    echo 'User Name:'.$wp_query->query_vars['user'].'</br>';
    echo 'User Id: '.$wp_query->query_vars['id'];
}