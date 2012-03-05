<?php
/**
 * Test
 *
 * @author Sergey
 * @copyright Anlab, Sergey S., 2 марта, 2012
 * @package cryptocp
 **/

// default timezone
date_default_timezone_set( 'Europe/Moscow' );
 
if (PHP_OS == 'Darwin')
    include_once 'CryptoCP.conf.php';
else
    include_once 'CryptoCP.conf.win.php';
    
include_once 'CryptoCP.php';

/* set test message */
$string = 'Old test message';
/* create CryptoCP object */
$cp = new CryptoCP($string);
/* test set/get */
var_dump($cp->getData());
var_dump($cp->setData('Test message')->getData());
/* test sign */
var_dump('Sign: ', $cp->sign());
var_dump('Hash: ', $cp->hash());
var_dump('Signf: ', $cp->signf());
