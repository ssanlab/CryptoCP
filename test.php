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
$string  = 'Test message';
$newMsg  = 'New message';
$hash    = 'LjO+xcG/D6gOgegKgDJVEUNnmpNwrlpusIaTHTcbAOA=';
$signLen = 64;
/* create CryptoCP object */
$cp = new CryptoCP($string);
/* test set/get */
echo "Current message:\n";
var_dump($cp->getData());
echo "Set new message and check:\t";
$new = $cp->setData($newMsg)->getData();
echo ($new == $newMsg ? "ok" : "fail") . "\n";
echo "Restore original message:\t";
$new = $cp->setData($string)->getData();
echo ($new == $string ? "ok" : "fail") . "\n";
/* test sign */
// var_dump('Sign: ', $cp->sign());
/* hash() */
echo "Generate hash:\t\t\t";
$hsh = $cp->hash();
echo "\"{$hsh}\"\n";
echo "Check hash:\t\t\t";
echo ($hsh == $hash ? "ok" : "fail") . "\n";
/* pureSign() */
echo "Generate sign by pureSign():\n";
$ps = $cp->pureSign();
var_dump($ps);
echo "Check sign length (eq {$signLen}):\t";
echo (strlen(base64_decode($ps)) == $signLen ? "ok" : "fail") . "\n";
/* pkcs7Sign() */
echo "Generate sign by pkcs7Sign():\n";
$pkcs7 = $cp->pkcs7Sign();
var_dump($pkcs7);
echo "Check sign length (eq {$signLen}):\t";
echo (strlen(base64_decode($pkcs7)) == $signLen ? "ok" : "fail") . "\n";
/* cert */
echo "Get certificate:\n";
var_dump($cp->getCertificate());
