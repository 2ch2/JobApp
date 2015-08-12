<?php

/**
 * The Job Model
 *
 * @author Faizan Ayubi, Hemant Mann
 */
class Job extends Shared\Model {
	
    /**
     * @column
     * @readwrite
     * @type integer
     * @index
     */
	protected $_user_id;

	/**
     * @column
     * @readwrite
     * @type integer
     * @index
     */
	protected $_organization_id;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 45
     * 
     * @validate required, min(3), max(45)
     * @label title
     */
    protected $_title;

    /**
     * @column
     * @readwrite
     * @type text
     *
     * @label details
     */
    protected $_details;

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_type;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     * 
     * @validate required, alpha, min(8), max(255)
     * @label status
     */
    protected $_status;

    /**
     * @column
     * @readwrite
     * @type integer
     * 
     * @validate required
     * @label budget
     */
    protected $_budget;

    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_time;
    
}