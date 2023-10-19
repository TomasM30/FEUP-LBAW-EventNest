-----------------------------------------
-- Drop old schema
-----------------------------------------

DROP TABLE IF EXISTS Users CASCADE;
DROP TABLE IF EXISTS Admin CASCADE;
DROP TABLE IF EXISTS Authenticated CASCADE;
DROP TABLE IF EXISTS Event CASCADE;
DROP TABLE IF EXISTS EventMessage CASCADE;
DROP TABLE IF EXISTS MessageReaction CASCADE;
DROP TABLE IF EXISTS UserNotifications CASCADE;
DROP TABLE IF EXISTS Notification CASCADE;
DROP TABLE IF EXISTS EventParticipants CASCADE;
DROP TABLE IF EXISTS FavoriteEvent CASCADE;
DROP TABLE IF EXISTS Hashtag CASCADE;
DROP TABLE IF EXISTS Ticket CASCADE;
DROP TABLE IF EXISTS Report CASCADE;
DROP TABLE IF EXISTS EventReport CASCADE;
DROP TABLE IF EXISTS UploadFile CASCADE;
DROP TABLE IF EXISTS Poll CASCADE;
DROP TABLE IF EXISTS PollOptions CASCADE;
DROP TABLE IF EXISTS PollVotes CASCADE;

-----------------------------------------
-- Drop old types
-----------------------------------------

DROP TYPE IF EXISTS TypesEvent CASCADE;
DROP TYPE IF EXISTS TypesMessage CASCADE;
DROP TYPE IF EXISTS TypesNotification CASCADE;

-----------------------------------------
-- Drop old default values
-----------------------------------------

DROP DEFAULT IF EXISTS DATE;
DROP DEFAULT IF EXISTS path;

-----------------------------------------
-- Create types
-----------------------------------------

CREATE TYPE TypesEvent AS ENUM ('public', 'private', 'approval');
CREATE TYPE TypesMessage AS ENUM ('chat', 'comment', 'video', 'audio');
CREATE TYPE TypesNotification AS ENUM ('request_answer', 'invitation');

-----------------------------------------
-- Create Default Values
-----------------------------------------

DATE DEFAULT CURRENT_DATE;
path VARCHAR(255) DEFAULT 'default.png';

-----------------------------------------
-- Create tables
-----------------------------------------

CREATE TABLE Users (
    id INT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE Admin (
    id_user INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Users(id)
);

CREATE TABLE Authenticated (
    id_user INT PRIMARY KEY,
    is_verified BOOLEAN DEFAULT FALSE,
    id_profilepic INT DEFAULT '0',
    FOREIGN KEY (id_user) REFERENCES Users(id)
);

CREATE TABLE Event (
    id INT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    type VARCHAR(255) CHECK (type IN ('TypesEvent')),
    date DATE NOT NULL,
    capacity INT NOT NULL,
    place VARCHAR(255) NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user)
);

CREATE TABLE EventMessage (
    id INT PRIMARY KEY,
    type VARCHAR(255) CHECK (type IN ('TypesMessage')),
    content TEXT NOT NULL,
    id_event INT NOT NULL,
    id_user INT NOT NULL,
    date DATE DEFAULT CURRENT_DATE
    FOREIGN KEY (id_event) REFERENCES Event(id),
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user)
);

CREATE TABLE MessageReaction (
    id_user INT NOT NULL,
    id_message INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user),
    FOREIGN KEY (id_message) REFERENCES EventMessage(id)
);

CREATE TABLE Notification (
    id INT PRIMARY KEY,
    type VARCHAR(255) CHECK (type IN ('TypesNotification')),
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    date DATE DEFAULT CURRENT_DATE,
    id_event INT NOT NULL,
    FOREIGN KEY (id_event) REFERENCES Event(id)
);

CREATE TABLE UserNotifications (
    id_user INT NOT NULL,
    id_notification INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Users(id),
    FOREIGN KEY (id_notification) REFERENCES Notification(id)
);

CREATE TABLE EventParticipants (
    id_user INT NOT NULL,
    id_event INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user),
    FOREIGN KEY (id_event) REFERENCES Event(id)
);

CREATE TABLE FavoriteEvent (
    id_user INT NOT NULL,
    id_event INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user),
    FOREIGN KEY (id_event) REFERENCES Event(id)
);

CREATE TABLE Hashtag (
    id INT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    id_event INT NOT NULL,
    FOREIGN KEY (id_event) REFERENCES Event(id)
);

CREATE TABLE Hashtag{
 id INT PRIMARY KEY,
 title VARCHAR(255) NOT NULL,
};

CREATE TABLE EventHashtag {
 id_event INT NOT NULL,
 id_hashtag INT NOT NULL,
 FOREIGN KEY (id_event) REFERENCES Event(id),
 FOREIGN KEY (id_hashtag) REFERENCES Hashtag(id)
};

CREATE TABLE Ticket (
    id INT PRIMARY KEY,
    id_event INT NOT NULL,
    id_user INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    title VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_event) REFERENCES Event(id),
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user)
);

CREATE TABLE Report (
    id INT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL
);

CREATE TABLE EventReport (
    id_event INT NOT NULL,
    id_report INT NOT NULL,
    FOREIGN KEY (id_event) REFERENCES Event(id),
    FOREIGN KEY (id_report) REFERENCES Report(id)
);

CREATE TABLE File (
    id INT PRIMARY KEY,
    path VARCHAR(255) NOT NULL,
    id_user INT NOT NULL,
    id_message INT,
    id_report INT,
    id_event INT,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user),
    FOREIGN KEY (id_message) REFERENCES EventMessage(id),
    FOREIGN KEY (id_report) REFERENCES Report(id),
    FOREIGN KEY (id_event) REFERENCES Event(id)
);

CREATE TABLE Poll{
    id INT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    summary TEXT NOT NULL,
    question TEXT NOT NULL,
    startsAt DATE NOT NULL,
    endsAt DATE NOT NULL,
    id_event INT NOT NULL,
    id user INT NOT NULL, /* Duvidas */
    FOREIGN KEY (id_event) REFERENCES Event(id),
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user)
};

CREATE TABLE poll_options{
    int id INT PRIMARY KEY,
    option TEXT NOT NULL,
    id_pool INT NOT NULL,
    FOREIGN KEY (id_poll) REFERENCES Poll(id)
};

CREATE TABLE poll_votes{
 int id_user INT NOT NULL,
 int id_option INT NOT NULL,
};
