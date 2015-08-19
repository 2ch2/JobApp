<?php

/**
 * The Bids Model
 *
 * @author Faizan Ayubi, Hemant Mann
 */
class Bid extends Shared\Model {
	
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
	protected $_job_id;

    /**
     * @column
     * @readwrite
     * @type integer
     * @index
     * 
     * @label amount
     */
    protected $_amount;

    /**
     * @column
     * @readwrite
     * @type integer
     *
     * @label delivery time
     */
    protected $_delivery;

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_details;

}
