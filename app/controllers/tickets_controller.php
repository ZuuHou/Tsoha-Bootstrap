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
    // POST-pyynnön muuttujat sijaitsevat $_POST nimisessä assosiaatiolistassa
    $params = $_POST;
    // Alustetaan uusi Ticket-luokan olion käyttäjän syöttämillä arvoilla
    $ticket = new Ticket(array(
      'site' => $params['site'],
      'amount' => $params['amount'],
      'currentstate' => NULL,
      'added' => $params['added']
    ));

    // Kutsutaan alustamamme olion save metodia, joka tallentaa olion tietokantaan
    $ticket->save();

    // Ohjataan käyttäjä lisäyksen jälkeen pelin esittelysivulle
    Redirect::to('/ticket/' . $ticket->id, array('message' => 'You have succesfully added a new bet!'));
  }
}
