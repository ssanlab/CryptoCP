<?php
/**
 * Test
 *
 * @author Sergey
 * @copyright Anlab, Sergey S., 2 марта, 2012
 * @package cryptocp
 **/

include_once 'CryptoCP.conf.php';
include_once 'CryptoCP.php';

/* set test message */
$string = 'Test message';
/* create CryptoCP object */
$cp = new CryptoCP($string);
/* test set/get */
var_dump($cp->getData());
var_dump($cp->setData('New test message')->getData());
var_dump($cp->getSign());
/* test sign */
var_dump($cp->sign());
