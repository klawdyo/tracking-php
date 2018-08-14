<?php

class TrackingValidation {

  /**
   * Multipliers
   */
  public $multipliers = [ 8, 6, 4, 2, 3, 5, 9, 7 ];

  /**
   * Is a postal register number?
   * 
   * @example
   * isValid( 'RU866808761CN' )
   * // true
   * isValid( 'RU866808760CN' )
   * // false
   * 
   * @param {String} number Postal Register Number in format AA123456789BB
   */
  public static function isValid ( $number ) {
    $parts = TrackingValidation::parse ( $number );

    if ( $parts )
      return TrackingValidation::getCheckingNumber( $parts['number'] ) === $parts['checkingNumber'];

    return false;
  }

  /**
   * Gets a checking number from a given postal code parsed.
   * @example
   * To calculate checking number from RU866808761CN, pass to '86680876'
   * getCheckingNumber( 86680876 ) 
   * // 1
   * 
   * @param {String|Integer} number Postal Register Number in format 12345678
   */
  public static function getCheckingNumber ( $number ) {
    // Check if is string or integer and 
    // if ( typeof number !== 'string' && typeof number !== 'integer' ) return null;
    if ( !is_string( $number ) && !is_numeric( $number ) && strlen($number ) !== 8 ) return null;

    // Regex
    $rgx = '/^([0-9]{8})$/i';

    if ( preg_match( $rgx, $number ) ) {
      $multipliers  = [ 8, 6, 4, 2, 3, 5, 9, 7 ];

      $sum = 0;

      foreach( $multipliers as $key => $multiplier ) {
        $sum += ( $multiplier * $number[$key]  );
      }
      
      // Rest calculus
      $rest = $sum % 11;

      // 
      if ( $rest === 0 ) return '5';
      else if ( $rest === 1 ) return '0';
      else return (string)( 11 - $rest );
    } else {
      return null;
    }
  }


  /**
   * Parses a postal register number
   * 
   * @example
   * parse( 'RU866808761CN' ) 
   * // { type: 'RU', number: '86680876', checkingNumber: '1', country: 'BR' }
   * 
   * parse( 'KLAWDYO' )
   * // false
   * 
   * @param {String} number Postal Register Number in format AA123456789BB
   */
  public static function parse ( $number ) {
    $rgx = '/(?<type>[a-z]{2})(?<number>[0-9]{8})(?<checkingNumber>[0-9]{1})(?<country>[a-z]{2})/i';

    preg_match( $rgx, $number, $parts );

    if ( $parts ) {
        return [
          'type' => $parts['type'],
          'number' => $parts['number'],
          'checkingNumber' => $parts['checkingNumber'],
          'country' => $parts['country'],
        ];
    }
    return null;
  }
}