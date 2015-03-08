<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class OsController extends ControllerBase
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
        $os = Os::findFirstByid($id);
        if (!$os) {
            $data = "Cannot found this ID: " . $id;
        } else {
            $os->$columnName = $value;
            if( $os->save() == false ){
                $data = "Cannot save to database";
            }
            $data = $value;
        }
        echo $data;
    }

    /**
     * Searches for os
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Os", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $os = Os::find($parameters);
        if (count($os) == 0) {
            $this->flash->notice("The search did not find any os");

            return $this->dispatcher->forward(array(
                "controller" => "os",
                "action" => "index"
            ));
        }

        $this->view->page = Os::find();

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
     * Creates a new o
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "os",
                "action" => "index"
            ));
        }

        $o = new Os();

        $o->name = $this->request->getPost("name");
        $o->revision = $this->request->getPost("revision");
        $o->release_year = $this->request->getPost("release_year");
        

        if (!$o->save()) {
            foreach ($o->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "os",
                "action" => "new"
            ));
        }

        $this->flash->success("OS was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "os",
            "action" => "index"
        ));

    }

    /**
     * Deletes a o
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $o = Os::findFirstByid($id);
        if (!$o) {
            $this->flash->error("o was not found");

            return $this->dispatcher->forward(array(
                "controller" => "os",
                "action" => "search"
            ));
        }

        if (!$o->delete()) {

            foreach ($o->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "os",
                "action" => "search"
            ));
        }

        $this->flash->success("OS was deleted successfully");
        return $this->response->redirect("os/search");
    }

}
