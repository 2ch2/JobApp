<?php

/**
 * Description of organization
 *
 * @author Faizan Ayubi, Hemant Mann
 */
class Organization extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_name;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 45
     */
    protected $_country;

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_website;
    
}
