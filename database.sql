CREATE TABLE games (
  gameid int NOT NULL AUTO_INCREMENT,
  maxPlayers int,
  PRIMARY KEY (gameid)
);

CREATE TABLE sentences (
  sentenceid int NOT NULL AUTO_INCREMENT,
  gameid int,
  sentence text,
  PRIMARY KEY (sentenceid),
  FOREIGN KEY (gameid) REFERENCES games(gameid)
);

INSERT INTO games VALUES (3);
INSERT INTO games VALUES (5);
INSERT INTO games VALUES (5);
INSERT INTO games VALUES (5);
