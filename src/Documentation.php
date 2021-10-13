<?php

namespace TrafficSupply\Documentation;

class Documentation {

	private $pages 	   = [];
	private $directory = '';
	private $main_page = 'home';

	public function __construct( $settings ) {

		if ( isset($settings['directory']) ) {
			$this->directory = $settings['directory'];
		}

		if ( isset($settings['main_page']) ) {
			$this->main_page = $settings['main_page'];
		}

		$this->setMainPage();

		if ( ! $settings['prevent_auto_parse'] ?? true ) {
			$this->parse();
		}

		return $this;

	}

	public function parse() {

		$files = glob($this->directory.'/**/index.php');

		foreach ( $files as $file ) {
			$directory_name = preg_replace('('.$this->directory.'/|/index.php)', '', $file);

			if ( $directory_name === $this->main_page ) {
				continue;
			}

			$page = [
				'folder' => $directory_name,
				'title'  => $this->directory_to_title($directory_name),
			];

			$this->addPage($page);
		}
	}

	private function addPage($page) {
		$this->pages[$page['folder']] = $page;
	}

	public function getPages() {
		return $this->pages;
	}

	private function setMainPage() {
		$page = [
			'folder' => $this->main_page,
			'title'  => $this->directory_to_title($this->main_page),
		];

		$this->addPage($page);
	}

	private function directory_to_title($directory) {
    	return ucwords(str_replace('_', ' ', $directory));
	}

}