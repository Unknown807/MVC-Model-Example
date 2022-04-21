<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    /*
    Loads the view_messages view to display all the user's posts
    */
	public function view($name = -1) {
        // If no name is provided then it loads a simple error page with a message
        if ($name == -1) {
			$this->load->view("error_page", array("error_text" => "You can't view a user's messages with a blank name"));
		} else {
            // sanitise the name parameter
            $san_name = htmlspecialchars(stripslashes(trim($name)));
            $follow_form_loc = "";
            $follow_but_text = "";
            $show_follow = false;
            $sess_username = $this->session->userdata("username");

            $this->load->model("messages_model");
            $data = $this->messages_model->getMessagesByPoster($san_name);
            
            // If the user is logged in and isn't on his profile, then check if
            // they are following the user on whose page they are on
            if ($sess_username && ($sess_username != $san_name) ) {
                $show_follow = true;
                $this->load->model("users_model");

                $is_following = $this->users_model->isFollowing(
                    $sess_username,
                    $san_name
                );

                // If the user is following them then change button for
                // unfollow functionality, otherwise change to follow
                if ($is_following) {
                    $follow_form_loc = "user/unfollow/".$san_name;
                    $follow_but_text = "Unfollow";
                } else {
                    $follow_form_loc = "user/follow/".$san_name;
                    $follow_but_text = "Follow";
                }
            }

            $viewdata = array(
                "username" => $san_name,
                "results" => $data,
                "show_follow" => $show_follow,
                "follow_form_loc" => $follow_form_loc,
                "follow_but_text" => $follow_but_text,
            );

            $this->load->view("view_messages", $viewdata);
		}
	}

    /*
    If the user is already logged in then redirect to their 
    view page
    */
    public function login() {
        $sess_username = $this->session->userdata("username");
        
        if ($sess_username) {
            redirect("user/view/".$sess_username, "refresh");
            return;
        }

        $viewdata = array(
            "username_val" => "",
            "username_error_text" => "",
            "password_error_text" => "",
        );
        $this->load->view("login_page", $viewdata);
    }

    /*
    If the user is not logged in then attempt to process their
    form data to log them in
    */
    public function doLogin() {

        $sess_username = $this->session->userdata("username");
        if ($sess_username) {
            redirect("user/view/".$sess_username, "refresh");
            return;
        }

        // Get form data, username and password
        $username = $this->input->post("usernameInput");
        $password = $this->input->post("passwordInput");
        
        // sanitise username and password
        $san_username = htmlspecialchars(stripslashes(trim($username)));
        $san_password = htmlspecialchars(stripslashes(trim($password)));
        
        $error = false;
        $username_error_text = "";
        $password_error_text = "";

        // If either username or password is blank then set the appropriate
        // error message for when its passed to the placeholders in the view
        if (empty(str_replace(['"', "'", " "], "", $san_username))) {
            $username_error_text = "Username can't be left blank";
            $error = true;
        } 
        
        if (empty(str_replace(['"', "'", " "], "", $san_password))) {
            $password_error_text = "Password can't be left blank";
            $error = true;
        }

        // If the password and username are not blank, then call checkLogin from
        // the users model and see if the login data exists and is correct
        if (!$error) {
            $this->load->model("users_model");
            $data = $this->users_model->checkLogin($san_username, $san_password);
            
            // If the user doesn't exist or has the wrong data, then set the error messages
            // otherwise set the username session data and redirect to the logged in user's view page
            if (empty($data)) {
                $username_error_text = "Username is either incorrect or doesn't exist";
                $password_error_text = "Password is incorrect";
            } else {
                $this->session->set_userdata("username", $data[0]["username"]);
                redirect("user/view/".$data[0]["username"], "refresh");
                return;
            }
        }
    
        $viewdata = array(
            "username_val" => $san_username,
            "username_error_text" => $username_error_text,
            "password_error_text" => $password_error_text,
        );
        $this->load->view("login_page", $viewdata);
    }

    /*
    Only if the user is logged in can they logout and
    their session data is destroyed
    */
    public function logout() {
        if ($this->session->userdata("username")) {
            $this->session->sess_destroy();
            redirect("user/login/", "refresh");
        }
    }

    /*
    If the user is not logged in they can't follow another user,
    otherwise the users_model follow function is called 
    */
    public function follow($followed = -1) {
        $sess_username = $this->session->userdata("username");
        if (!$sess_username || $followed == -1) {
            redirect("user/login/", "refresh");
            return;
        }

        $san_followed = htmlspecialchars(stripslashes(trim($followed)));
        $this->load->model("users_model");
        $this->users_model->follow(
            $sess_username,
            $san_followed,
        );

        redirect("user/view/".$san_followed, "refresh");
    }

    /*
    If the user is not logged in they can't unfollow another user,
    otherwise the users_model unfollow function is called 
    */
    public function unfollow($followed = -1) {
        $sess_username = $this->session->userdata("username");
        if (!$sess_username || $followed == -1) {
            redirect("user/login/", "refresh");
            return;
        }

        $this->load->model("users_model");
        $san_followed = htmlspecialchars(stripslashes(trim($followed)));
        $this->users_model->unfollow(
            $sess_username,
            $san_followed,
        );

        redirect("user/view/".$san_followed, "refresh");
    }

    /*
    If a name is not provided then an error page is displayed, otherwise
    the specified user's feed is shown, which consists of all posts from
    all the user's they follow
    */
    public function feed($name = -1) {
        if ($name == -1) {
			$this->load->view("error_page", array("error_text" => "You can't view a feed with a blank name"));
		} else {
            $san_name = htmlspecialchars(stripslashes(trim($name)));

            $this->load->model("messages_model");
            $data = $this->messages_model->getFollowedMessages($san_name);

            $viewdata = array(
                "username" => $san_name,
                "results" => $data,
            );

            $this->load->view("messages_feed", $viewdata);
        }
        
    }
}
?>
