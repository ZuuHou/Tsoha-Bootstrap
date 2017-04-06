<?php

class TicketController extends BaseController{
  public static function index(){
    $tickets = Ticket::all();
    View::make('ticket/index.html', array('tickets' => $tickets));
  }
  public static function show($id){
      $ticket = Ticket::find($id);
      View::make('ticket/ticket.html', array('ticket' => $ticket));
  }
  
  public static function create(){
      View::make('ticket/new.html');
  }
  
  public static function store(){
    $params = $_POST;

    $ticket = new Ticket(array(
      'site' => $params['site'],
      'amount' => $params['amount'],
      'currentstate' => NULL,
      'added' => $params['added']
    ));

    $ticket->save();

    Redirect::to('/ticket/' . $ticket->id, array('message' => 'You have succesfully added a new bet!'));
  }
  
  public static function edit($id){
    $ticket = Ticket::find($id);
    View::make('ticket/edit.html', array('ticket' => $ticket));
  }

  public static function update($id){
    $params = $_POST;

    $attributes = array(
      'id' => $id,
      'site' => $params['site'],
      'amount' => $params['amount'],
      'currentstate' => $params['currentstate'],
      'added' => $params['added']
    );

    $ticket = new Ticket($attributes);
    $errors = $ticket->errors();

    if(count($errors) > 0){
      View::make('ticket/edit.html', array('errors' => $errors, 'attributes' => $attributes));
    }else{
      $ticket->update();

      Redirect::to('/' . $ticket->id, array('message' => 'Your bet has been updated!'));
    }
  }

  // Pelin poistaminen
  public static function destroy($id){
    $ticket = new Ticket(array('id' => $id));
    $ticket->destroy();

    Redirect::to('/ticket', array('message' => 'Your bet has been removed!'));
  }
}
