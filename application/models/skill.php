<?php

/**
 * List of skills under which a job can be listed
 *
 * @author Faizan Ayubi, Hemant Mann
 */
class Skill extends Shared\Model {
    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_name;
}
