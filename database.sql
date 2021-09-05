CREATE TABLE users (
  username varchar(200) NOT NULL,
  password char(60) NOT NULL,
  PRIMARY KEY (username)
);

CREATE TABLE games (
  gameid int NOT NULL AUTO_INCREMENT,
  user1 varchar(200),
  user2 varchar(200),
  turnnum int,
  whoseturn int,
  p1 varchar(1),
  p2 varchar(1),
  p3 varchar(1),
  p4 varchar(1),
  p5 varchar(1),
  p6 varchar(1),
  p7 varchar(1),
  p8 varchar(1),
  p9 varchar(1),
  PRIMARY KEY (gameid),
  FOREIGN KEY (user1) REFERENCES users(username),
  FOREIGN KEY (user2) REFERENCES users(username)
);

CREATE TABLE stats (
  username varchar(200) NOT NULL,
  gamesplayed int,
  wins int,
  draws int,
  loses int,
  score int,
  winrate float,
  PRIMARY KEY (username),
  FOREIGN KEY (username) REFERENCES users(username)
);
