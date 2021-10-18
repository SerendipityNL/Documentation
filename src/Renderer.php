<?php

namespace TrafficSupply\Documentation;

use TrafficSupply\Documentation\Documentation;

class Renderer
{
    public function tableOfContents( $pages = [], $active_page = false )
    {
        $response = '<ul>';

        foreach ( $pages as $page ) {

            $response .= '<li '.( $page['directory'] === $active_page ? 'class="active"' : '' ).'>';
            $response .= '<a href="/'.$page['directory'].'">'.$page['title'].'</a>';

            if ( isset( $page['files'] ) && count( $page['files'] ) ) {

                $response .= '<ul>';

                foreach ( $page['files'] as $file ) {
                    $response .= '<li>';
                    $response .= '<a href="#'.$file['file'].'">'.$file['title'].'</a>';
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

        include Documentation::$directory.'/'.Documentation::$active_page.'/'.'index.php';

        if ( $active_page['files'] ) {

            foreach ( $active_page['files'] as $file ) {
                echo '<h2 id="'.$file['file'].'">'.$file['title'].'</h2>';
                include Documentation::$directory.'/'.Documentation::$active_page.'/'.'_'.$file['file'].'.php';
            }

        }

        $contents = ob_get_clean();

        return $contents;
    }

}