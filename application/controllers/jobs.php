<?php

/**
 * Jobs Controller: Handles job posting/reviewing etc
 *
 * @author Hemant Mann
 */
use Framework\RequestMethods as RequestMethods;
use Framework\ArrayMethods as ArrayMethods;
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
            $min = RequestMethods::post("min");
            $max = RequestMethods::post("max");

            $job = new Job(array(
                "user_id" => $this->user->id,
                "organization_id" => "",
                "title" => RequestMethods::post("title"),
                "details" => RequestMethods::post("details"),
                "type" => RequestMethods::post("type"),
                "budget" => $min. "-" . $max,
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

    public function display($name, $id) {
        if (empty($id)) {
            self::redirect("/");
        }

        $view = $this->getActionView();

        $job = Job::first(array("id = ?" => $id));
        $user = User::first(array("id = ?" => $job->user_id));
        
        // find all the categories under which the job is listed
        $categories = Category::all(array("property = ?" => "job", "property_id = ?" => $job->id));
        
        // find bids for the jobs
        $findBids = Bid::all(array("job_id = ?" => $job->id));
        $bids = array();
        foreach ($findBids as $b) {
            // find the details of the bidder
            $user = User::first(array("id = ?" => $b->user_id), array("location", "username"));
            $review = Review::all(array("user_id = ?" => $b->user_id), array("rating"));

            $bids[] = array(
                "id" => $b->id,
                "user" => $user->username,
                "user_id" => $b->user_id,
                "user_location" => $user->location,
                "user_rating" => $this->rating($review),
                "amount" => $b->amount,
                "delivery_time" => $b->delivery
            );
        }
        $bids = (!empty($bids)) ? ArrayMethods::toObject($bids) : array();

        $view->set("job", $job);
        $view->set("user", $user);
        $view->set("bids", $bids);
        $view->set("categories", $categories);
    }

    public function skills($name) {
        if (empty($name)) {
            self::redirect("/");
        }

        $view = $this->getActionView();

        $skill = Skill::first(array("name = ?" => $name), array("id"));
        $categories = Category::all(array("property = ?" => "job", "skill_id = ?" => $skill->id));

        $jobs = array();
        foreach ($categories as $cat) {
            $job = Job::first(array("id = ?" => $cat->property_id));
            $jobCategories = Category::all(array("property = ?" => "job", "property_id = ?" => $job->id));
            $jobs[] = array(
                "id" => $job->id,
                "title" => $job->title,
                "type" => $job->type,
                "budget" => $job->budget,
                "time" => $job->time,
                "categories" => $jobCategories
            );
        }
        $jobs = (!empty($jobs)) ? ArrayMethods::toObject($jobs) : array();

        $view->set("jobs", $jobs);
    }

    /**
     * @before _secure, changeLayout
     */
    public function bid($jobId) {
        if (empty($jobId)) {
            self::redirect("/");
        }

        $view = $this->getActionView();

        if (RequestMethods::post("action") == "placeBid") {
            $bid = new Bid(array(
                "amount" => RequestMethods::post("amount"),
                "delivery" => RequestMethods::post("delivery_time"),
                "details" => RequestMethods::post("details"),
                "job_id" => $jobId,
                "user_id" => $this->user->id,
                "live" => true,
                "deleted" => false,
            ));
            $bid->save();

            $view->set("sucess", true);
        }
    }
}
