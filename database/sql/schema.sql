DROP SCHEMA IF EXISTS lbaw23144 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw23144;
SET search_path TO lbaw23144;

DROP TABLE IF EXISTS EventParticipants CASCADE;
DROP TABLE IF EXISTS FavoriteEvent CASCADE;
DROP TABLE IF EXISTS EventHashtag CASCADE;
DROP TABLE IF EXISTS PollVotes CASCADE;
DROP TABLE IF EXISTS Notification CASCADE;
DROP TABLE IF EXISTS EventNotification CASCADE;
DROP TABLE IF EXISTS messages CASCADE;
DROP TABLE IF EXISTS Ticket CASCADE;
DROP TABLE IF EXISTS Hashtag CASCADE;
DROP TABLE IF EXISTS Report CASCADE;
DROP TABLE IF EXISTS PollOption CASCADE;
DROP TABLE IF EXISTS Poll CASCADE;
DROP TABLE IF EXISTS Authenticated CASCADE;
DROP TABLE IF EXISTS Admin CASCADE;
DROP TABLE IF EXISTS Event CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS Users CASCADE;
DROP TABLE IF EXISTS OrderDetail CASCADE;
DROP TABLE IF EXISTS TicketType CASCADE;
DROP TABLE IF EXISTS Orders CASCADE;

DROP TYPE IF EXISTS TypesNotification CASCADE;
DROP TYPE IF EXISTS TypesMessage CASCADE;
DROP TYPE IF EXISTS TypesEvent CASCADE;

DROP FUNCTION IF EXISTS user_search_update CASCADE;
DROP FUNCTION IF EXISTS created_search_update CASCADE;
DROP FUNCTION IF EXISTS event_search_update CASCADE;
DROP FUNCTION IF EXISTS check_organizer_enrollment CASCADE;
DROP FUNCTION IF EXISTS verify_user_attendance CASCADE;
DROP FUNCTION IF EXISTS check_poll_options CASCADE;
DROP FUNCTION IF EXISTS user_ticket CASCADE;
DROP FUNCTION IF EXISTS admin_event CASCADE;


-- Create types
CREATE TYPE TypesEvent AS ENUM ('public', 'private', 'approval');
CREATE TYPE TypesNotification AS ENUM ('invitation_received', 'request', 'invitation_accepted', 
                                        'invitation_rejected', 'request_rejected', 'request_accepted',
                                        'removed_from_event', 'added_to_event', 'event_canceled',
                                        'event_edited', 'report_received', 'report_closed');

-- Create tables
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    remember_token VARCHAR(256) DEFAULT NULL,
    password VARCHAR(255),
    google_id VARCHAR(255),
    profile_image VARCHAR(255) DEFAULT NULL

);

CREATE TABLE Admin (
    id_user INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id),
    PRIMARY KEY (id_user)
);

CREATE TABLE Authenticated (
    id_user INT PRIMARY KEY,
    is_verified BOOLEAN DEFAULT FALSE,
    is_blocked BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_user) REFERENCES users(id)
);

CREATE TABLE Event (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    type TypesEvent DEFAULT 'public',
    date DATE NOT NULL,
    capacity INT NOT NULL,
    ticket_limit INT ,
    place VARCHAR(255) NOT NULL,
    id_user INT,
    closed BOOLEAN DEFAULT FALSE,
    image VARCHAR(255) DEFAULT NULL,
    CHECK (ticket_limit <= capacity),
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user)
);

CREATE TABLE messages (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    id_event INT NOT NULL,
    id_user INT NOT NULL,
    date DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (id_event) REFERENCES Event(id),
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user)
);

CREATE TABLE Report (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    id_user INT,
    id_event INT NOT NULL,
    closed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    file VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user),
    FOREIGN KEY (id_event) REFERENCES Event(id)
);

CREATE TABLE Notification (
    id SERIAL PRIMARY KEY,
    type TypesNotification NOT NULL,
    id_user INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    report_id INT DEFAULT NULL,
    FOREIGN KEY (report_id) REFERENCES Report(id),
    FOREIGN KEY (id_user) REFERENCES users(id)
);

