<?php

namespace TrafficSupply\Documentation;

use TrafficSupply\Documentation\Documentation;

class Renderer
{
    public function tableOfContents( $pages = [] )
    {
        $response = '<ul class="table-of-contents">';

        foreach ( $pages as $page ) {

            $response .= '<li '.( $page['directory'] === Documentation::$active_directory ? 'class="active"' : '' ).'>';
            $response .= '<a href="/'.$page['directory'].'">'.$page['title'].'</a>';

            if ( isset( $page['files'] ) && count( $page['files'] ) ) {

                $response .= '<ul>';

                foreach ( $page['files'] as $file ) {
                    $response .= '<li>';

                    $link = '#'.$file['file'];

                    if ( $page['directory'] !== Documentation::$active_directory ) {
                        $link = '/'.$page['directory'].$link;
                    }

                    $response .= '<a href="'.$link.'">'.$file['title'].'</a>';

                    $response .= '</li>';
                }

                $response .= '</ul>';
            }

            $response .= '</li>';
        }

        $response .= '</ul>';

        return $response;
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
