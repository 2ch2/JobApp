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
        
        // find all the skills
        $skills = Skill::all(array("live = ?" => true));

        if (RequestMethods::post("action") == "postJob") {
            $job = new Job(array(
                "user_id" => $this->user->id,
                "organization_id" => "",
                "title" => RequestMethods::post("title"),
                "details" => RequestMethods::post("details"),
                "type" => RequestMethods::post("type"),
                "budget" => (int) RequestMethods::post("budget"),
                "time" => (int) RequestMethods::post("time"),
                "status" => "Plan",
                "live" => 0,
                "deleted" => false,
                "modified" => "00-00-00 00:00:00"
            ));
            $job->save();
            
            $skill = $_POST["skills"];
            foreach ($skill as $s) {
                $category = new Category(array(
                    "skill_id" => $s,
                    "property" => "job",
                    "property_id" => $job->id
                ));
                $category->save();
            }
            $view->set("message", "Job posted successfully");
        }
        $view->set("skills", $skills);
        
    }
    
    /**
     * @before _secure, changeLayout
     */
    public function listAll() {
        $view = $this->getActionView();
        
        $jobs = Job::all(array("user_id = ?" => $this->user->id));
        $view->set("jobs", $jobs);
    }

    /**
     * @before _secure, changeLayout
     */
    public function edit($jobId = "") {
        $view = $this->getActionView();

        $job = Job::first(array("id = ?" => $jobId));

        if (RequestMethods::post("action") == "editJob") {
            $job->title = RequestMethods::post("title");
            $job->details = RequestMethods::post("details");
            $job->type = RequestMethods::post("type");
            $job->budget = RequestMethods::post("budget");
            $job->time = RequestMethods::post("time");

            $job->save();
            $view->set("success", true);
        }

        $view->set("job", $job);
    }
}
