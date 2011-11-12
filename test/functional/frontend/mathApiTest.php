<?php

// test/functional/mathApiTest.php
$app   = 'frontend';
$debug = true;

include_once(dirname(__FILE__).'/../../bootstrap/soaptest.php');

$c = new ckTestSoapClient();

// test executeMultiply
$c->SimpleMultiply(5, 2)    // call the action
  ->isFaultEmpty()         // check there are no errors
  ->isType('', 'double')   // check the result type is double
  ->is('', 20);            // check the result value is 10