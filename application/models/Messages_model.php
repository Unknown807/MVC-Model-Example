<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	/*
	Returns an array of all the messages posted by the user
	specified by $name
	*/
	public function getMessagesByPoster($name) {
		$sql = "SELECT user_username, text, posted_at FROM Messages WHERE user_username = ? ORDER BY posted_at DESC";
		$query = $this->db->query($sql, array($name));
		return $query->result_array();
	}

	/*
	Returns all messages from all users that include the exact term $string
	*/
	public function searchMessages($string) {
		$sql = "SELECT user_username, text, posted_at FROM Messages WHERE text LIKE CONCAT('%', ? '%') ";
		$query = $this->db->query($sql, array($string));
		return $query->result_array();
	}

	/*
	By specifying which user wants to post a new message, $poster, it inserts
	the new message, $string, in the Messages table
	*/
	public function insertMessage($poster, $string) {
		$sql = "INSERT INTO Messages (user_username, text, posted_at) VALUES ( ? , ? , NOW())";
		$query = $this->db->query($sql, array($poster, $string));
		return $query;
	}

	/*
	Returns all messages posted by every user which the user specified in $name
	follows
	*/
	public function getFollowedMessages($name) {
		$sql = "SELECT user_username, text, posted_at FROM Messages WHERE user_username IN (
		SELECT followed_username FROM User_Follows WHERE follower_username = ? )";
		$query = $this->db->query($sql, array($name));
		return $query->result_array();
	}
}
?>