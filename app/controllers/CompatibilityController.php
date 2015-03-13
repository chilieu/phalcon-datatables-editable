<?php
use Phalcon\Mvc\Model\Criteria;

 
class CompatibilityController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Displays the setup form for components compatibility
     */
    public function setupAction()
    {
        //set layout to no nav layout
        $this->view->setLayout('nonavlayout');

		$comp_os_connection = array();
    	$components_id = $this->request->getQuery("components_id", "int");
    	$cmp = Components::findFirst("id=" . $components_id);

    	//get all compatibility by Components_id
		$compatibility = Compatibility::find(array(
		    "conditions" => "components_id = ?1",
		    "bind"       => array(1 => $components_id)
		));

		foreach($compatibility as $post) {
		    // i do net need to know/define all properties here, this becomes much flexible
		    $comp_os_connection[$post->os_id] = $post->toArray(); 
		}

		$os = Os::find();
		if (count($os) == 0) {
			$this->flash->notice("The is no OS in system, please input OS data into OS Table");
		}

		$this->view->os = $os;
		$this->view->components_id = $components_id;
		$this->view->cmp = $cmp;
		$this->view->compatibility = $comp_os_connection;
    }

    public function saveAction()
    {
    	$parameters = $this->request->getPost();
    	$compatibility_components = $parameters['compatible'];

    	foreach($compatibility_components as $key => $val){

			// Query compatibility binding parameters with string placeholders
			$conditions = "components_id = :components_id: AND os_id = :os_id:";

			//Parameters whose keys are the same as placeholders
			$param = array(
			    "components_id" => $parameters['components_id'],
			    "os_id" => $key
			);

			$cmp = Compatibility::findFirst(array(
    			$conditions,
    			"bind" => $param
			));

			if( !$cmp ){//if doesn't exists => save it
				$save = new Compatibility();
				$save->components_id = $parameters['components_id'];
				$save->os_id = $key;
				$save->compatible = $val['compatible'];
				$save->note = $val['note'];
				$save->save();
			} else {//update record
				$cmp->compatible = $val['compatible'];
				$cmp->note = $val['note'];
				$cmp->save();
			}

    	}

        $this->flashSession->success("Your information was stored correctly!");
        //return $this->dispatcher->forward(array("controller" => "compatibility","action" => "setup"));
        return $this->response->redirect("compatibility/setup?components_id=" . $parameters['components_id']);

    }

    public function ajaxSaveAction()
    {
        //disable view in Ajax processing
        $this->view->disable();
    	$parameters = $this->request->getPost();

		// Query compatibility binding parameters with string placeholders
		$conditions = "components_id = :components_id: AND os_id = :os_id:";

		//Parameters whose keys are the same as placeholders
		$param = array(
		    "components_id" => $parameters['components_id'],
		    "os_id" => $parameters['os_id']
		);

		$cmp = Compatibility::findFirst(array(
			$conditions,
			"bind" => $param
		));

		if( !$cmp ){//if doesn't exists => save it
			$save = new Compatibility();
			$save->components_id = $parameters['components_id'];
			$save->os_id = $parameters['os_id'];
			$save->compatible = $parameters['compatible'];
			$save->save();
		} else {//update record
			$cmp->compatible = $parameters['compatible'];
			$cmp->save();
		}

	}

    public function ajaxSaveNoteAction()
    {
        //disable view in Ajax processing
        $this->view->disable();
    	$parameters = $this->request->getPost();

		// Query compatibility binding parameters with string placeholders
		$conditions = "components_id = :components_id: AND os_id = :os_id:";

		//Parameters whose keys are the same as placeholders
		$param = array(
		    "components_id" => $parameters['components_id'],
		    "os_id" => $parameters['os_id']
		);

		$cmp = Compatibility::findFirst(array(
			$conditions,
			"bind" => $param
		));

		if( !$cmp ){//if doesn't exists => save it
			$save = new Compatibility();
			$save->components_id = $parameters['components_id'];
			$save->os_id = $parameters['os_id'];
			$save->note = $parameters['note'];
			$save->save();
		} else {//update record
			$cmp->note = $parameters['note'];
			$cmp->save();
		}

	}


	public function ajaxDeleteAction(){
        //disable view in Ajax processing
        $this->view->disable();
		$parameters = $this->request->getPost();        

		// Query compatibility binding parameters with string placeholders
		$conditions = "components_id = :components_id: AND os_id = :os_id:";

		//Parameters whose keys are the same as placeholders
		$param = array(
		    "components_id" => $parameters['components_id'],
		    "os_id" => $parameters['os_id']
		);

		$cmp = Compatibility::findFirst(array(
			$conditions,
			"bind" => $param
		));

		if( !$cmp->delete() ){
            foreach ($component->getMessages() as $message) {
                $this->flashSession->error($message);
            }
		} else {
        	$this->flashSession->success("compatibility was deleted successfully");
		}

        return;
	}

}