<?php
spl_autoload_register(function ($class_name) {
    //TODO change to use NAMESPACES
    $first = strtok($class_name, '_');
    if ($first == 'Int') {
        require_once LIB_PATH . 'interface' . DS . $class_name . '.php';
    } else {
        require_once LIB_PATH . $class_name . '.php';
    }
    //throw new MissingException("Unable to load $name.");
});

function strip_zeros_from_date($marked_string = "")
{
    //remove marked zeros
    $no_zeros = str_replace('*0', '', $marked_string);
    //remove any remaining marks
    $cleaned_string = str_replace('*', '', $no_zeros);
    return $cleaned_string;
}

function redirect_to($location)
{
    header("Location: {$location}");
    exit;
}

function output_message($message = "")
{
    if (!empty($message)) {
        return "<p class=\"message\">{$message}</p>";
    } else {
        return "";
    }
}
?>