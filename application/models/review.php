<?php

/**
 * Review of a user for any project
 *
 * @author Faizan Ayubi, Hemant Mann
 */
class Review extends Shared\Model {

    /**
	 * @column
	 * @readwrite
	 * @type integer
	 */
    protected $_user_id;

    /**
	 * @column
	 * @readwrite
	 * @type integer
	 */
    protected $_project_id;

    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_rating;

}
