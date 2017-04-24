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

    public static function show_creation($id) {
        self::check_logged_in();
        $ticket = Ticket::find($id);
        $bets = Bet::findAllFromTicket($id);
        View::make('ticket/add.html', array('ticket' => $ticket, 'bets' => $bets));
    }

    public function store() {
        self::check_logged_in();
        $params = $_POST;

        $ticket = new Ticket(array(
            'gbuser_id' => $_SESSION['gbuser'],
            'site' => $params['site'],
            'amount' => $params['amount'],
            'added' => $params['added']
        ));

        $id = $ticket->save();

        $bet = new Bet(array(
            'sport' => $params['sport'],
            'event' => $params['event'],
            'endresult' => $params['endresult'],
            'odds' => $params['odds'],
            'eventdate' => $params['eventdate']
        ));

        $bet_id = $bet->save();
        $ticket->add_bet($bet_id);

        Redirect::to("/ticket/$id/add", array('message' => 'You have succesfully added an event. Now you can add another one or confirm the bet.', 'ticket' => $ticket));
    }

    public function add($id) {
        self::check_logged_in();
        $params = $_POST;
        $ticket = Ticket::find($id);
        $bet = new Bet(array(
            'sport' => $params['sport'],
            'event' => $params['event'],
            'endresult' => $params['endresult'],
            'odds' => $params['odds'],
            'eventdate' => $params['eventdate']
        ));

        $bet_id = $bet->save();
        $ticket->add_bet($bet_id);
        Redirect::to("/ticket/$id/add", array('message' => 'You have succesfully added an event. Now you can add another one or confirm the bet.', 'ticket' => $ticket));
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
        $ticket->update();

        Redirect::to('/', array('message' => 'Your bet has been updated!'));
    }

    public static function destroy($id) {
        self::check_logged_in();
        $ticket = new Ticket(array('id' => $id));
        $ticket->destroy($id);

        Redirect::to('/', array('message' => 'Your bet has been removed!'));
    }

}
