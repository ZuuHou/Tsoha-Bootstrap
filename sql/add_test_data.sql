-- Lisää INSERT INTO lauseet tähän tiedostoon

-- Gbuser-taulun testidata
INSERT INTO Gbuser (name, password, balance) VALUES ('Hiistoni', 'H1', '100.00');
INSERT INTO Gbuser (name, password, balance) VALUES ('Pesken', 'P1', '1000.00');
-- Ticket taulun testidata
INSERT INTO Ticket (site, amount, currentstate, added) VALUES ('Unibet', '10.00', NULL, '2016-11-11');
INSERT INTO Ticket (site, amount, currentstate, added) VALUES ('Nordicbet', '20.00', NULL, '2017-02-08');

-- Bet taulun testidata
INSERT INTO Bet (sport, result, odds) VALUES ('NHL', '', '2.00');
INSERT INTO Bet (sport, result, odds) VALUES ('e-sports', 'NaVi', '5.50');

-- Betticket taulun testidata
