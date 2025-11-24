<?php

declare( strict_types = 1 );

use JustinSkolnick\KeyValue;
use PHPUnit\Framework\TestCase;

class KeyValueTest extends TestCase
{
  public function testHas(): void {
    $values = [
      'id'        => 1,
      'title'     => 'A Short Story',
      'is_active' => false,
      'created'   => [
        'timestamp' => '2025-11-07 01:23:45',
      ],
    ];

    $null_collection = new KeyValue();
    $collection = new KeyValue( $values );

    $this->assertFalse( $null_collection->has( 'id' ) );

    $this->assertTrue( $collection->has( 'id' ) );
    $this->assertTrue( $collection->has( 'title' ) );
    $this->assertTrue( $collection->has( 'is_active' ) );
    $this->assertTrue( $collection->has( 'created' ) );
    $this->assertTrue( $collection->has( 'created/timestamp' ) );
    $this->assertFalse( $collection->has( 'second' ) );
  }

  public function testHasValueAt(): void {
    $values = [
      'id'        => 1,
      'title'     => 'A Short Story',
      'is_active' => false,
      'bad'       => null,
      'created'   => [
        'timestamp' => '2025-11-07 01:23:45',
      ],
    ];

    $collection = new KeyValue( $values );

    $this->assertTrue( $collection->hasValueAt( 'id' ) );
    $this->assertTrue( $collection->hasValueAt( 'title' ) );
    $this->assertTrue( $collection->hasValueAt( 'is_active' ) );
    $this->assertTrue( $collection->hasValueAt( 'created/timestamp' ) );
    $this->assertFalse( $collection->hasValueAt( 'bad' ) );
    $this->assertFalse( $collection->hasValueAt( 'second' ) );
  }

  public function testHasAny(): void {
    $values = [
      'id'        => 1,
      'title'     => 'A Short Story',
      'is_active' => false,
    ];

    $null_collection = new KeyValue();
    $empty_collection = new KeyValue( [] );
    $collection = new KeyValue( $values );

    $this->assertFalse( $null_collection->hasAny() );
    $this->assertFalse( $empty_collection->hasAny() );
    $this->assertTrue( $collection->hasAny() );
  }

  public function testIsEmpty(): void {
    $null_collection = new KeyValue();
    $empty_collection = new KeyValue( [] );

    $this->assertTrue( $null_collection->isEmpty() );
    $this->assertFalse( $null_collection->isEmpty( true ) );
    $this->assertTrue( $empty_collection->isEmpty() );
    $this->assertTrue( $empty_collection->isEmpty( true ) );
  }

  public function testIsNull(): void {
    $null_collection = new KeyValue();
    $empty_collection = new KeyValue( [] );

    $this->assertTrue( $null_collection->isNull() );
    $this->assertFalse( $empty_collection->isNull() );
  }

  public function testGet(): void {
    $values = [
      'id'        => 1,
      'title'     => 'A Short Story',
      'is_active' => false,
      'created'   => [
        'timestamp' => '2025-11-07 01:23:45',
      ],
    ];

    $collection = new KeyValue( $values );

    $this->assertEquals( $collection->get( 'id' ), 1 );
    $this->assertEquals( $collection->get( 'title' ), 'A Short Story' );
    $this->assertEquals( $collection->get( 'is_active' ), false );
    $this->assertEquals( $collection->get( 'created' ), [ 'timestamp' => '2025-11-07 01:23:45' ] );
    $this->assertEquals( $collection->get( 'created/timestamp' ), '2025-11-07 01:23:45' );
    $this->assertEquals( $collection->get(), null );
  }

  public function testGetAll(): void {
    $values = [
      'id'            => 'bffc4d08-1750-41a7-9fdd-345187eb9ff2',
      'phone_number'  => 8675309,
      'was_saved'     => false,
      'created'   => [
        'timestamp' => '2025-11-07 01:23:45',
      ],
    ];

    $null_collection = new KeyValue();
    $collection = new KeyValue( $values );

    $this->assertEquals( $null_collection->getAll(), null );
    $this->assertEquals( $collection->getAll(), $values );
  }

