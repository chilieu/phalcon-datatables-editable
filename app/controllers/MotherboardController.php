<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class MotherboardController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Save Ajax edit action
     */
    public function ajaxsaveAction()
    {
        //disable view in Ajax processing
        $this->view->disable();

        //getting request values
        $value = $this->request->get("value");
        $id = $this->request->getPost("id");
        $columnName = $this->request->getPost("columnName");
        
        //getting row will be edited
        $motherboard = Motherboard::findFirstByid($id);
        if (!$motherboard) {
            $data = "Cannot found this ID: " . $id;
        } else {
            $motherboard->$columnName = $value;
            if( $motherboard->save() == false ){
                $data = "Cannot save to database";
            }
            $data = $value;
        }
        echo $data;
    }

    /**
     * Save Ajax order action
     */
    public function ajaxorderAction()
    {
        //disable view in Ajax processing
        $this->view->disable();

        //getting request values
        $value = $this->request->getPost();
        
        print_r($value);
    }
    

    /**
     * Searches for motherboard
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Motherboard", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "sort ASC";

        $motherboard = Motherboard::find($parameters);
        if (count($motherboard) == 0) {
            $this->flash->notice("The search did not find any motherboard");

            return $this->dispatcher->forward(array(
                "controller" => "motherboard",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $motherboard,
            "limit"=> 10,
            "page" => $numberPage
        ));
        $this->view->page = $paginator->getPaginate();

    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Displays the components form
     */
    public function componentsAction($motherboard_id)
    {

        //getting motherboard info
        $cmp = Motherboard::findFirst("id=" . $motherboard_id);
        $this->view->cmp = $cmp;

        //getting components
        $components = Components::find();
        if (count($components) == 0) {
            $this->flash->notice("The is no component in system, please input component data into Component Table");
        }
        $this->view->components = $components;

        //get all compatibility by Motherboard_id
        $motherboard_comp_connection = array();
        $compatibility = MotherboardComponents::find(array(
            "conditions" => "motherboard_id = ?1",
            "bind"       => array(1 => $motherboard_id)
        ));

        foreach($compatibility as $post) {
            // i do not need to know/define all properties here, this becomes much flexible
            $motherboard_comp_connection[$post->components_id] = $post->toArray(); 
        }

        $this->view->compatibility = $motherboard_comp_connection;

    }

    /**
     * Displays the components form
     */
    public function componentssaveAction()
    {
        $parameters = $this->request->getPost();
        $compatibility_components = $parameters['compatible'];

        foreach($compatibility_components as $key => $val){

            // Query compatibility binding parameters with string placeholders
            $conditions = "motherboard_id = :motherboard_id: AND components_id = :components_id:";

            //Parameters whose keys are the same as placeholders
            $param = array(
                "motherboard_id" => $parameters['motherboard_id'],
                "components_id" => $key
            );

            $cmp = MotherboardComponents::findFirst(array(
                $conditions,
                "bind" => $param
            ));

            if( !$cmp ){//if doesn't exists => save it
                $save = new MotherboardComponents();
                $save->motherboard_id = $parameters['motherboard_id'];
                $save->components_id = $key;
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
        return $this->response->redirect("motherboard/components/" . $parameters['motherboard_id']);

    }

    /**
     * Creates a new motherboard
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "motherboard",
                "action" => "index"
            ));
        }

        $motherboard = new Motherboard();

        $motherboard->model = $this->request->getPost("model");
        $motherboard->manufacturer = $this->request->getPost("manufacturer");
        

        if (!$motherboard->save()) {
            foreach ($motherboard->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "motherboard",
                "action" => "new"
            ));
        }

        $this->flash->success("motherboard was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "motherboard",
            "action" => "index"
        ));

    }

    /**
     * Deletes a motherboard
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $motherboard = Motherboard::findFirstByid($id);
        if (!$motherboard) {
            $this->flashSession->error("motherboard was not found");

            return $this->dispatcher->forward(array(
                "controller" => "motherboard",
                "action" => "index"
            ));
        }

        if (!$motherboard->delete()) {

            foreach ($motherboard->getMessages() as $message) {
                $this->flashSession->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "motherboard",
                "action" => "search"
            ));
        }

        $this->flashSession->success("motherboard was deleted successfully");
        return $this->response->redirect("motherboard/search");
    }

}