CREATE TABLE EventNotification (
    id INT PRIMARY KEY,
    inviter_id INT,
    id_event INT NOT NULL,
    FOREIGN KEY (id) REFERENCES Notification(id),
    FOREIGN KEY (inviter_id) REFERENCES users(id),
    FOREIGN KEY (id_event) REFERENCES Event(id)
);


CREATE TABLE EventParticipants (
    id_user INT NOT NULL,
    id_event INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user),
    FOREIGN KEY (id_event) REFERENCES Event(id),
    PRIMARY KEY (id_user, id_event)
);

CREATE TABLE FavoriteEvent (
    id_user INT NOT NULL,
    id_event INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user),
    FOREIGN KEY (id_event) REFERENCES Event(id),
    PRIMARY KEY (id_user, id_event)
);

CREATE TABLE Hashtag (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL
);

CREATE TABLE EventHashtag (
    id_event INT NOT NULL,
    id_hashtag INT NOT NULL,
    FOREIGN KEY (id_event) REFERENCES Event(id),
    FOREIGN KEY (id_hashtag) REFERENCES Hashtag(id),
    PRIMARY KEY (id_event, id_hashtag)
);

CREATE TABLE TicketType (
    id SERIAL PRIMARY KEY,
    id_event INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category TEXT,
    availability INT NOT NULL,
    FOREIGN KEY (id_event) REFERENCES Event(id)
);

CREATE TABLE Orders (
    id SERIAL PRIMARY KEY,
    order_number INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user)
);

CREATE TABLE Ticket (
    id SERIAL PRIMARY KEY,
    description TEXT NOT NULL,
    id_order INT NOT NULL,
    id_ticket_type INT NOT NULL,
    FOREIGN KEY (id_order) REFERENCES Orders(id),
    FOREIGN KEY (id_ticket_type) REFERENCES TicketType(id)
);

CREATE TABLE OrderDetail (
    id_order INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (id_order) REFERENCES Orders(id),
    PRIMARY KEY (id_order)
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

CREATE TABLE PollOption (
    id SERIAL PRIMARY KEY,
    option TEXT NOT NULL,
    id_poll INT NOT NULL,
    FOREIGN KEY (id_poll) REFERENCES Poll(id)
);

CREATE TABLE PollVotes (
    id_user INT NOT NULL,
    id_option INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Authenticated(id_user),
    FOREIGN KEY (id_option) REFERENCES PollOption(id),
    PRIMARY KEY (id_user, id_option)
);

----------------------------------------------------------
-- INDEXES
----------------------------------------------------------

-- Performance indexes

CREATE INDEX event_manager_id ON event USING btree (id_user);
CREATE INDEX event_participant_id ON eventparticipants USING btree (id_user, id_event);
CREATE INDEX date_event ON event USING btree (date);
CREATE INDEX orders_user_id ON orders USING hash (id_user);

----------------------------------------------------------

-- Full-text search indexes

-- Add column to users to store computed ts_vectors.
ALTER TABLE users
ADD COLUMN tsvectors TSVECTOR;

-- Create a function to automatically update ts_vectors.
CREATE FUNCTION user_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('simple', NEW.username), 'A')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.username <> OLD.username) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('simple', NEW.username), 'A')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

-- Create a trigger before insert or update on users
CREATE TRIGGER user_search_update
 BEFORE INSERT OR UPDATE ON users
 FOR EACH ROW
 EXECUTE PROCEDURE user_search_update();

-- Create a GIN index for ts_vectors.
CREATE INDEX search_user ON users USING GIN (tsvectors);

----------------------------------------------------------

-- Add column to Event to store computed ts_vectors.
ALTER TABLE Event
ADD COLUMN tsvectors TSVECTOR;


CREATE FUNCTION created_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('simple', NEW.id_user::text), 'A')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.id_user <> OLD.id_user) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('simple', NEW.id_user::text), 'A')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

-- Create a trigger before insert or update on Event
CREATE TRIGGER created_search_update
 BEFORE INSERT OR UPDATE ON Event
 FOR EACH ROW
 EXECUTE PROCEDURE created_search_update();

-- Create a GIN index for ts_vectors.
CREATE INDEX search_created ON Event USING GIN (tsvectors);

