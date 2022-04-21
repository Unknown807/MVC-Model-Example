<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Controller {

    /*
    If the user is not logged in they can't post a message, so a redirect is issued to the
    login page, otherwise load the login view
    */
    public function index() {
        if (!$this->session->userdata("username")) {
            redirect("user/login/", "refresh");
            return;
        }

        $viewdata = array(
            "error_text" => "",
        );

        $this->load->view("post_messages", $viewdata);
    }

    /*
    If the user is not logged in, then they can't send a message and are
    redirected to the login page, otherwise the new message will be posted
    */
    public function doPost() {
        $sess_username = $this->session->userdata("username");
        $error_text = "";

        if (!$sess_username) {
            redirect("user/login/", "refresh");
            return;
        }

        // the textarea value (message) is retrieved and sanitised
        $post_text = $this->input->post("postInput");
        $san_post_text = htmlspecialchars(stripslashes(trim($post_text)));

        // If the message content is totally blank then the string in error_text
        // will be displayed under the text area after the post_messages view is
        // loaded again
        if (empty(str_replace(['"', "'", " "], "", $san_post_text))) {
            $error_text = "Post can't be left blank";
        } else {
            // If something goes wrong and the $data variable is empty
            // then the post_messages view will be loaded with this error
            // under the textarea
            $error_text = "Something went wrong, please try again later";
            
            $this->load->model("messages_model");
            $data = $this->messages_model->insertMessage(
                $sess_username,
                $san_post_text
            );

            // If inserting the user's new message was a success, then
            // it will redirect to the user's view page to see the latest
            // message
            if ($data) {
                redirect("user/view/".$sess_username, "refresh");
                return;
            }
        }

        $viewdata = array(
            "error_text" => $error_text,
        );

        $this->load->view("post_messages", $viewdata);
    }
}
?>
