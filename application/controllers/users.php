<?php

/**
 * User Controller: Handles user login/signup and related functions
 *
 * @author Hemant Mann
 */
use Shared\Controller as Controller;
use Framework\RequestMethods as RequestMethods;
use Framework\ArrayMethods as ArrayMethods;
use Framework\Registry as Registry;

class Users extends Controller {

    /**
     * @before _secure, changeLayout
     */
    public function index() {
        
    }

    public function login() {
        if ($this->user)
            self::redirect("/users/");
        $view = $this->getActionView();

        if (RequestMethods::post("action") == "login") {
            $password = RequestMethods::post("password");
            $username = RequestMethods::post("username");

            $user = User::first(array("username = ?" => $username));

            if ($user) {
                if ($this->passwordCheck($password, $user->password)) {
                    // successful login
                    $this->setUser($user);
                    self::redirect("/users/");
                } else {
                    $error = "Invalid username/password";
                }
            } else {
                $error = "Invalid username/password";
            }
            $view->set("error", $error);
        }
    }

    public function signup() {
        if ($this->user)
            self::redirect("/users/");
        $view = $this->getActionView();

        if (RequestMethods::post("action") == "signUp") {
            $password = RequestMethods::post("password");

            $user = new User(array(
                "name" => RequestMethods::post("name"),
                "username" => RequestMethods::post("username"),
                "email" => RequestMethods::post("email"),
                "password" => $this->encrypt($password),
                "location" => RequestMethods::post("location", ""),
                "live" => true,
                "deleted" => false
            ));

            if (RequestMethods::post("confirm") != $password) {
                $view->set("message", "Password's don't match!");
            } else {
                $user->save();
                $view->set("message", "You are registered!! Please login to continue");
            }
        }
    }

    public function logout() {
        $this->setUser(false);
        self::redirect("/login");
    }

    /**
     * @before _secure, changeLayout
     */
    public function edit($id = "") {
        $view = $this->getActionView();
        $user = $this->user;
        $skills = Skill::all(array("live = ?" => true));

        if (RequestMethods::post("action") == "editProfile") {
            $user->name = RequestMethods::post("name");
            $user->location = RequestMethods::post("location", "");
            $user->save();
            $this->setUser($user);

            $getSkills = RequestMethods::post("skills", []);  // Get all the skills submitted by the user
            
            // first find already saved categories
            $categories = Category::all(array("property = ?" => "user", "property_id = ?" => $user->id));
            foreach ($categories as $cat) {
                $position = array_search($cat->skill_id, $getSkills);

                if ($position !== FALSE) {
                    unset($getSkills[$position]);   // skill is already saved so no need to save it
                    if (!$cat->live) {
                        $cat->live = true;
                        $cat->save();
                    }
                } else { // skill saved in db but not in the updated list submitted by user
                    $cat->live = false;
                    $cat->save();
                }
            }

            // then save any remaining new categories
            foreach ($getSkills as $skill) {
                $category = new Category(array(
                    "skill_id" => $skill,
                    "property" => "user",
                    "property_id" => $user->id
                ));
                $category->save();
            }
            $view->set("success", true);
        }

        $view->set("skills", $skills);
        $view->set("user", $user);
    }

    /**
     * @before _secure, changeLayout
     */
    public function organizations() {
        $view = $this->getActionView();

        $member = Member::all(array("user_id = ?" => $this->user->id));
        $organizations = array();

        foreach ($member as $m) {
            $org = Organization::first(array("id = ?" => $m->organization_id));

            $organizations[] = array(
                "id" => $org->id,
                "name" => $org->name,
                "website" => $org->website,
                "country" => $org->country,
                "designation" => $m->designation
            );
        }

        $organizations = (!empty($organizations)) ? ArrayMethods::toObject($organizations) : array();
        $view->set("organizations", $organizations);
    }

    /**
     * @before _secure, changeLayout
     */
    public function addOrg($id="") {
        $view = $this->getActionView();
        $org = Organization::first(array("id = ?" => $id));
        $member = Member::first(array("user_id = ?" => $this->user->id, "organization_id = ?" => $id));

        if (RequestMethods::post("action") == "saveOrg") {
            if (!$org) {
                $org = new Organization(array());
                $member = new Member(array("user_id" => $this->user->id));
            }
            $org->name = RequestMethods::post("name");
            $org->website = RequestMethods::post("website");
            $org->country = RequestMethods::post("country");
            $org->save();

            $member->organization_id = $org->id;
            $member->designation = RequestMethods::post("designation");
            $member->save();

            $view->set("success", true);
        }
        $view->set("designation", $member->designation);
        $view->set("org", $org);
    }

    public function changeLayout() {
        $this->defaultLayout = "layouts/users";
        $this->setLayout();
    }

    /**
     * Encrypts the password using blowfish algorithm
     */
    protected function encrypt($password) {
        $hash_format = "$2y$10$";  //tells PHP to use Blowfish with a "cost" of 10
        $salt_length = 22; //Blowfish salts should be 22-characters or more
        $salt = $this->generateSalt($salt_length);
        $format_and_salt = $hash_format . $salt;
        $hash = crypt($password, $format_and_salt);
        return $hash;
    }

    /**
     * Generates a salt for hashing the password
     */
    private function generateSalt($length) {
        //Not 100% unique, not 100% random, but good enought for a salt
        //MD5 returns 32 characters
        $unique_random_string = md5(uniqid(mt_rand(), true));

        //valid characters for a salt are [a-z A-Z 0-9 ./]
        $base64_string = base64_encode($unique_random_string);

        //but not '+' which is in base64 encoding
        $modified_base64_string = str_replace('+', '.', $base64_string);

        //Truncate string to the correct length
        $salt = substr($modified_base64_string, 0, $length);

        return $salt;
    }

    /**
     * Checks the password by hashing it using the existing hash
     */
    protected function passwordCheck($password, $existingHash) {
        //existing hash contains format and salt or start
        $hash = crypt($password, $existingHash);
        if ($hash == $existingHash) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Finds the average rating of user based on the projects
     * @param array $ratingObj array of review objects
     */
    protected function rating($ratingObj) {
        $rating = 0; $i = 1;
        foreach ($ratingObj as $review) {
            $rating += $review->rating;
            $i++;
        }

        $average = ($rating === 0) ? "User not reviewed yet" : $rating/$i;
        return $average;
    }

}
