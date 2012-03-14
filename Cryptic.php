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
	 * Path to the key storage file
	 * 
	 * @access public
	 * @var string
	 */

	public $keyStorageFile = "keys";

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
	 * Store keys in a file
	 *
	 * The keys will be stored as JSON.
	 *
	 * @access public
	 * @param string $key [, int $id]
	 * @return int|bool
	 */

	public function storeKeyInFile($key, $id = NULL) {

		// Be sure the given ID is a int
		if ($id && !is_int($id)) return FALSE;

		// Check if the key storage file is valid
		if (self::keyStorageFileIsValid()) {

			// Check if the key storage file already exists
			if (file_exists($this->keyStorageFile)) {

				// Get all existing keys as JSON and decode it to a array
				$keys = json_decode(@file_get_contents($this->keyStorageFile), TRUE);

				if (is_array($keys)) {

					// Calculate a ID for the key if none is given
					if (!$id) $id = count(array_keys($keys)) > 0 ? max(array_keys($keys))+1 : 1;

					// Add the new key to the keys array
					$keys[$id] = $key;

					// Sort the keys array by ID
					ksort($keys);

					// Encode the array as JSON and update the key storage file
					if (@file_put_contents($this->keyStorageFile, json_encode($keys)) !== FALSE) {

						return $id;
					}
				}

			} else {

				// Calculate a ID for the key if none is given
				if (!$id) $id = 1;

				// Create a new key storage file with a JSON encoded array
				if (@file_put_contents($this->keyStorageFile, json_encode(array($id => $key))) !== FALSE) {

					return $id;
				}
			}
		}

		return FALSE;
	}

	/**
	 * Get keys from a file
	 *
	 * @access public
	 * @param int $id
	 * @return string|bool
	 */

	public function keyFromFile($id) {

		// Be sure the given ID is a int
		if (!is_int($id)) return FALSE;

		// Check if the key storage file is valid
		if (self::keyStorageFileIsValid()) {

			// Get all existing keys as JSON and decode it to a array
			$keys = json_decode(@file_get_contents($this->keyStorageFile), TRUE);

			if (is_array($keys)) {

				// Return the key if one exists for the given ID
				if (isset($keys[$id])) return $keys[$id];
			}
		}

		return FALSE;
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

	/**
	 * Check if the key storage file is valid
	 *
	 * @access private
	 * @param void
	 * @return bool
	 */

	private function keyStorageFileIsValid() {

		if (isset($this->keyStorageFile) && is_string($this->keyStorageFile) && strlen($this->keyStorageFile) > 0) {

			return TRUE;
		}

		return FALSE;
	}
}
