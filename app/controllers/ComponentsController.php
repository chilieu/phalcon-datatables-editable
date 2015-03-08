<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ComponentsController extends ControllerBase
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
        $Components = Components::findFirstByid($id);
        if (!$Components) {
            $data = "Cannot found this ID: " . $id;
        } else {
            $Components->$columnName = $value;
            if( $Components->save() == false ){
                $data = "Cannot save to database";
            }
            $data = $value;
        }
        echo $data;
    }

    /**
     * Searches for components
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Components", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $components = Components::find($parameters);
        if (count($components) == 0) {
            $this->flash->notice("The search did not find any components");

            return $this->dispatcher->forward(array(
                "controller" => "components",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $components,
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
        //disable layout rendering
        //$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
    }

    /**
     * Creates a new component
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "components",
                "action" => "index"
            ));
        }

        $component = new Components();

        $component->type = $this->request->getPost("type");
        $component->manufacturer = $this->request->getPost("manufacturer");
        $component->model = $this->request->getPost("model");
        

        if (!$component->save()) {
            foreach ($component->getMessages() as $message) {
                $this->flashSession->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "components",
                "action" => "new"
            ));
        }

        $this->flashSession->success("component was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "components",
            "action" => "index"
        ));

    }

    /**
     * Deletes a component
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $component = Components::findFirstByid($id);
        if (!$component) {
            $this->flashSession->error("component was not found");

            return $this->dispatcher->forward(array(
                "controller" => "components",
                "action" => "search"
            ));
        }

        if (!$component->delete()) {

            foreach ($component->getMessages() as $message) {
                $this->flashSession->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "components",
                "action" => "search"
            ));
        }

        $this->flashSession->success("component was deleted successfully");
        return $this->response->redirect("components/search");
        
    }

}
