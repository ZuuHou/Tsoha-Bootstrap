<?php

$routes->get('/', function() {
    TicketController::show_open();
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
    BetController::store();
});

$routes->get('/ticket/:id', function($id) {
    TicketController::show($id);
});

$routes->get('/ticket/:id/edit', function($id) {
    TicketController::edit($id);
});

$routes->post('/ticket/:id/edit', function($id) {
    TicketController::update($id);
});

$routes->post('/ticket/:id/delete', function($id) {
    TicketController::destroy($id);
});



$routes->get('/bet/:id/edit', function($id) {
    BetController::edit($id);
});

$routes->post('/bet/:id/edit', function($id) {
    BetController::update($id);
});

$routes->post('/bet/:id/edit', function($id) {
    BetController::destroy($id);
});



$routes->get('/gbuser/login', function() {
    UserController::login();
});

$routes->post('/gbuser/login', function() {
    UserController::handle_login();
});

$routes->post('/gbuser/logout', function() {
    UserController::logout();
});

$routes->get('/gbuser/newaccount', function() {
    UserController::create();
});

$routes->post('/gbuser', function() {
    UserController::store();
});



