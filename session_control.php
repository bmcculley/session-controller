<?php
/**
* A PHP session controller to keep session data within a database.
*
* @author 	Blake <zen.fed96@gmail.com>
* @link		https://github.com/bmcculley/session-controller
*/

class SessionControl {

	protected $con;
	protected $table = 'sessioncontrol';

	/**
     * Initialize
     */
	function SessionControl() {
		session_start();
		include_once('config.php');
		$this->db_connect($host, $user, $pass, $db);
	}

	/**
     * Start a new connection
     */
	private function db_connect($host, $user, $pass, $db) {
		$this->con = mysqli_connect($host, $user, $pass, $db);
	}

	/**
     * Create a new session
     */
	function start_session($ip) {
		$vid = md5($ip);
		if ($this->store_session($vid, $ip)) {
			setcookie('sess', $vid);
		}
	}

	/**
     * Store new session in database
     */
	private function store_session($vid, $ip) {
		$sql = sprintf("REPLACE INTO %s VALUES('%s', '%s', '%s', '%s')",
        			   $this->table,
        			   '',
                       $this->con->escape_string($vid),
                       $this->con->escape_string($ip),
                       time());
        return $this->con->query($sql);
	}

	/**
     * Determine if visitor has a current session
     */
	function get_session($vid,$ip) {
		$sql = sprintf("SELECT * FROM %s WHERE visitor_id = '%s' AND ip_address = '%s'", $this->table, $this->con->escape_string($vid), $this->con->escape_string($ip));
        if ($result = $this->con->query($sql)) {
        	return true;
        }
        else {
        	return false;
        }
	}

	/**
     * Update session timestamp on visitor activity
     */
	function update_activity($vid,$ip) {
		$sql = sprintf("UPDATE %s SET timestamp = %s WHERE visitor_id = '%s' AND ip_address = '%s'", $this->table, time(), $this->con->escape_string($vid), $this->con->escape_string($ip));
		if ($result = $this->con->query($sql)) {
        	return true;
        }
        else {
        	return false;
        }
	}

	/**
     * Destroy a current session by visitor id
     */
	function clear_session($vid) {
		$sql = sprintf("DELETE FROM %s WHERE `visitor_id` = '%s'", $this->table, $this->con->escape_string($vid));
		unset($_COOKIE['sess']);
        return $this->con->query($sql);
	}

	/**
     * Remove old sessions
     * Use a cron job to clean out old sessions
     */
	function clear_old() {
		$limit = time() - (3600 * 24);
        $sql = sprintf("DELETE FROM %s WHERE timestamp < %s", $this->table, $limit);
        return $this->con->query($sql);
	}
}
?>