<?php

namespace TrafficSupply\Documentation;

use TrafficSupply\Documentation\Parser;
use TrafficSupply\Documentation\Renderer;

class Documentation
{

    private $pages = [];

    public static $directory        = '';
    public static $home_directory   = 'home';
    public static $code_directory   = 'code';
    public static $active_directory = 'home';

    private $renderer;

    public function __construct( $settings )
    {
        if ( isset( $settings['directory'] ) ) {
            self::$directory = $settings['directory'];
        }

        if ( isset( $settings['home_directory'] ) ) {
            self::$home_directory = $settings['home_directory'];
        }

        self::$active_directory = $settings['active_page'] ?? $home_directory;

        if ( ! ( $settings['prevent_auto_parse'] ?? false ) ) {
            $this->pages = ( new Parser() )->run()->getPages();
        }

        return $this;
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function renderTableOfContents()
    {
        return $this->getRenderer()->tableOfContents( $this->pages );
    }

    public function renderActivePage()
    {
        $active_page = $this->getActivePage();

        return $this->getRenderer()->activePage( $active_page );
    }

    public function getActivePage()
    {
        return $this->pages[self::$active_directory];
    }

    private function getRenderer()
    {
        if ( ! $this->renderer ) {
            $this->renderer = new Renderer();
        }

        return $this->renderer;
    }

}
