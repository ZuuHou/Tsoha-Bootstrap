CREATE TABLE Gbuser(
  id SERIAL PRIMARY KEY,
  name varchar(50) NOT NULL, 
  password varchar(50) NOT NULL,
  balance decimal(10,2)
);

CREATE TABLE Ticket(
  id SERIAL PRIMARY KEY,
  gbuser_id INTEGER REFERENCES Gbuser(id),
  site varchar(50),
  amount decimal(10,2) NOT NULL,
  currentstate boolean DEFAULT NULL,
  added DATE
);

CREATE TABLE Bet(
  id SERIAL PRIMARY KEY,
  sport varchar(50),
  result varchar(50),
  odds decimal(10,2)
);

CREATE TABLE Betticket(
  bet_id INTEGER REFERENCES bet(id),
  ticket_id INTEGER REFERENCES ticket(id)
);