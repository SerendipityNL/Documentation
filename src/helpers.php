<?php

function hl( $file, $syntax )
{
    return \TrafficSupply\Documentation\Highlighter::file( $file, $syntax );
}

function view($name, $data)
{
    $file_path = __DIR__.'/views/'.$name.'.php';

    if ( ! is_file($file_path) ) {
        throw new Exception('View '.$name.' not found');
    }

    ob_start();

    foreach ( $data as $variable_name => $value ) {
        $$variable_name = $value;
    }

    include ( $file_path );

    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}