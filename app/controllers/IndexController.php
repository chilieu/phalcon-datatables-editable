<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
		$parameters["order"] = "id";
		$components = Components::find($parameters)->toArray();

		$this->view->components = $components;

    }

}

