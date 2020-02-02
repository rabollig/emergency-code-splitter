# PHP Permutations Generator

## Summary

A PHP Generator that generates all possible permutations for a given array of values.

### Important note

I've optimized the code for performance. Therefore, the order in which the permutations are generated, might differ from what you might expect. To get the permutations in ascending order, you can `reverse()` every result you get from the generator.

## Installation

With composer:

```bash
composer require lunkkun/permutations-generator
```

## Usage

```php
<?php

$array = range(0, 2);
$generator = new \Lunkkun\PermutationsGenerator\PermutationsGenerator($array);

foreach ($generator as $permutation) {
    print_r($permutation);
}

```

## License

PHP Permutations Generator is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
