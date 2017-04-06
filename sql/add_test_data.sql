-- Lisää INSERT INTO lauseet tähän tiedostoon

-- Gbuser-taulun testidata
INSERT INTO Gbuser (username, password, balance) VALUES ('devnull', 'devnull', '421.0');
-- Ticket taulun testidata
INSERT INTO Ticket (gbuser_id, site, amount, currentstate, added) VALUES ('1', 'Unibet', '10.00', NULL, '2016-11-11');
INSERT INTO Ticket (gbuser_id, site, amount, currentstate, added) VALUES ('1', 'Nordicbet', '20.00', NULL, '2017-02-08');

-- Bet taulun testidata
INSERT INTO Bet (sport, endresult, odds, eventdate) VALUES ('NHL', '', '2.00', '2017-02-08');
INSERT INTO Bet (sport, endresult, odds, eventdate) VALUES ('e-sports', 'NaVi', '5.50', '2017-02-08');

-- Betticket taulun testidata
