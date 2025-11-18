KeyValue
===========

KeyValue is a small utility that provides a simple, readable interface for managing associative arrays as a collection of key-value pairs.

It handles common but often complex operations like confirming membership and overwriting nested values in multi-dimensional arrays. Like so:

```php

$collection = new KeyValue();
$collection->set( 'id', 'bffc4d08-1750-41a7-9fdd-345187eb9ff2' );

echo $collection->get( 'id' ); // 'bffc4d08-1750-41a7-9fdd-345187eb9ff2'

```

KeyValue is one of a number of tools I'm hoping to extract from older, larger projects.

## Installation

KeyValue has not yet been added to Packagist. Stay tuned.

Local installation is possible in the meantime, using the `repositories` block of your composer.json. For instance:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./packages/justinskolnick/key-value",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
        "justinskolnick/key-value": "*"
    }
}
```

## Usage

KeyValue collections are associative arrays, with mixed-type values saved at keys. Keys must be strings:

```php
$id = $collection->get( 'id' );
```

If a KeyValue item's value is an array, the key can include a slash to access a value at the second level of the array:

```php
$timestamp = $collection->get( 'created/timestamp' );
```

To get started, initiate a new KeyValue collection:

```php
$collection = new KeyValue();
```

A collection can also be initiated with values:

```php
$collection = new KeyValue(
  [
    'id'            => 'bffc4d08-1750-41a7-9fdd-345187eb9ff2',
    'phone_number'  => 8675309,
    'was_saved'     => false,
  ]
);
```

Set a value or many values:

```php
$collection->set( 'id', 'bffc4d08-1750-41a7-9fdd-345187eb9ff2' );
$collection->setMany(
  [
    'id'            => 'bffc4d08-1750-41a7-9fdd-345187eb9ff2',
    'phone_number'  => 8675309,
    'was_saved'     => false,
    'created'   => [
      'readable'  => 'November 7, 2025',
      'timestamp' => '2025-11-07 01:23:45',
    ],
  ]
);
```

Confirm that any values have been set:

```php
if ( $collection->hasAny() ) {
  return true;
}
```

Confirm that a key exists, or that a value has been set at a given key:

```php
if ( $collection->has( 'id' ) ) {
  return true;
}

if ( $collection->hasValueAt( 'id' ) ) {
  return true;
}
```

Get a value for a given key:

```php
$id = $collection->get( 'id' );
```

Get all values for all keys, or only the keys, or only the values:

```php
$all = $collection->getAll();
$keys = $collection->getKeys();
$values = $collection->getValues();
```

Remove an item (and confirm the deletion):

```php
if ( $collection->delete( 'id' ) ) {
  return true;
}
```

Reset the collection to null (and confirm the reset):

```php
if ( $collection->reset() ) {
  return true;
}
```

Confirm the collection is empty:

```php
if ( $collection->isEmpty() ) {
  return true;
}
```

Confirm the collection is a non-null empty array:

```php
$collection = new KeyValue( [] );

if ( $collection->isEmpty( true ) ) {
  return true;
}
```

Confirm the collection is null (such as on init or after a reset):

```php
if ( $collection->isNull() ) {
  return true;
}
```

## Style

Readers will note that the code, tests, and examples in this README don't meet the requirements of PSR-2 or PSR-12. Instead, they follow conventions I adopted a long time ago to help me scan PHP code. While this personal style is expected to be followed in PRs on this repository, it's not a requirement of use.

## License

Copyright (c) 2025 Justin Skolnick. [MIT License](/LICENSE).
