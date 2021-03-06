<?php

class BetController extends BaseController {

    public static function store() {
        self::check_logged_in();
        $params = $_POST;

        $bet = new Bet(array(
            'sport' => $params['sport'],
            'event' => $params['event'],
            'endresult' => $params['endresult'],
            'odds' => $params['odds'],
            'eventdate' => $params['eventdate']
        ));

        $bet->save();

    }

    public static function edit($id) {
        self::check_logged_in();
        $bet = Bet::find($id);
        View::make('bet/edit.html', array('bet' => $bet));
    }

    public static function declaration($id) {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'currentstate' => $params['declaration']
        );

        $bet = new Bet($attributes);

        $bet->update_currentstate($id);

        $ticket = Ticket::find(Bet::find_ticket_id($id));
        if (!$ticket->check_if_open()) {
            $ticket->calculate_result();
            $gbuser = Gbuser::find($_SESSION['gbuser']);
            $gbuser->update_balance($gbuser->balance + $ticket->result);
            Redirect::to('/', array('message' => 'Your bet has been updated and your ticket has been closed! Your balance has been updated accordingly.'));
        }

        Redirect::to('/', array('message' => 'Your bet has been updated!'));
    }

    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'sport' => $params['sport'],
            'event' => $params['event'],
            'endresult' => $params['endresult'],
            'odds' => $params['odds'],
            'eventdate' => $params['eventdate']
        );

        $bet = new Bet($attributes);
        //      $errors = $bet->errors();

        $bet->update($id);

        Redirect::to('/', array('message' => 'Your bet has been updated!'));
    }

    public static function destroy($id) {
        self::check_logged_in();
        $bet = new Bet(array('id' => $id));
        $ticket_id = Bet::find_ticket_id($id);
        $bet->destroy($id);
        $ticket = Ticket::find($ticket_id);
        $ticket->check_if_no_events();

        Redirect::to('/bethistory', array('message' => 'Your bet has been removed!'));
    }

}
