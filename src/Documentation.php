<?php

namespace TrafficSupply\Documentation;

class Documentation
{

    private $pages     = [];
    private $directory = '';
    private $home_directory = 'home';
    private $code_directory = 'code';

    public function __construct( $settings )
    {

        if ( isset( $settings['directory'] ) ) {
            $this->directory = $settings['directory'];
        }

        if ( isset( $settings['home_directory'] ) ) {
            $this->home_directory = $settings['home_directory'];
        }

        if ( ! ( $settings['prevent_auto_parse'] ?? false ) ) {
        	$this->pages = (new Parser())->run($this->directory, $this->home_directory, $this->code_directory)->getPages();
        }

        return $this;

    }

    public function getPages()
    {
        return $this->pages;
    }

}
