<?php
/**
 * Description of admin
 *
 * @author Faizan Ayubi
 */
use Shared\Controller as Controller;
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;

class Admin extends Controller {
    
    public function sync($model) {
        $this->noview();
        $db = Framework\Registry::get("database");
        $db->sync(new $model);
    }
}
