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
        $post_sort_value = $this->request->getPost("order");
        //print_r($post_sort_value);exit;

        $parameters["order"] = "sort ASC";
        $motherboard = Motherboard::find($parameters);
        $arr = $motherboard->toArray();
        foreach($post_sort_value as $key => $val){

            $sorted_item = Motherboard::findFirstByid($val);

            if( !$sorted_item ) {
                //no row was found 
                print_r("Cannot find update ID in database");exit;
            } else {
                $sort = ($key+1) * 100;
                $sorted_item->sort = $sort;
                if( $sorted_item->save() == false ){
                    //cannot save to database
                    print_r(" Cannot save to database");exit;
                }
            }
        }

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
        $this->view->page = $motherboard;
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
        //set layout to no nav layout
        $this->view->setLayout('nonavlayout');
    }

    /**
     * Displays the components form
     */
    public function componentsAddWithoutListAction($motherboard_id)
    {
        return $this->componentsAdd($motherboard_id);
    }

    /**
     * Displays the components form
     */
    public function componentsAddAction($motherboard_id)
    {
        //set layout to no nav layout
        $this->view->setLayout('nonavlayout');
        //getting motherboard info
        $motherboard = Motherboard::findFirst("id=" . $motherboard_id);
        $this->view->motherboard = $motherboard;

        //getting components
        $components = Components::find();
        if (count($components) == 0) {
            $this->flash->notice("The is no component in system, please input component data into Component Table");
        }
        $this->view->components = $components->toArray();

        //get all compatibility by Motherboard_id
        $motherboard_comp_connection = array();
        $compatibility = MotherboardComponents::find(array(
            "conditions" => "motherboard_id = ?1",
            "bind"       => array(1 => $motherboard_id)
        ));

        foreach($compatibility as $post) {
            // i do not need to know/define all properties here, this becomes much flexible
            $motherboard_comp_connection[$post->components_id] = $post->toArray(); 
            $motherboard_comp_connection[$post->components_id]['component_detail'] = Components::findFirstByid($post->components_id)->toArray();
        }

        $this->view->compatibility = $motherboard_comp_connection;

    }

    /**
     * Displays the components form
     */
    public function componentsAddSaveAction()
    {
        $motherboard_id = $this->request->getPost("motherboard_id");
        $components_id = $this->request->getPost("components_id");
        $note = $this->request->getPost("note");

        $MotherboardComponents = new MotherboardComponents();
        $MotherboardComponents->motherboard_id = $motherboard_id;
        $MotherboardComponents->components_id = $components_id;
        $MotherboardComponents->compatible = 1;
        $MotherboardComponents->note = $note;
        if ( !$MotherboardComponents->save() ){
            foreach ($MotherboardComponents->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "motherboard",
                "action" => "componentsAdd"
            ));
        }

        $this->flashSession->success("Your information was stored correctly!");
        return $this->response->redirect("motherboard/componentsAdd/" . $motherboard_id);

    }

    /**
     * Displays the components form
     */
    public function componentsAddDeleteAction($id)
    {
        $MotherboardComponents = MotherboardComponents::findFirstByid($id);
        if (!$MotherboardComponents) {
            $this->flashSession->error("Component was not found");

            return $this->dispatcher->forward(array(
                "controller" => "motherboard",
                "action" => "componentsAdd"
            ));
        }

        if (!$MotherboardComponents->delete()) {

            foreach ($MotherboardComponents->getMessages() as $message) {
                $this->flashSession->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "motherboard",
                "action" => "componentsAdd"
            ));
        }

        $this->flashSession->success("component was deleted successfully");
        return $this->response->redirect("motherboard/componentsAdd/" . $MotherboardComponents->motherboard_id);
    }

    /**
     * Displays the components form
     */
    public function componentsAction($motherboard_id)
    {
        //set layout to no nav layout
        $this->view->setLayout('nonavlayout');
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
        } else {
            $motherboard_id = $motherboard->id;
        }

        //also save motherboard to components table with type = 'Motherboard'
        $components = new Components();
        $components->type = 'Motherboard';
        $components->manufacturer = $this->request->getPost("manufacturer");
        $components->model = $this->request->getPost("model");
        if ( !$components->save() ){
            foreach ($components->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "motherboard",
                "action" => "new"
            ));
        } else {
            $components_id = $components->id;
        }

        //save motherboard_components table
        $motherboard_components = new MotherboardComponents();
        $motherboard_components->motherboard_id = $motherboard_id;
        $motherboard_components->components_id = $components_id;
        $motherboard_components->compatible = 1;//set to compatiable mod
        if ( !$motherboard_components->save() ){
            foreach ($motherboard_components->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "motherboard",
                "action" => "new"
            ));
        }


        //$this->flashSession->success("motherboard was created successfully");
        //return $this->response->redirect("motherboard/search");
        
        $this->flashSession->success("motherboard was created successfully");
        return $this->dispatcher->forward(array(
            "controller" => "motherboard",
            "action" => "new"
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

        $conditions = "motherboard_id = :motherboard_id:";
        $param = array("motherboard_id" => $id);

        $MotherboardComponents = MotherboardComponents::find(array(
            $conditions,
            "bind" => $param
        ));

        foreach ($MotherboardComponents as $item) {
            $item->delete();
        }

        $this->flashSession->success("motherboard was deleted successfully");
        return $this->response->redirect("motherboard/search");
    }

    /**
     * view motherboard
     *
     * @param string $id
     */
    public function motherboardAction($id)
    {
        $os_components = array();//create os with components array
        $motherboard = Motherboard::findFirstByid($id);//finding motherboard by ID
        $os = Os::find(array('order' => 'name DESC'));
        $components = $motherboard->Components;
        
        foreach ($os as $os_item) {
            $os_components[$os_item->id] = $os_item->toArray();

            foreach ($components as $component_item) {
                $os_components[$os_item->id]['compatibility'][$component_item->id] = Compatibility::checkCompatibility($component_item->id, $os_item->id);
            }
        }

        //$this->pr($os_components);

        if( count($motherboard->Components) == 0){
            $this->flashSession->notice("There is no component in <strong><u>" . $motherboard->manufacturer . " " . $motherboard->model . "</u></strong>");
            return $this->response->redirect("motherboard/search");
        } else {

            $this->view->motherboard = $motherboard;
            $this->view->os = $os_components;

        }

    }

}
