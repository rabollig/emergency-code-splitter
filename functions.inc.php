<?php

function generateRandomPassword(?int $length = 16) {
    $bytes = random_bytes($length / 1);
    $base58 = new StephenHill\Base58();
    return substr($base58->encode($bytes), 0, $length);
}


// Convert inputs into very normalized tokens so
// we can have hypen-delimited filenames. This does
// strip out characters that could be valid in file
// names, depending on the file system... but we're
// trying to maximize compatability and reduce the 
// change of the file being difficult to use because
// if the end-user's operating system
function sloppyNameConverter($input) {
  // Convert to underscores
  $input = str_replace(" ", "_" , $input);

  // Convert spaces to underscores
  $input = str_replace(" ", "_" , $input);

  // Remove all other unwanted characters
  $input = preg_replace('/[^a-z0-9]/', '', strtolower($input));

  return $input;
}



