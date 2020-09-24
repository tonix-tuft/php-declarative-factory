<?php

/*
 * Copyright (c) 2020 Anton Bagdatyev (Tonix)
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

namespace DeclarativeFactory;

/**
 * Declarative factory.
 */
class DeclarativeFactory {
  /**
   * Factory method to declaratively switch between components.
   *
   * @param array $tuples An array of tuples, each tuple being an array containing:
   *
   *                      - A boolean value or a function returning a boolean value at index 0,
   *                        specifying whether to return this component or not;
   *                      - A function returning the component itself (mixed) or the component itself to return at index 1.
   *                        The component will be returned to the consuming code if and only if the condition at index 0 is satisfied;
   *
   *                      A default value may be specified as the last element (either a function returning it or the component itself).
   *                      In that case it will be returned if all the previous conditions are falsy.
   * @return mixed The result of the factory, i.e. the component to return. NULL if there isn't any component
   *               for which the corresponding condition is satisfied, provided that a default value is missing.
   */
  public static function factory($tuples) {
    $lastIndex = count($tuples) - 1;
    $i = 0;
    foreach ($tuples as $tuple) {
      $isEffectivelyATuple =
        is_array($tuple) && array_keys($tuple) === range(0, count($tuple) - 1); // Check if tuple is an indexed array (not an associative array).
      if ($lastIndex === $i && !$isEffectivelyATuple) {
        // Default.
        return is_callable($tuple) ? $tuple() : $tuple;
      }
      [$condition, $value] = $tuple;
      if (is_callable($condition)) {
        $condition = $condition();
      }
      if ($condition) {
        return is_callable($value) ? $value() : $value;
      }
      $i++;
    }
    return null;
  }
}
