<?php

class Os extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $revision;

    /**
     *
     * @var string
     */
    public $release_year;


    public function getId()    {
        return $this->id;
    }

    public function getName()    {
        return $this->name;
    }

    public function getRevision()    {
        return $this->revision;
    }

}