  public function testGetKeys(): void {
    $values = [
      'id'            => 'bffc4d08-1750-41a7-9fdd-345187eb9ff2',
      'phone_number'  => 8675309,
      'was_saved'     => false,
      'created'       => [
        'timestamp'     => '2025-11-07 01:23:45',
      ],
    ];

    $null_collection = new KeyValue();
    $collection = new KeyValue( $values );

    $this->assertEquals( $null_collection->getKeys(), null );
    $this->assertEquals( $collection->getKeys(), array_keys( $values ) );
  }

  public function testGetValues(): void {
    $values = [
      'id'            => 'bffc4d08-1750-41a7-9fdd-345187eb9ff2',
      'phone_number'  => 8675309,
      'was_saved'     => false,
      'created'       => [
        'timestamp'     => '2025-11-07 01:23:45',
      ],
    ];

    $null_collection = new KeyValue();
    $collection = new KeyValue( $values );

    $this->assertEquals( $null_collection->getValues(), null );
    $this->assertEquals( $collection->getValues(), array_values( $values ) );
  }

  public function testInitiatesWithValues(): void {
    $values = [
      'id'            => 'bffc4d08-1750-41a7-9fdd-345187eb9ff2',
      'phone_number'  => 8675309,
      'was_saved'     => false,
    ];

    $collection = new KeyValue( $values );

    $this->assertEquals( $collection->getAll(), $values );
  }

  public function testOverwritesInitialValues(): void {
    $values = [
      'id'            => 'bffc4d08-1750-41a7-9fdd-345187eb9ff2',
      'phone_number'  => 8675309,
      'was_saved'     => false,
    ];

    $collection = new KeyValue( $values );

    $collection->set( 'id', '7ebe8ea1-a629-436c-8fa9-5c511ba1c57c' );

    $this->assertNotEquals( $collection->get( 'id' ), $values['id'] );
    $this->assertEquals( $collection->get( 'phone_number' ), $values['phone_number'] );
    $this->assertEquals( $collection->get( 'was_saved' ), $values['was_saved'] );
  }

  public function testSetsAStringValue(): void {
    $collection = new KeyValue();
    $value = 'bffc4d08-1750-41a7-9fdd-345187eb9ff2';

    $collection->set( 'id', $value );

    $this->assertTrue( $collection->has( 'id' ) );
    $this->assertEquals( $collection->get( 'id' ), $value );
  }

  public function testSetsAnInteger(): void {
    $collection = new KeyValue();
    $value = 8675309;

    $collection->set( 'phone_number', $value );

    $this->assertTrue( $collection->has( 'phone_number' ) );
    $this->assertEquals( $collection->get( 'phone_number' ), $value );
  }

  public function testSetsABooleanValue(): void {
    $collection = new KeyValue();
    $value = false;

    $collection->set( 'was_saved', $value );

    $this->assertTrue( $collection->has( 'was_saved' ) );
    $this->assertEquals( $collection->get( 'was_saved' ), $value );
  }

  public function testSetsANullValue(): void {
    $collection = new KeyValue();
    $value = null;

    $collection->set( 'tbd', $value );

    $this->assertTrue( $collection->has( 'tbd' ) );
    $this->assertEquals( $collection->get( 'tbd' ), $value );
  }

  public function testSetsAnArrayValue(): void {
    $collection = new KeyValue();
    $value = [
      'one',
      'two',
      'three',
    ];

    $collection->set( 'number_array', $value );

    $this->assertTrue( $collection->has( 'number_array' ) );
    $this->assertEquals( $collection->get( 'number_array' ), $value );
  }

  public function testSetsAnObjectValue(): void {
    $collection = new KeyValue();
    $value = (object) [
      'one' => 1,
      'two' => 2,
      'three' => 3,
    ];

    $collection->set( 'number_object', $value );

    $this->assertTrue( $collection->has( 'number_object' ) );
    $this->assertEquals( $collection->get( 'number_object' ), $value );
  }

