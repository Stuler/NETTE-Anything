<?php

/** Připojení do db bez hesla v rámci localhostu
 * @link https://www.adminer.org/plugins/#use
 * @author Jakub Vrana, https://www.vrana.cz/
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 */
class AdminerLoginPasswordLess {

	/** Set allowed password
	 * @param string result of password_hash
	 */
	function __construct() {
	}

	function credentials() {
		$password = get_password();
		return [SERVER, $_GET["username"], $password];
	}

	function login($login, $password) {
		return true;
	}

}