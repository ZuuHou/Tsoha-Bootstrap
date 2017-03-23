<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
  
  $routes->get('/bethistory', function() {
    HelloWorldController::bethistory();
  });
  
  $routes->get('/bet', function() {
    HelloWorldController::bet();
  });
  
  $routes->get('/balance', function() {
    HelloWorldController::balance();
  });
