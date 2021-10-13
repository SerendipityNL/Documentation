<?php

namespace TrafficSupply\Documentation;

class Documentation
{

    private $pages     = [];
    private $directory = '';
    private $main_directory = 'home';
    private $code_directory = 'code';

    public function __construct( $settings )
    {

        if ( isset( $settings['directory'] ) ) {
            $this->directory = $settings['directory'];
        }

        if ( isset( $settings['main_directory'] ) ) {
            $this->main_directory = $settings['main_directory'];
        }

        $this->setMainPage();

        if ( ! $settings['prevent_auto_parse'] ?? true ) {
            $this->parse();
        }

        return $this;

    }

    public function parse()
    {
        $this->parseIndexes();

        $this->parseSubFiles();
    }

    private function parseIndexes()
    {

        $files = glob( $this->directory.'/**/index.php' );

        foreach ( $files as $file ) {

            $is_matching = preg_match( '~^'.$this->directory.'/(?<directory>(?!'.$this->code_directory.'|'.$this->main_directory.').*)/index.php$~', $file, $matches );

            if ( ! $is_matching ) {
            	continue;
            }

            $page = [
                'directory' => $matches['directory'],
                'title'     => $this->directory_to_title( $matches['directory'] ),
                'files'     => [],
            ];

            $this->addPage( $page );
        }

    }

    private function parseSubFiles()
    {

        foreach ( $this->getPages() as &$page ) {

            $files = glob( $this->directory.'/'.$page['directory'].'/_*.php' );

            foreach ( $files as $file ) {

                preg_match( '~^'.$this->directory.'/'.$page['directory'].'/_(?<file>.*).php$~', $file, $matches );

                $this->addSubFile( $page['directory'], $matches['file'] );
            }

        }

    }

    private function addPage( $page )
    {
        $this->pages[$page['directory']] = $page;
    }

    public function getPages()
    {
        return $this->pages;
    }

    private function addSubFile( $directory, $file )
    {
        $this->pages[$directory]['files'][] = $file;
    }

    private function setMainPage()
    {
        $page = [
            'directory' => $this->main_directory,
            'title'     => $this->directory_to_title( $this->main_directory ),
        ];

        $this->addPage( $page );
    }

    private function directory_to_title( $directory )
    {
        return ucwords( str_replace( '_', ' ', $directory ) );
    }

}
