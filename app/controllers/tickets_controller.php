<?php

class TicketController extends BaseController {

    public static function index() {
        self::check_logged_in();
        $tickets = Ticket::all();
        View::make('ticket/index.html', array('tickets' => $tickets));
    }

    public static function show_open() {
        self::check_logged_in();
        $tickets = Ticket::show_open();
        View::make('index.html', array('tickets' => $tickets));
    }
    
    public static function show_history() {
        self::check_logged_in();
        $tickets = Ticket::all();
        View::make('bethistory.html', array('tickets' => $tickets));
    }

    public static function show($id) {
        self::check_logged_in();
        $ticket = Ticket::find($id);
        View::make('ticket/ticket.html', array('ticket' => $ticket));
    }

    public static function create() {
        self::check_logged_in();
        View::make('ticket/new.html');
    }

    public static function store() {
        self::check_logged_in();
        $params = $_POST;

        $ticket = new Ticket(array(
            'gbuser_id' => $_SESSION['gbuser'],
            'site' => $params['site'],
            'amount' => $params['amount'],
            'added' => $params['added']
        ));

        $ticket->save();

        Redirect::to('/', array('message' => 'You have succesfully added a new bet!'));
    }

    public static function edit($id) {
        self::check_logged_in();
        $ticket = Ticket::find($id);
        $bets = Bet::findAllFromTicket($id);
        View::make('ticket/edit.html', array('ticket' => $ticket, 'bets' => $bets));
    }

    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'site' => $params['site'],
            'amount' => $params['amount']
        );

        $ticket = new Ticket($attributes);
        $errors = $ticket->errors();

        //   if(count($errors) > 0){
        //   View::make('ticket/edit.html', array('errors' => $errors, 'attributes' => $attributes));
        // }else{
        $ticket->update($id);

        Redirect::to('/', array('message' => 'Your bet has been updated!'));
    }

    // Pelin poistaminen
    public static function destroy($id) {
        self::check_logged_in();
        $ticket = new Ticket(array('id' => $id));
        $ticket->destroy($id);

        Redirect::to('/', array('message' => 'Your bet has been removed!'));
    }

}
