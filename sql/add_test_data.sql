-- Lisää INSERT INTO lauseet tähän tiedostoon

-- Gbuser-taulun testidata
INSERT INTO Gbuser (username, password, balance) VALUES ('devnull', 'devnull', '421.0');
-- Ticket taulun testidata
INSERT INTO Ticket (gbuser_id, site, amount, added) VALUES ('1', 'Unibet', '10.00', '2016-11-11');
INSERT INTO Ticket (gbuser_id, site, amount, added) VALUES ('1', 'Nordicbet', '20.00', '2017-02-08');
INSERT INTO Ticket (gbuser_id, site, amount, added) VALUES ('1', 'Nordicbet', '20.00', '2017-02-08');

-- Bet taulun testidata
INSERT INTO Bet (sport, event, endresult, odds, eventdate) VALUES ('NHL', 'New York - Washington', 'New York', '3.00', '2017-02-08');
INSERT INTO Bet (sport, event, endresult, odds, currentstate, eventdate) VALUES ('NHL', 'Chicago - Nahsville', 'Chicago', '5.50', '1', '2017-02-08');
INSERT INTO Bet (sport, event, endresult, odds, currentstate, eventdate) VALUES ('NHL', 'Columbus - Ottawa', 'Columbus', '2.50', '2', '2017-02-08');
INSERT INTO Bet (sport, event, endresult, odds, currentstate, eventdate) VALUES ('e-sports', 'NaVi - Team SoloMid', 'NaVi', '7.00', '0', '2017-02-08');
INSERT INTO Bet (sport, event, endresult, odds, currentstate, eventdate) VALUES ('NHL', 'Ottawa - Calgary', 'Ottawa', '2.50', '1', '2017-01-08');
-- Betticket taulun testidata
INSERT INTO Betticket (bet_id, ticket_id) VALUES ('1', '1');
INSERT INTO Betticket (bet_id, ticket_id) VALUES ('2', '1');
INSERT INTO Betticket (bet_id, ticket_id) VALUES ('3', '1');
INSERT INTO Betticket (bet_id, ticket_id) VALUES ('4', '2');
INSERT INTO Betticket (bet_id, ticket_id) VALUES ('5', '3');