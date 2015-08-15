<?php

/**
 * User Controller: Handles user login/signup and related functions
 *
 * @author Hemant Mann
 */
use Shared\Controller as Controller;
use Framework\RequestMethods as RequestMethods;
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
                "deleted" => false,
                "modified" => "00-00-00 00:00:00"
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

}
