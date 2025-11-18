<?php

declare( strict_types = 1 );

use JustinSkolnick\KeyValue;
use PHPUnit\Framework\TestCase;

class Recipe {
  public ?KeyValue $ingredients = null;

  public function __construct() {
    $this->ingredients = new KeyValue();
  }

  private function getIngredient( string $key ): ?array {
    if ( $this->ingredients->has( $key ) ) {
      $ingredient = $this->ingredients->get( $key );

      return [
        'name' => $key,
        'unit' => $ingredient->get( 'unit' ),
        'amount' => $ingredient->get( 'amount' ),
      ];
    }
  }

  final public function setIngredient( string $name, string $unit, string $amount ): void {
    $details = new KeyValue();
    $details->set( 'unit', $unit );
    $details->set( 'amount', $amount );

    $this->ingredients->set( $name, $details );
  }

  final public function getIngredients(): array {
    $ingredients = [];
    $keys = $this->ingredients->getKeys();

    foreach ( $keys as $key ) {
      list(
        'name' => $name,
        'unit' => $unit,
        'amount' => $amount,
      ) = $this->getIngredient( $key );

      $ingredients[] = join(
        ' ',
        [
          $amount,
          $unit,
          $name, 
        ]
      );
    }

    return $ingredients;
  }
}

class KeyValueExampleTest extends TestCase
{
  public function testWorksAsAClassProperty(): void {
    $oatmeal = new Recipe();

    $this->assertFalse( $oatmeal->ingredients->hasAny() );

    $oatmeal->setIngredient( 'oats', 'cup', '1/2' );
    $oatmeal->setIngredient( 'water', 'cup', '1' );
    $oatmeal->setIngredient( 'salt', 'dash', '1' );

    $this->assertEquals(
      $oatmeal->getIngredients(),
      [
        '1/2 cup oats',
        '1 cup water',
        '1 dash salt',
      ],
    );
  }
}
