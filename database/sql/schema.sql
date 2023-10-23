-- Drop old schema

DROP TABLE IF EXISTS EventParticipants CASCADE;
DROP TABLE IF EXISTS FavoriteEvent CASCADE;
DROP TABLE IF EXISTS EventHashtag CASCADE;
DROP TABLE IF EXISTS PollVotes CASCADE;
DROP TABLE IF EXISTS EventNotification CASCADE; -- Renamed from UserNotifications
DROP TABLE IF EXISTS Notification CASCADE;
DROP TABLE IF EXISTS MessageReaction CASCADE;
DROP TABLE IF EXISTS EventMessage CASCADE;
DROP TABLE IF EXISTS EventReport CASCADE;
DROP TABLE IF EXISTS Tickets CASCADE; -- Renamed from Ticket
DROP TABLE IF EXISTS Hashtag CASCADE;
DROP TABLE IF EXISTS Report CASCADE;
DROP TABLE IF EXISTS PollOptions CASCADE;
DROP TABLE IF EXISTS Poll CASCADE;
DROP TABLE IF EXISTS UploadFile CASCADE;
DROP TABLE IF EXISTS Authenticated CASCADE;
DROP TABLE IF EXISTS Admin CASCADE;
DROP TABLE IF EXISTS Event CASCADE;
DROP TABLE IF EXISTS Users CASCADE;
DROP TYPE IF EXISTS TypesNotification CASCADE;
DROP TYPE IF EXISTS TypesMessage CASCADE;
DROP TYPE IF EXISTS TypesEvent CASCADE;

-- Create types
CREATE TYPE TypesEvent AS ENUM ('public', 'private', 'approval');
CREATE TYPE TypesMessage AS ENUM ('chat', 'comment');
CREATE TYPE TypesNotification AS ENUM ('request_answer', 'invitation');

-- Create tables
CREATE TABLE Users (
    id SERIAL PRIMARY KEY,
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
    id_profilepic INT DEFAULT 0,
    FOREIGN KEY (id_user) REFERENCES Users(id)
);

CREATE TABLE Event (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    type TypesEvent DEFAULT 'public',
    date DATE NOT NULL,
    capacity INT NOT NULL,
    place VARCHAR(255) NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user)
);

CREATE TABLE EventMessage (
    id SERIAL PRIMARY KEY,
    type TypesMessage NOT NULL,
    content TEXT NOT NULL,
    id_event INT NOT NULL,
    id_user INT NOT NULL,
    date DATE DEFAULT CURRENT_DATE,
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
    id SERIAL PRIMARY KEY
);

CREATE TABLE EventNotification (
    id SERIAL PRIMARY KEY,
    id_user INT NOT NULL,
    id_event INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user),
    FOREIGN KEY (id_event) REFERENCES Event(id)
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
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL
);

CREATE TABLE EventHashtag (
    id_event INT NOT NULL,
    id_hashtag INT NOT NULL,
    FOREIGN KEY (id_event) REFERENCES Event(id),
    FOREIGN KEY (id_hashtag) REFERENCES Hashtag(id)
);

CREATE TABLE TicketType (
    id SERIAL PRIMARY KEY,
    id_event INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category TEXT,
    availability INT NOT NULL
);

CREATE TABLE Orders (
    id SERIAL PRIMARY KEY,
    order_number INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user)
);

CREATE TABLE Tickets (
    id SERIAL PRIMARY KEY,
    description TEXT NOT NULL,
    id_order INT NOT NULL,
    id_ticket_type INT NOT NULL,
    FOREIGN KEY (id_order) REFERENCES Orders(id),
    FOREIGN KEY (id_ticket_type) REFERENCES TicketType(id)
);



CREATE TABLE OrderDetails (
    id_order INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (id_order) REFERENCES Orders(id)
);

CREATE TABLE Report (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL
);

CREATE TABLE EventReport (
    id_event INT NOT NULL,
    id_report INT NOT NULL,
    FOREIGN KEY (id_event) REFERENCES Event(id),
    FOREIGN KEY (id_report) REFERENCES Report(id)
);

CREATE TABLE UploadFile (
    id SERIAL PRIMARY KEY,
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

CREATE TABLE Poll (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    summary TEXT,
    question TEXT NOT NULL,
    startsAt DATE DEFAULT CURRENT_DATE,
    endsAt DATE NOT NULL,
    id_event INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_event) REFERENCES Event(id),
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user)
);

CREATE TABLE PollOptions (
    id SERIAL PRIMARY KEY,
    option TEXT NOT NULL,
    id_poll INT NOT NULL,
    FOREIGN KEY (id_poll) REFERENCES Poll(id)
);

CREATE TABLE PollVotes (
    id_user INT NOT NULL,
    id_option INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user),
    FOREIGN KEY (id_option) REFERENCES PollOptions(id)
);
