CREATE TABLE Gbuser(
  id SERIAL PRIMARY KEY,
  username varchar(50) NOT NULL, 
  password varchar(50) NOT NULL,
  balance decimal(10,2) DEFAULT 0
);

CREATE TABLE Ticket(
  id SERIAL PRIMARY KEY,
  gbuser_id INTEGER REFERENCES Gbuser(id),
  site varchar(50),
  amount decimal(10,2) NOT NULL,
  added DATE
);

CREATE TABLE Bet(
  id SERIAL PRIMARY KEY,
  sport varchar(50),
  event varchar(50),
  endresult varchar(50),
  odds decimal(10,2),
  currentstate numeric DEFAULT 0,
  eventdate DATE
);

CREATE TABLE Betticket(
  ticket_id INTEGER REFERENCES ticket(id),
  bet_id INTEGER REFERENCES bet(id)
);