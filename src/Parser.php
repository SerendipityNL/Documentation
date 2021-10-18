<?php

namespace TrafficSupply\Documentation;

use TrafficSupply\Documentation\Documentation;

class Parser
{

    private $pages = [];

    public function run()
    {

        $this->setMainPage();

        $this->parseIndexes();

        $this->parseSubFiles();

        return $this;
    }

    private function parseIndexes()
    {

        $files = glob( Documentation::$directory.'/*/index.php' );

        foreach ( $files as $file ) {

            $is_matching = preg_match( '~^'.Documentation::$directory.'/(?<directory>(?!'.Documentation::$code_directory.'|'.Documentation::$home_directory.').*)/index.php$~', $file, $matches );

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

        foreach ( $this->getPages() as $page ) {

            $files = glob( Documentation::$directory.'/'.$page['directory'].'/_*.php' );

            foreach ( $files as $file ) {

                preg_match( '~^'.Documentation::$directory.'/'.$page['directory'].'/_(?<file>.*).php$~', $file, $matches );

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
        $this->pages[$directory]['files'][] = [
            'title' => $this->directory_to_title( $file ),
            'file'  => $file,
        ];
    }

    private function setMainPage()
    {
        $page = [
            'directory' => Documentation::$home_directory,
            'title'     => $this->directory_to_title( Documentation::$home_directory ),
        ];

        $this->addPage( $page );
    }

    private function directory_to_title( $directory )
    {
        return ucwords( str_replace( '_', ' ', $directory ) );
    }

}
