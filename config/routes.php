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

$routes->get('/ticket', function() {
    TicketController::index();
});

$routes->get('/ticket/new', function() {
    TicketController::create();
});

$routes->post('/ticket', function() {
    TicketController::store();
});

$routes->get('/ticket/:id', function($id) {
    TicketController::show($id);
});



