<?php

namespace JustinSkolnick;

class KeyValue {
  private ?array $values = null;
  private ?array $initialValues = null;

  /**
   * Constructs the object
   * 
   * @param array $values
   */
  public function __construct( ?array $values = null ) {
    if ( !is_null( $values ) ) {
      $this->values = $values;
      $this->initialValues = $values;
    }
  }

  /**
   * Parse a key
   * 
   * @param string $key
   * 
   * @return mixed
   */
  private function parse_key( string $key ): mixed {
    if ( str_contains( $key, '/' ) ) {
      list( $key_set, $key_item ) = explode( '/', $key );

      return (object) [
        'set'   => $key_set,
        'item'  => $key_item,
      ];
    } else {
      return $key;
    }
  }

  /**
   * Return true if the key has been parsed by $this->parse_key()
   * 
   * @param string|object $key
   * 
   * @return bool
   */
  private function is_key_set( string|object $key ): bool {
    return is_object( $key ) && property_exists( $key, 'set' ) && property_exists( $key, 'item' );
  }

  /**
   * Return true if the given key exists on $values
   * 
   * @param string $keyString
   * 
   * @return bool
   */
  final public function has( string $keyString ): bool {
    $key = $this->parse_key( $keyString );

    if ( !$this->hasAny() ) {
      return false;
    }

    if ( $this->is_key_set( $key ) ) {
      if ( $this->has( $key->set ) ) {
        return array_key_exists( $key->item, $this->values[ $key->set ] );
      } else {
        return false;
      }
    } else {
      return array_key_exists( $key, $this->values );
    }
  }

  /**
   * Return true if the given key has a value on $values
   * 
   * @param string $key
   * 
   * @return bool
   */
  final public function hasValueAt( string $key ): bool {
    if ( $this->isEmpty() ) {
      return false;
    }

    return !is_null( $this->get( $key ) );
  }

  /**
   * Return true if $values has been set
   * 
   * @return bool
   */
  final public function hasAny(): bool {
    return !$this->isEmpty( true ) && !$this->isNull();
  }

  /**
   * Return true if $values is empty
   * 
   * @return bool
   */
  final public function isEmpty( ?bool $strict = false ): bool {
    if ( $strict ) {
      return !is_null( $this->values ) && empty( $this->values );
    } else {
      return empty( $this->values );
    }
  }

  /**
   * Return true if $values is null
   * 
   * @return bool
   */
  final public function isNull(): bool {
    return is_null( $this->values );
  }

  /**
   * Get the value at a given key
   * 
   * @param string $key
   * 
   * @return mixed
   */
  final public function get( ?string $keyString = null ): mixed {
    if ( !is_null( $keyString ) && $this->has( $keyString ) ) {
      $key = $this->parse_key( $keyString );

      if ( $this->is_key_set( $key ) ) {
        $set = (array) $this->values[ $key->set ];

        return $set[ $key->item ];
      } else {
        return $this->values[ $key ];
      }
    } else {
      return null;
    }
  }

  /**
   * Get all values as an array
   * 
   * @return array
   */
  final public function getAll(): ?array {
    return $this->values;
  }

  /**
   * Get all keys as an array
   * 
   * @return array
   */
  final public function getKeys(): ?array {
    if ( $this->hasAny() ) {
      return array_keys( $this->values );
    }

    return null;
  }

  /**
   * Get all values as an array
   * 
   * @return array
   */
  final public function getValues(): ?array {
    if ( $this->hasAny() ) {
      return array_values( $this->values );
    }

    return null;
  }

  /**
   * Set the value for the given key
   * 
   * @param string $keyString
   * @param mixed $value
   * 
   * @return void
   */
  final public function set( string $keyString, mixed $value ): void {
    $key = $this->parse_key( $keyString );

    if ( $this->is_key_set( $key ) ) {
      if ( !$this->has( $key->set ) ) {
        $this->values[ $key->set ] = [];
      }

      $this->values[ $key->set ][ $key->item ] = $value;
    } else {
      $this->values[ $key ] = $value;
    }
  }

  /**
   * Set multiple values
   * 
   * @param array $values
   * 
   * @return void
   */
  final public function setMany( ?array $values ): void {
    if ( !is_null( $values ) && !empty( $values ) ) {
      foreach( $values as $key => $value ) {
        $this->set( $key, $value );
      }
    }
  }

  /**
   * Delete and unset the value at the given key
   * Return true if the operation was successful
   * 
   * @param string $keyString
   * 
   * @return bool
   */
  final public function delete( string $keyString ): bool {
    if ( $this->has( $keyString ) ) {
      $key = $this->parse_key( $keyString );

      if ( $this->is_key_set( $key ) ) {
        unset( $this->values[ $key->set ][ $key->item ] );
      } else {
        unset( $this->values[ $key ] );
      }

      return !$this->has( $keyString );
    }

    return false;
  }

  /**
   * Reset the values to the initial values or null
   * Return true if the operation was successful
   * 
   * @param bool $nullify
   * 
   * @return bool
   */
  final public function reset( ?bool $nullify = false ): bool {
    if ( $nullify ) {
      $this->values = null;

      return $this->isNull();
    } else {
      $this->values = $this->initialValues;

      return $this->values === $this->initialValues;
    }
  }
}
