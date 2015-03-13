<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

	public function initialize() {
		$this->view->setLayout('index');
	}


    // wrapper function for debug purposes.
    public function pr($data = null) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';      
    }
    
}
