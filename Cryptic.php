<?php

/**
 * Cryptic
 *
 * @version 1.0
 * @link https://github.com/betawax/Cryptic
 *
 * @author Holger Weis <holger.weis@gmail.com>
 * @copyright Copyright (c) 2012 Holger Weis
 * @license The MIT License http://www.opensource.org/licenses/mit-license.php
 */

class Cryptic {

	/**
	 * Generate a random key
	 *
	 * @access public
	 * @param void
	 * @return string
	 */

	public function generateKey() {

		return substr(hash("sha256", sha1(microtime(TRUE).mt_rand(10000, 90000))), 0, mcrypt_get_key_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB));
	}

	/**
	 * Encrypt
	 *
	 * @access public
	 * @param string $data [, string $key]
	 * @return array
	 */

	public function encrypt($data, $key = NULL) {

		if (!$key) $key = self::generateKey();

		$encryptedData = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));

		return array("data" => $encryptedData, "key" => $key);
	}

	/**
	 * Decrypt
	 *
	 * @access public
	 * @param string $encryptedData, string $key
	 * @return string
	 */

	public function decrypt($encryptedData, $key) {

		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($encryptedData), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)), "\0");
	}
}
