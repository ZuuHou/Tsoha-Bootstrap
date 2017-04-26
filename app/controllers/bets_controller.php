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

 //       Redirect::to('/ticket/' . $ticket->id, array('message' => 'You have succesfully added a new bet!'));
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
   //     $errors = $bet->errors();

        $bet->declaration($id);

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
        Ticket::check_if_no_events($ticket_id);

        Redirect::to('/bethistory', array('message' => 'Your bet has been removed!'));
    }  

}
