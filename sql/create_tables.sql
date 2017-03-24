-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE Gbuser(
  id SERIAL PRIMARY KEY, -- SERIAL tyyppinen pääavain pitää huolen, että tauluun lisätyllä rivillä on aina uniikki pääavain. Kätevää!
  name varchar(50) NOT NULL, -- Muista erottaa sarakkeiden määrittelyt pilkulla!
  password varchar(50) NOT NULL,
  balance decimal(10,3)
);

CREATE TABLE Ticket(
  id SERIAL PRIMARY KEY,
  gbuser_id INTEGER REFERENCES Gbuser(id), -- Viiteavain Player-tauluun
  amount decimal(10,3) NOT NULL,
  currentstate boolean DEFAULT NULL,
  added DATE
);

CREATE TABLE Bet(
  id SERIAL PRIMARY KEY,
  sport varchar(50) NOT NULL,
  result varchar(50),
  odds decimal(10,3)
);

CREATE TABLE Betticket(
  bet_id INTEGER REFERENCES bet(id), -- Viiteavain Player-tauluun
  ticket_id INTEGER REFERENCES ticket(id)
);