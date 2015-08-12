<?php

/**
 * Description of file
 *
 * @author Faizan Ayubi, Hemant Mann
 */
class File extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     */
    protected $_name;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 32
     */
    protected $_mime;

    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_size;

    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_user_id;

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_property;

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_property_id;

}
