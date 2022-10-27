<?php

namespace TrafficSupply\Documentation;

use TrafficSupply\Documentation\Documentation;

class Renderer
{
    public function tableOfContents( $pages = [] )
    {
        $active_directory = Documentation::$active_directory;

        return view('menu', [
            'active_directory' => $active_directory,
            'pages' => $pages
        ]);
    }

    public function activePage( $active_page )
    {
        ob_start();

        include Documentation::$directory.'/'.Documentation::$active_directory.'/'.'index.php';

        if ( $active_page['files'] ) {

            foreach ( $active_page['files'] as $file ) {

                echo '<h2 id="'.$file['file'].'">'.$file['title'].'</h2>';

                include Documentation::$directory.'/'.Documentation::$active_directory.'/'.'_'.$file['file'].'.php';
            }
        }

        $contents = ob_get_clean();

        return $contents;
    }

}
