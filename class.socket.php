<?php
/**
 * 
 * Generic socket interface class to create a socket connection to a server.
 * 
 * @author nrekow
 *
 */

class socket {
	private $socket = null;
	private $error = null;
	private $protocol = 'tls://';
	private $hostname = '';
	private $port = null;
	private $timeout = 120; // seconds
	private $errno = null;
	private $errstr = '';
	
	
	public function __construct($protocol, $hostname, $port, $timeout) {
		if (!empty($hostname) && $port > 0 && $port < 65536 && $timeout >= 0) {
			$this->protocol = $protocol;
			$this->hostname = $hostname;
			$this->port = $port;
			$this->timeout = $timeout;
		} else {
			$this->error = "Cannot initialize socket class due to missing parameters.";
			return false;
		}
	}// END: __constuct()
	
	
	public function getError() {
		return $this->error;
	}// END: getError()
	
	
	public function open() {
		if ($this->socket = @fsockopen($this->protocol . $this->hostname, $this->port, $this->errno, $this->errstr, $this->timeout)) {
			socket_set_blocking($this->socket, 0);
			socket_set_timeout($this->socket, $this->timeout * 1000000); // microseconds
			return true;
		} else {
			$this->error = "{$this->errstr} (#{$this->errno}, " . __FILE__ . ", " . __LINE__ . ")";
			return false;
		}
	}// END: open()
	

	public function close() {
		return fclose($this->socket);
	}// END: close()
	

	public function write($data) {
		return fwrite($this->socket, $data);
	}// END: write()

	
	public function read($byte_count) {
		$buffer = fread($this->socket, $byte_count);
		return $buffer;
	}// END: read()
}// END: socket class
