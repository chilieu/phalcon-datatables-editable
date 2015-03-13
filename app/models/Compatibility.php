<?php

class Compatibility extends \Phalcon\Mvc\Model
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
    public $compontents_id;

    /**
     *
     * @var integer
     */
    public $os_id;

    /**
     *
     * @var string
     */
    public $note;

    public function initialize()
    {
        $this->belongsTo("os_id", "Os", "id");
        $this->belongsTo("components_id", "Components", "id");
    }

    public static function checkCompatibility($components_id, $os_id){
        $conditions = "components_id = :components_id: AND os_id = :os_id: ";
        $param = array("components_id" => $components_id, "os_id" => $os_id);

        $compatible = Compatibility::findFirst(array(
            $conditions,
            "bind" => $param
        ));
        
        if( count($compatible->components) == 0 ){
            return array("");
        } else {
            return array_merge($compatible->components->toArray(), $compatible->toArray());
        }

    }

}
