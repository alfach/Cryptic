<pre>
<?php

include "Cryptic.php";
$Cryptic = new Cryptic;

var_dump($Cryptic);

echo "\n================================================================================\n\n";
echo "generateKey()\n\n";

$key = $Cryptic->generateKey();

var_dump($key);

echo "\n================================================================================\n\n";
echo "encrypt(\"foobar\")\n\n";

$encrypted = $Cryptic->encrypt("foobar");

print_r($encrypted);

echo "\n================================================================================\n\n";
echo "encrypt(\"foobar\", \"barfoo\");\n\n";

$encrypted = $Cryptic->encrypt("foobar", "barfoo");

print_r($encrypted);

echo "\n================================================================================\n\n";
echo "decrypt(\$encrypted[\"data\"], \$encrypted[\"key\"])\n\n";

$encrypted = $Cryptic->encrypt("foobar");
$decrypted = $Cryptic->decrypt($encrypted["data"], $encrypted["key"]);

var_dump($decrypted);

echo "\n================================================================================\n\n";
echo "storeKeyInFile(generateKey())\n\n";

$return = $Cryptic->storeKeyInFile($Cryptic->generateKey());

var_dump($return);

echo "\n================================================================================\n\n";
echo "storeKeyInFile(generateKey(), 42)\n\n";

$return = $Cryptic->storeKeyInFile($Cryptic->generateKey(), 42);

var_dump($return);

echo "\n================================================================================\n\n";
echo "storeKeyInFile(generateKey(), rand(1, 999))\n\n";

$return = $Cryptic->storeKeyInFile($Cryptic->generateKey(), rand(1, 999));

var_dump($return);

echo "\n================================================================================\n\n";
echo "keyFromFile(42)\n\n";

$return = $Cryptic->keyFromFile(42);

var_dump($return);

?>
</pre>
