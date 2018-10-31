<?php

/**
 * Starts a session if not started.
 * @return boolean
 */
function sessionSESSION()
{
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
		return true;
	}
	return false;
}

/**
 * Delete the current session.
 */
function deleteSESSION()
{
	sessionSESSION();
	session_destroy();
}

?>
