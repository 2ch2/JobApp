<?php

/**
 * The Default Home Controller Class
 *
 * @author Faizan Ayubi
 */
use Shared\Controller as Controller;

class Home extends Controller {

    public function index() {
        $view = $this->getActionView();

        $jobs = Job::all(array());
        $view->set("jobs", $jobs);
    }

    public function about() {

    }

    public function terms() {
    		
    }

    public function privacy() {
    		
    }

    public function contact() {
    		
    }

    public function faq() {
    		
    }

}
