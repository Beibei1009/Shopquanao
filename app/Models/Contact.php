<?php
require_once __DIR__.'/../../config/db.php';
class Contact{
 private PDO $pdo;
 public function __construct(){ $this->pdo=getPDO(); }
 public function create(string $name,string $email,string $message):void{ $st=$this->pdo->prepare('INSERT INTO contacts(name,email,message) VALUES(:n,:e,:m)'); $st->execute([':n'=>$name,':e'=>$email,':m'=>$message]); }
}