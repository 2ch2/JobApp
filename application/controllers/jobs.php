<?php

/**
 * Jobs Controller: Handles job posting/reviewing etc
 *
 * @author Hemant Mann
 */
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;

class Jobs extends Users {
    
    /**
     * @before _secure, changeLayout
     */
    public function create() {
    		$view = $this->getActionView();

    		if (RequestMethods::post("action") == "postJob") {
    			$job = new Job(array(
    				"title" => RequestMethods::post("title"),
    				"details" => RequestMethods::post("details"),
    				"type" => RequestMethods::post("type"),
    				"budget" => (int) RequestMethods::post("budget"),
    				"time" => (int) RequestMethods::post("time"),
    				"status" => "Plan",
    				"live" => false,
    				"deleted" => false,
    				"modified" => "00-00-00 00:00:00"
    			));
    			$job->save();
    		}
        
    }
}
