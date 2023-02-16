<?php


function pd_fullcalendar_custom_logs($message) { 
    if(is_array($message)) { 
        $message = json_encode($message); 
    } 
    $file = fopen(plugin_dir_path( dirname( __FILE__ ) )."logs/custom_logs.log","a"); 
    echo fwrite($file, "\n" . date('Y-m-d h:i:s') . " :: " . $message); 
    fclose($file); 
}