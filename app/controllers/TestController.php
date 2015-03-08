<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class TestController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for test
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Test", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $test = Test::find($parameters);
        if (count($test) == 0) {
            $this->flash->notice("The search did not find any test");

            return $this->dispatcher->forward(array(
                "controller" => "test",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $test,
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
     * Edits a test
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $test = Test::findFirstByid($id);
            if (!$test) {
                $this->flash->error("test was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "test",
                    "action" => "index"
                ));
            }

            $this->view->id = $test->id;

            $this->tag->setDefault("id", $test->id);
            $this->tag->setDefault("name", $test->name);
            
        }
    }

    /**
     * Creates a new test
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "test",
                "action" => "index"
            ));
        }

        $test = new Test();

        $test->id = $this->request->getPost("id");
        $test->name = $this->request->getPost("name");
        

        if (!$test->save()) {
            foreach ($test->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "test",
                "action" => "new"
            ));
        }

        $this->flash->success("test was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "test",
            "action" => "index"
        ));

    }

    /**
     * Saves a test edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "test",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $test = Test::findFirstByid($id);
        if (!$test) {
            $this->flash->error("test does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "test",
                "action" => "index"
            ));
        }

        $test->id = $this->request->getPost("id");
        $test->name = $this->request->getPost("name");
        

        if (!$test->save()) {

            foreach ($test->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "test",
                "action" => "edit",
                "params" => array($test->id)
            ));
        }

        $this->flash->success("test was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "test",
            "action" => "index"
        ));

    }

    /**
     * Deletes a test
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $test = Test::findFirstByid($id);
        if (!$test) {
            $this->flash->error("test was not found");

            return $this->dispatcher->forward(array(
                "controller" => "test",
                "action" => "index"
            ));
        }

        if (!$test->delete()) {

            foreach ($test->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "test",
                "action" => "search"
            ));
        }

        $this->flash->success("test was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "test",
            "action" => "index"
        ));
    }

}
