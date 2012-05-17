# Cryptic

Cryptic is a PHP class to encrypt/decrypt data with keys, using the PHP Mcrypt Extension. It takes the hassle out of dealing directly with `mcrypt_encrypt()` & `mcrypt_decrypt()` and offers some additional features:

- easily encrypt & decrypt data
- generate random encryption keys
- store and manage keys

Take a look at the following code examples to get started.

## Usage

### Basic usage

#### Initialization

	include_once "Cryptic.php";
	$Cryptic = new Cryptic;

#### Encrypt

Encrypt a string and let Cryptic automatically generate a random key for you:

	$encrypted = $Cryptic->encrypt("foobar");

The return value (`$encrypted`) will be an array:

	Array
	(
		[data] => roPRNHVMnANJwiAI+4u5f2q2WEnz5QVg6XK+HI2x9ik=
		[key] => c7ae6ea5aa885b1e94f69214534ea95b
	)

`$encrypted["data"]` holds the encrypted data as a Base64-encoded string. For example, you can store this in a database table using a BLOB field.

`$encrypted["key"]` is the random generated key that was used to encrypt the data. You want to store this separated from the encrypted data, e.g. in a file (see "Manage keys" section below).

#### Decrypt

Decrypt the string with the generated key:

	$decrypted = $Cryptic->decrypt($encrypted["data"], $encrypted["key"]);

The return value (`$decrypted`) will be the original string:

	string(6) "foobar"

### Advanced usage

#### Encrypt with a custom key

You can also encrypt with a custom key:

	$encrypted = $Cryptic->encrypt("foobar", "key");

#### Encrypt an array

If you want to encrypt an array, just use `serialize()` before encryption:

	$array = serialize(array("foo", "bar"));
	$encrypted = $Cryptic->encrypt($array);

And after decryption, `unserialize()` to get your original array back:

	$decrypted = $Cryptic->decrypt($encrypted["data"], $encrypted["key"]);
	$array = unserialize($decrypted);

#### Generate a random key

If you just want to generate a random key:

	$key = $Cryptic->generateKey();

The return value (`$key`) will be a 32 digit random key:

	string(32) "eba4b9cc79cd431030ce72f3701c4132"

### Manage keys

If you want, Cryptic can even manage your keys for you. All keys are stored in a single file, identified by an ID and encoded as JSON.

#### Configuration

Define the path to the key storage file:

	$Cryptic->keyStorageFile = "/path/to/keys";

#### Store a key

Store a key and let Cryptic automatically generate a ID for you:

	$id = $Cryptic->storeKeyInFile($encrypted["key"]);

The return value (`$id`) will be the generated ID of the key:

	int(42)

You want to store the ID of the key together with the encrypted data.

#### Get a key

To get a key back, simply pass the ID of the key:

	$key = $Cryptic->keyFromFile($id);

The return value (`$key`) will be the original key string:

	string(32) "c7ae6ea5aa885b1e94f69214534ea95b"

#### Store a key with a custom ID

Of course you can also use a custom ID (e.g. a user-ID) to store a key:

	$Cryptic->storeKeyInFile($encrypted["key"], 42);

Note: specifying an ID that already exists will overwrite the appropriate key.

## FAQ

#### How is the data encrypted?

The data is encrypted using `Mcrypt` and the `MCRYPT_RIJNDAEL_256` algorithm.

#### How to safely store and access the key storage file?

Cryptic does not provide a technique for addressing the issue how to safely store and access the key storage file. As this depends on your infrastructure, you will have to do it by yourself.

## Requirements

Cryptic has the following requirements:

- [libmcrypt 2.4 or later](http://mcrypt.sourceforge.net/)
- [PHP Mcrypt Extension](http://www.php.net/manual/en/book.mcrypt.php)
- PHP 5.2 or later

## License

Licensed under the MIT license.
