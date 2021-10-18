<?php

namespace TrafficSupply\Documentation;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
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

        $adapter    = new LocalFilesystemAdapter( Documentation::$directory );
        $filesystem = new Filesystem( $adapter );

        $files = $filesystem->listContents( '/', true )
                            ->filter( function ( $attributes ) {

                                if ( $attributes->isDir() ) {
                                    return false;
                                }

                                return preg_match( '~^(?<directory>(?!'.Documentation::$code_directory.'|'.Documentation::$home_directory.').*)/index.php$~', $attributes->path() );
                            } )
                            ->sortByPath()
                            ->toArray();

        foreach ( $files as $file ) {

            preg_match( '~^(?<directory>(?!'.Documentation::$code_directory.'|'.Documentation::$home_directory.').*)/index.php$~', $file->path(), $matches );

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

            $adapter    = new LocalFilesystemAdapter( Documentation::$directory.'/'.$page['directory'] );
            $filesystem = new Filesystem( $adapter );

            $files = $filesystem->listContents( '/', true )
                                ->filter( function ( $attributes ) {

                                    if ( $attributes->isDir() ) {
                                        return false;
                                    }

                                    return preg_match( '~^_.*.php$~', $attributes->path() );
                                } )
                                ->sortByPath()
                                ->toArray();

            foreach ( $files as $file ) {

                preg_match( '~^_(?<file>.*).php$~', $file->path(), $matches );

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
