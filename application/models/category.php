<?php

/**
 * Description of category
 *
 * @author Faizan Ayubi, Hemant Mann
 */
class Category extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_skill_id;
    
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
