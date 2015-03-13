<?php

class MotherboardComponents extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $motherboard_id;

    /**
     *
     * @var integer
     */
    public $components_id;

    public function initialize()
    {
        $this->belongsTo("motherboard_id", "motherboard", "id");
        $this->belongsTo("components_id", "components", "id");
    }

    public function getSource()
    {
        return 'motherboard_components';
    }

}