----------------------------------------------------------

-- Create a function to automatically update ts_vectors
CREATE FUNCTION event_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = to_tsvector('english', NEW.title);
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF (NEW.title <> OLD.title) THEN
            NEW.tsvectors = to_tsvector('english', NEW.title);
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER event_search_update
BEFORE INSERT OR UPDATE ON Event
FOR EACH ROW
EXECUTE PROCEDURE event_search_update();

----------------------------------------------------------


----------------------------------------------------------
-- TRIGGERS 
----------------------------------------------------------

CREATE OR REPLACE FUNCTION user_ticket()
RETURNS TRIGGER AS
$BODY$
DECLARE
    user_ticket_count INT;
    event_ticket_limit INT;
BEGIN
    SELECT COUNT(*), MAX(Event.ticket_limit)
    INTO user_ticket_count, event_ticket_limit
    FROM Ticket
    INNER JOIN Orders ON Orders.id = Ticket.id_order
    INNER JOIN TicketType ON TicketType.id = Ticket.id_ticket_type
    INNER JOIN Event ON Event.id = TicketType.id_event
    WHERE Orders.id_user = (SELECT id_user FROM Orders WHERE id = NEW.id_order)
    AND Event.id = (SELECT id_event FROM TicketType WHERE id = NEW.id_ticket_type);

    IF user_ticket_count >= event_ticket_limit THEN
        RAISE EXCEPTION 'A user can only buy up to ticket limit per event.';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS user_ticket ON Ticket;

CREATE TRIGGER user_ticket
    BEFORE INSERT OR UPDATE ON Ticket
    FOR EACH ROW
    EXECUTE PROCEDURE user_ticket();

----------------------------------------------------------

CREATE FUNCTION admin_event()
RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT *
        FROM admin
        WHERE NEW.id_user = id_user
    ) THEN
        RAISE EXCEPTION 'Admins can´t enroll in an event.';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER admin_event
    BEFORE INSERT OR UPDATE ON EventParticipants
    FOR EACH ROW
    EXECUTE PROCEDURE admin_event();

----------------------------------------------------------

CREATE FUNCTION verify_user_attendance()
RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT *
        FROM EventParticipants
        WHERE NEW.id_user = id_user AND NEW.id_event = id_event
    ) THEN
        RAISE EXCEPTION 'A user can’t join an event that they are already a part of.';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_user_attendance
    BEFORE INSERT OR UPDATE ON EventParticipants
    FOR EACH ROW
    EXECUTE PROCEDURE verify_user_attendance();

----------------------------------------------------------

----------------------------------------------------------
-- TRASACTIONS
----------------------------------------------------------

-- Start a transaction
BEGIN TRANSACTION;

-- Your queries go here
SELECT COUNT(*) FROM TicketType WHERE availability > 0;

SELECT Event.title, Event.description, Event.date
FROM Event
INNER JOIN TicketType ON TicketType.id_event = Event.id
WHERE TicketType.availability > 0
ORDER BY Event.date DESC;

-- Commit the transaction
END TRANSACTION;

-- Create the function that checks the number of options for a poll
CREATE OR REPLACE FUNCTION check_poll_options() RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT COUNT(*) FROM PollOption WHERE id_poll = NEW.id_poll) < 2 THEN
        RAISE EXCEPTION 'A poll must have at least two options.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger that calls the function after each insert on PollOption
CREATE TRIGGER check_poll_options_trigger
AFTER INSERT ON PollOption
FOR EACH ROW
EXECUTE FUNCTION check_poll_options();

-- Start another transaction
BEGIN TRANSACTION;

-- Your queries go here
SELECT COUNT(*)
FROM Event
WHERE now() < Event.date;

SELECT Event.id, Event.title, Event.description, Event.type, Event.date, Event.capacity, Event.ticket_limit, Event.place, Event.id_user
FROM Event
INNER JOIN EventParticipants ON EventParticipants.id_user = Event.id_user
INNER JOIN Authenticated ON Authenticated.id_user = EventParticipants.id_user
WHERE now() < Event.date
ORDER BY Event.date ASC;

-- Commit the second transaction
END TRANSACTION;
