<?php

class Components extends \Phalcon\Mvc\Model
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
    public $type;

    /**
     *
     * @var string
     */
    public $manufacturer;

    /**
     *
     * @var string
     */
    public $model;


    public function initialize()
    {
       $this->hasMany("id", "MotherboardComponents", "components_id");
       $this->hasMany("id", "Compatibility", "components_id");

        $this->hasManyToMany(
            "id",
            "Compatibility",
            "components_id", "os_id",
            "Os",
            "id"
        );

    }

}
