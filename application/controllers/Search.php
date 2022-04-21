<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

    /*
    Shows a simple search bar as the search_messages is the loaded view
    */
    public function index() {
        $viewdata = array(
            "search_term" => "",
            "error_text" => "",
            "results" => array(),
            "results_title" => "",
        );
        $this->load->view("search_messages", $viewdata);
    }

    /*
    Loads the search view again with any received messages from all users in the DB
    that contain the search term
    */
	public function doSearch() {
        // Search term is retrieved from form and sanitised
        $search_term = $this->input->get("searchInput");
        $sanitised_input = trim($search_term);
        $sanitised_input = stripslashes($sanitised_input);
        $sanitised_input = htmlspecialchars($sanitised_input);

        // If the search term is blank then the view will be loaded with an error message
        if (empty(str_replace(['"', "'", " "], "", $sanitised_input))) {        
            $viewdata = array(
                "search_term" => $sanitised_input,
                "error_text" => "Search term can't be left blank",
                "results" => array(),
                "results_title" => "",
            );
        } else {
            // Otherwise searchMessages with be called with the sanitised search term
            // passed in and it will provide the $viewdata array with the searched 
            // results to display
            $this->load->model("messages_model");
            $data = $this->messages_model->searchMessages($sanitised_input);
            
            $viewdata = array(
                "search_term" => $sanitised_input,
                "error_text" => "",
                "results" => $data,
                "results_title" => "Search Results:",
            );
        }

        $this->load->view("search_messages", $viewdata);
        
	}
}
?>
