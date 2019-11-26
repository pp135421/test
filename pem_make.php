<?php

header("Content-Type: text/html; charset=utf-8");

$filename = dirname(__FILE__)."/payPublicKey.pem";//生成的公钥或私钥文件
    
    @chmod($filename, 0777);
    @unlink($filename);

$devPubKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjv9bOf2U6jKSCTOY8G7IXeUWwCHov7VskVoXOjY6BDAbz/20+Usl5wXbqd8BD88qxyNa5EEKZ/Zm78yV9rMCNWCnBYC7e5k+XylNQwHWkYUcSciIaxuj3Z0JyNPgbSz6P5KKHSXGrNki004GrA4SMgVx4Tp3wCUuHW3ikndT8BKutf/fbfp/yz5ran/BjsJt4wM4kT8MCA8nqekZp6a4zONa3scfOj8lzx5RHhjQ6vc10vTIS8agjslCTH/qGTUCOZAqn3NuI/l07w8WWABFGla4J8054JZ09eSo4S2N5gZFbbOio2fmAVwkBgWTThmSNIgvSNEB81YwnZDO1cOs3wIDAQAB";//公钥或私钥


$begin_public_key = "-----BEGIN PUBLIC KEY-----\r\n";  //-----BEGIN PRIVATE KEY-----
$end_public_key = "-----END PUBLIC KEY-----\r\n";  //-----END PRIVATE KEY-----

$fp = fopen($filename,'ab'); 
fwrite($fp,$begin_public_key,strlen($begin_public_key)); 
$raw = strlen($devPubKey)/64;
$index = 0; 
while($index <= $raw ) {
  $line = substr($devPubKey,$index*64,64)."\r\n"; 
  if(strlen(trim($line)) > 0)
   fwrite($fp,$line,strlen($line)); 
   $index++; 
} 
fwrite($fp,$end_public_key,strlen($end_public_key)); 
fclose($fp);