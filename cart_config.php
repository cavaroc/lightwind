<?php
require_once('stripe-php/init.php');

$stripe = array(
  "secret_key"      => "cOKUjkODsuxm2xV5oK18RLRPeCz8jk71",
  "publishable_key" => "pk_live_1uKBcaJM9SWUFX4yrTEZOzo7IwPZsFeDRs7nMBclwVx5SI8oyN8XFKUSxuwtOLxBQsACZHgTfq0fGfK93laYMVxJC00TXU99hdI"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>