<?php

/**
 * Stores the id of users which are members of different organizations
 *
 * @author Faizan Ayubi, Hemant Mann
 */
class Member extends Shared\Model {

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
    protected $_organization_id;

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_designation;
}
