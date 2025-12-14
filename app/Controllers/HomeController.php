<?php
require_once __DIR__.'/../Models/Product.php';

class HomeController{
 public function index():void{ $p=new Product(); $latest=$p->latest(8); $title='SALE 30% – LILY & CO.'; include __DIR__.'/../Views/home.php'; }
 public function about():void{ $title='Giới thiệu cửa hàng'; include __DIR__.'/../Views/about.php'; }
}