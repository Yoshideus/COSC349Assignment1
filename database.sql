CREATE TABLE users (
  username text NOT NULL,
  password char(60) NOT NULL,
  PRIMARY KEY (username)
);

CREATE TABLE games (
  gameid int NOT NULL AUTO_INCREMENT,
  user1 username,
  user2 username,
  turnnum int,
  whoseturn int,
  p1 char(1),
  p2 char(1),
  p3 char(1),
  p4 char(1),
  p5 char(1),
  p6 char(1),
  p7 char(1),
  p8 char(1),
  p9 char(1),
  PRIMARY KEY (gameid),
  FOREIGN KEY (user1) REFERENCES users(username),
  FOREIGN KEY (user2) REFERENCES users(username)
);

CREATE TABLE stats (
  username text NOT NULL,
  gamesplayed int,
  wins int,
  draws int,
  loses int,
  score int,
  winrate float,
  PRIMARY KEY (username),
  FOREIGN KEY (username) REFERENCES users(username)
);

INSERT INTO games (maxPlayers) VALUES (3);
INSERT INTO games (maxPlayers) VALUES (5);
INSERT INTO games (maxPlayers) VALUES (5);
INSERT INTO games (maxPlayers) VALUES (5);
