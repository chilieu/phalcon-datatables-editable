<?php

class Motherboard extends \Phalcon\Mvc\Model
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
    public $model;

    /**
     *
     * @var string
     */
    public $branch;

    /**
     *
     * @var integer
     */
    public $order;


    public function initialize()
    {
        $this->hasMany("id", "MotherboardComponents", "motherboard_id");

        $this->hasManyToMany(
            "id",
            "MotherboardComponents",
            "motherboard_id", "components_id",
            "Components",
            "id"
        );

    }

}
