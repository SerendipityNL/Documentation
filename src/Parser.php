<?php

namespace TrafficSupply\Documentation;

class Parser
{

    private $pages = [];

    private $directory;
    private $home_directory;
    private $code_directory;

    public function run($directory = 'documentation', $home_directory = 'home', $code_directory = 'code') {
    	$this->directory      = $directory;
        $this->home_directory = $home_directory;
        $this->code_directory = $code_directory;

        $this->setMainPage();

        $this->parseIndexes();

        $this->parseSubFiles();

        return $this;
    }

    private function parseIndexes()
    {

        $files = glob( $this->directory.'/**/index.php' );

        foreach ( $files as $file ) {

            $is_matching = preg_match( '~^'.$this->directory.'/(?<directory>(?!'.$this->code_directory.'|'.$this->home_directory.').*)/index.php$~', $file, $matches );

            if ( ! $is_matching ) {
                continue;
            }

            $page = [
                'directory' => $matches['directory'],
                'title'     => self::directory_to_title( $matches['directory'] ),
                'files'     => [],
            ];

            $this->addPage( $page );
        }

    }

    private function parseSubFiles()
    {

        foreach ( $this->getPages() as $page ) {

            $files = glob( $this->directory.'/'.$page['directory'].'/_*.php' );

            foreach ( $files as $file ) {

                preg_match( '~^'.$this->directory.'/'.$page['directory'].'/_(?<file>.*).php$~', $file, $matches );

                $this->addSubFile( $page['directory'], $matches['file'] );
            }

        }

    }

    public function getPages()
    {
        return $this->pages;
    }

    private function addPage( $page )
    {
        $this->pages[$page['directory']] = $page;
    }

    private function addSubFile( $directory, $file )
    {
        $this->pages[$directory]['files'][] = $file;
    }

    private function setMainPage()
    {
        $page = [
            'directory' => $this->home_directory,
            'title'     => $this->directory_to_title( $this->home_directory ),
        ];

        $this->addPage( $page );
    }

    private function directory_to_title( $directory )
    {
        return ucwords( str_replace( '_', ' ', $directory ) );
    }

}
