<?php

namespace TrafficSupply\Documentation;

class Highlighter
{
    public static function file( $file, $syntax )
    {
        $path = Documentation::$directory.'/'.Documentation::$active_directory.'/'.Documentation::$code_directory.'/'.$file;

        if ( ! file_exists( $path ) ) {
            throw new \Exception( 'Code file does not exist' );
        }

        return "\n\r<pre><code class=\"$syntax\">".htmlentities( file_get_contents( $path ) )."</code></pre>\n\r";
    }

}
