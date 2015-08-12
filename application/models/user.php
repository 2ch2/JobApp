<?php

/**
 * The User Model
 *
 * @author Faizan Ayubi, Hemant Mann
 */
class User extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * @length 45
     * 
     * @validate required, min(3), max(45)
     * @label name
     */
    protected $_name;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 45
     * @index
     * 
     * @validate required, alpha, min(3), max(45)
     * @label username
     */
    protected $_username;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     * @index
     * 
     * @validate required, max(255)
     * @label email address
     */
    protected $_email;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     * 
     * @validate required, alpha, min(8), max(255)
     * @label password
     */
    protected $_password;

    /**
     * @column
     * @readwrite
     * @type text
     *
     * @label location
     */
    protected $_location;
}
