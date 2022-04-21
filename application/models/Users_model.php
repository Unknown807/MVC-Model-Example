<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	/*
	If the returned array has a row, then the user exists and their username and
	password matches, so the site should allow the login
	*/
	public function checkLogin($username, $pass) {
        $sql = "SELECT username FROM Users WHERE username = ? AND password = ?";
		$query = $this->db->query($sql, array($username, sha1($pass)));
        return $query->result_array();
	}

	/*
	If the result array is not empty, then the user, $follower, is already
	following the other user, $followed
	*/
	public function isFollowing($follower, $followed) {
		$sql = "SELECT * FROM User_Follows WHERE follower_username = ? AND followed_username = ?";
		$query = $this->db->query($sql, array($follower, $followed));
		return !empty($query->result_array());
	}

	/*
	Will return false and not insert the follow relationship in the User_Follows table if
	the user, $follower, already follows the other specified user, $followed or the user
	tries to follow themselves
	*/
	public function follow($follower, $followed) {
		if (!$this->isFollowing($follower, $follower) && $follower == $followed) {
			return false;
		}

		$sql = "INSERT INTO User_Follows (follower_username, followed_username) VALUES ( ? , ? )";
		$query = $this->db->query($sql, array($follower, $followed));
		return $query;
	}

	/*
	Will return false and not delete the following relationship from the User_Follows table
	if the user, $follower, is already not a follower of $followed
	*/
	public function unfollow($follower, $followed) {
		if ($this->isFollowing($follower, $follower)) {
			return false;
		}

		$sql = "DELETE FROM User_Follows WHERE follower_username = ? AND followed_username = ?";
		$query = $this->db->query($sql, array($follower, $followed));
		return $query;
	}
}
?>