  public function testSetsANestedKey(): void {
    $collection = new KeyValue();
    $readable = 'November 7, 2025';
    $timestamp = '2025-11-07 01:23:45';

    $collection->set( 'created/readable', $readable );
    $collection->set( 'created/timestamp', $timestamp );

    $this->assertTrue( $collection->has( 'created/readable' ) );
    $this->assertEquals( $collection->get( 'created/readable' ), $readable );
    $this->assertTrue( $collection->has( 'created/timestamp' ) );
    $this->assertEquals( $collection->get( 'created/timestamp' ), $timestamp );
    $this->assertEquals(
      $collection->get( 'created' ),
      [
        'readable' => $readable,
        'timestamp' => $timestamp,
      ]
    );
  }

  public function testSetsMany(): void {
    $values = [
      'id'            => 'bffc4d08-1750-41a7-9fdd-345187eb9ff2',
      'phone_number'  => 8675309,
      'was_saved'     => false,
    ];

    $collection = new KeyValue();

    $this->assertFalse( $collection->hasAny() );
    $this->assertEquals( $collection->getAll(), null );

    $collection->setMany( null );

    $this->assertFalse( $collection->hasAny() );
    $this->assertEquals( $collection->getAll(), null );

    $collection->setMany( [] );

    $this->assertFalse( $collection->hasAny() );
    $this->assertEquals( $collection->getAll(), null );

    $collection->setMany( $values );

    $this->assertTrue( $collection->hasAny() );
    $this->assertEquals( $collection->getAll(), $values );
  }

  public function testDelete(): void {
    $values = [
      'id'        => 1,
      'title'     => 'A Short Story',
      'is_active' => false,
      'created'   => [
        'readable'  => 'November 7, 2025',
        'timestamp' => '2025-11-07 01:23:45',
      ],
    ];

    $collection = new KeyValue( $values );

    $this->assertTrue( $collection->has( 'title' ) );
    $this->assertTrue( $collection->delete( 'title' ) );
    $this->assertFalse( $collection->has( 'title' ) );
    $this->assertNull( $collection->get( 'title' ) );

    $this->assertTrue( $collection->has( 'created/timestamp' ) );
    $this->assertTrue( $collection->delete( 'created/timestamp' ) );
    $this->assertFalse( $collection->has( 'created/timestamp' ) );
    $this->assertNull( $collection->get( 'created/timestamp' ) );

    $this->assertFalse( $collection->has( 'created/timezone' ) );
    $this->assertFalse( $collection->delete( 'created/timezone' ) );
    $this->assertFalse( $collection->has( 'created/timezone' ) );
    $this->assertNull( $collection->get( 'created/timezone' ) );
  }

  public function testReset(): void {
    $values = [
      'ducks'   => 3,
      'dogs'    => 4,
      'horses'  => 2,
    ];

    $collection = new KeyValue( $values );

    $this->assertTrue( $collection->has( 'ducks' ) );
    $this->assertTrue( $collection->has( 'dogs' ) );
    $this->assertTrue( $collection->has( 'horses' ) );

    $this->assertEquals( $collection->get( 'dogs' ), 4 );

    $collection->set( 'dogs', 6 );

    $this->assertEquals( $collection->get( 'dogs' ), 6 );

    $this->assertTrue( $collection->hasAny() );
    $this->assertFalse( $collection->isEmpty() );
    $this->assertFalse( $collection->isEmpty( true ) );
    $this->assertFalse( $collection->isNull() );

    $this->assertTrue( $collection->reset() );

    $this->assertEquals( $collection->get( 'dogs' ), 4 );

    $this->assertTrue( $collection->reset( true ) );

    $this->assertFalse( $collection->has( 'ducks' ) );
    $this->assertFalse( $collection->has( 'dogs' ) );
    $this->assertFalse( $collection->has( 'horses' ) );

    $this->assertNull( $collection->get( 'dogs' ) );

    $this->assertFalse( $collection->hasAny() );
    $this->assertTrue( $collection->isEmpty() );
    $this->assertFalse( $collection->isEmpty( true ) );
    $this->assertTrue( $collection->isNull() );
  }
}
