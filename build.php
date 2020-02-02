<?php
require "vendor/autoload.php";
require "functions.inc.php";

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

// Get configuration
try {
    $config = (object)Yaml::parseFile('config.yaml');
} catch (PraseException $exception) {
    printf('Unable to parse config file: %s', $exception->getMessage());
    exit;
}

// Make all possible chains of trusted individuals
$allPermutations = [];

$generator = new \Lunkkun\PermutationsGenerator\PermutationsGenerator($config->trustedIndividuals);

foreach ($generator as $permutation) {
    $allPermutations[] = ($permutation);
}

// Cut the permutations to just N levels
$truncatedPermutations = [];

foreach ($allPermutations as $thisPermutation) {
    // array_unique doens't work on multidimensional arrays,
    // so we'll serialize to a string as a hack.
    $truncatedPermutations[] = serialize(
        array_slice(
            $thisPermutation,
            0,
            $config->requiredPasswords
        )
    );
}

// Remove duplicates
$serializedChains = array_unique($truncatedPermutations);


// Generate passwords for all trusted users.
$passwords = array();
foreach ($config->trustedIndividuals as $name) {
    $passwords[$name] = generateRandomPassword($config->passwordLength);
}

// make some temp space to work in
if (!file_exists('temp')) {
    mkdir('temp');
}


// make output location
if (!file_exists('output')) {
    mkdir('output');
}

// if the master file doesn't exist, make it
if (!file_exists("{$config->masterFilename}")) {
    die("The configured masterFilename doesn't exist." . PHP_EOL);
}

$n = 1;
foreach ($serializedChains as $serializedChain) {
    $chain = array_reverse(unserialize($serializedChain));

    // Delete everything in the temp directory, except the master file
    exec("rm -R temp/*");

    $lastItem = $config->masterFilename;
    $nameChain = array();

    foreach ($chain as $trustedIndividual) {

        $filename = sloppyNameConverter($trustedIndividual);
        $nameChain[] = $filename;
        $password = $passwords[$trustedIndividual];

        // Create zip file. -m = move, -j = exclude path
        exec("zip --encrypt -P {$password} -j 'temp/{$filename}.zip' $lastItem");
        $lastItem = "temp/" . $filename . ".zip";
    }

    $finalFilename = $n . "-" . implode("-", array_reverse($nameChain));

    // Move last file to output
    exec("mv temp/{$filename}.zip output/{$finalFilename}.zip");
    $n++;
    echo ".";
}

echo PHP_EOL;

echo "Your nested encryption files are in the output directory.";

echo PHP_EOL;
echo PHP_EOL;

echo "The passwords are:";
echo PHP_EOL;

foreach ($passwords as $name => $password) {
    echo "{$name}: {$password}" . PHP_EOL;
}

echo PHP_EOL;
