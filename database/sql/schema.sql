DROP SCHEMA IF EXISTS lbaw23144 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw23144;
SET search_path TO lbaw23144;
DROP TABLE IF EXISTS events_Participants CASCADE;
DROP TABLE IF EXISTS Favoriteevents CASCADE;
DROP TABLE IF EXISTS eventsHashtag CASCADE;
DROP TABLE IF EXISTS PollVotes CASCADE;
DROP TABLE IF EXISTS MessageNotification CASCADE;
DROP TABLE IF EXISTS eventsNotification CASCADE;
DROP TABLE IF EXISTS Notification CASCADE;
DROP TABLE IF EXISTS MessageReaction CASCADE;
DROP TABLE IF EXISTS eventsMessage CASCADE;
DROP TABLE IF EXISTS Ticket CASCADE;
DROP TABLE IF EXISTS Hashtag CASCADE;
DROP TABLE IF EXISTS Report CASCADE;
DROP TABLE IF EXISTS PollOption CASCADE;
DROP TABLE IF EXISTS Poll CASCADE;
DROP TABLE IF EXISTS File CASCADE;
DROP TABLE IF EXISTS authenticateds CASCADE;
DROP TABLE IF EXISTS Admin CASCADE;
DROP TABLE IF EXISTS events CASCADE;
DROP TABLE IF EXISTS Users CASCADE;
DROP TABLE IF EXISTS OrderDetail CASCADE;
DROP TABLE IF EXISTS TicketType CASCADE;
DROP TABLE IF EXISTS Orders CASCADE;
DROP TYPE IF EXISTS TypesNotification CASCADE;
DROP TYPE IF EXISTS TypesMessage CASCADE;
DROP TYPE IF EXISTS Typesevents CASCADE;
-- DROP FUNCTION IF EXISTS check_organizer_enrollment CASCADE;

-- Create types
CREATE TYPE Typesevents AS ENUM ('public', 'private', 'approval');
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

CREATE TABLE authenticateds (
    id_user INT PRIMARY KEY,
    is_verified BOOLEAN DEFAULT FALSE,
    id_profilepic INT DEFAULT 0,
    FOREIGN KEY (id_user) REFERENCES Users(id)
);

CREATE TABLE events (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    type Typesevents DEFAULT 'public',
    date DATE NOT NULL,
    capacity INT NOT NULL,
    ticket_limit INT ,
    place VARCHAR(255) NOT NULL,
    id_user INT NOT NULL,
    CHECK (ticket_limit <= capacity),
    FOREIGN KEY (id_user) REFERENCES authenticateds(id_user)
);

CREATE TABLE eventsMessage (
    id SERIAL PRIMARY KEY,
    type TypesMessage NOT NULL,
    content TEXT NOT NULL,
    id_event INT NOT NULL,
    id_user INT NOT NULL,
    date DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (id_event) REFERENCES events(id),
    FOREIGN KEY (id_user) REFERENCES authenticateds(id_user)
);

CREATE TABLE MessageReaction (
    id_user INT NOT NULL,
    id_message INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES authenticateds(id_user),
    FOREIGN KEY (id_message) REFERENCES eventsMessage(id),
    PRIMARY KEY (id_user, id_message)
);

CREATE TABLE Notification (
    id SERIAL PRIMARY KEY
);

CREATE TABLE eventsNotification (
    id INT NOT NULL,
    id_user INT NOT NULL,
    id_event INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES authenticateds(id_user),
    FOREIGN KEY (id_event) REFERENCES events(id),
    FOREIGN KEY (id) REFERENCES Notification(id),
    PRIMARY KEY (id)
);

CREATE TABLE MessageNotification (
    id INT NOT NULL,
    id_user INT NOT NULL,
    id_message INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES authenticateds(id_user),
    FOREIGN KEY (id_message) REFERENCES eventsMessage(id),
    FOREIGN KEY (id) REFERENCES Notification(id),
    PRIMARY KEY (id)
);

CREATE TABLE events_Participants (
    id_user INT NOT NULL,
    id_event INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES authenticateds(id_user),
    FOREIGN KEY (id_event) REFERENCES events(id),
    PRIMARY KEY (id_user, id_event)
);

CREATE TABLE Favoriteevents (
    id_user INT NOT NULL,
    id_event INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES authenticateds(id_user),
    FOREIGN KEY (id_event) REFERENCES events(id),
    PRIMARY KEY (id_user, id_event)
);

CREATE TABLE Hashtag (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL
);

CREATE TABLE eventsHashtag (
    id_event INT NOT NULL,
    id_hashtag INT NOT NULL,
    FOREIGN KEY (id_event) REFERENCES events(id),
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
    FOREIGN KEY (id_event) REFERENCES events(id)
);

CREATE TABLE Orders (
    id SERIAL PRIMARY KEY,
    order_number INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES authenticateds(id_user)
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

CREATE TABLE Report (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    id_user INT NOT NULL,
    id_event INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES authenticateds(id_user),
    FOREIGN KEY (id_event) REFERENCES events(id)
);

CREATE TABLE File (
    id SERIAL PRIMARY KEY,
    path VARCHAR(255) NOT NULL,
    id_user INT NOT NULL,
    id_message INT,
    id_report INT,
    id_event INT,
    FOREIGN KEY (id_user) REFERENCES authenticateds(id_user),
    FOREIGN KEY (id_message) REFERENCES eventsMessage(id),
    FOREIGN KEY (id_report) REFERENCES Report(id),
    FOREIGN KEY (id_event) REFERENCES events(id),
    CHECK (
        (id_message IS NOT NULL AND id_event IS NULL AND id_report IS NULL) OR
        (id_message IS NULL AND id_event IS NOT NULL AND id_report IS NULL) OR
        (id_message IS NULL AND id_event IS NULL AND id_report IS NOT NULL)
    )
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
    FOREIGN KEY (id_event) REFERENCES events(id),
    FOREIGN KEY (id_user) REFERENCES authenticateds(id_user)
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
    FOREIGN KEY (id_user) REFERENCES authenticateds(id_user),
    FOREIGN KEY (id_option) REFERENCES PollOption(id),
    PRIMARY KEY (id_user, id_option)
);

/* ----------------------------------------------------------
-- INDEXES
----------------------------------------------------------

-- Performance indexes

CREATE INDEX events_manager_id ON events USING btree (id_user);
CREATE INDEX events_participant_id ON events_Participants USING btree (id_user, id_event);
CREATE INDEX date_events ON events USING btree (date);
CREATE INDEX orders_user_id ON orders USING hash (id_user);

----------------------------------------------------------

-- Full-text search indexes

-- Add column to Users to store computed ts_vectors.
ALTER TABLE Users
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

-- Create a trigger before insert or update on Users
CREATE TRIGGER user_search_update
 BEFORE INSERT OR UPDATE ON Users
 FOR EACH ROW
 EXECUTE PROCEDURE user_search_update();

-- Create a GIN index for ts_vectors.
CREATE INDEX search_user ON Users USING GIN (tsvectors);

----------------------------------------------------------

-- Add column to events to store computed ts_vectors.
ALTER TABLE events
ADD COLUMN tsvectors TSVECTOR;

-- Create a function to automatically update ts_vectors.
-- Create a function to automatically update ts_vectors.
CREATE FUNCTION Created_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('simple', NEW.id_user::text), 'A')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.username <> OLD.username) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('simple', NEW.id_user::text), 'A')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

-- Create a trigger before insert or update on events
CREATE TRIGGER Created_search_update
 BEFORE INSERT OR UPDATE ON events
 FOR EACH ROW
 EXECUTE PROCEDURE Created_search_update();

-- Create a GIN index for ts_vectors.
CREATE INDEX search_created ON events USING GIN (tsvectors);

----------------------------------------------------------

-- Create a function to automatically update ts_vectors
CREATE FUNCTION events_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = to_tsvector('portuguese', NEW.title);
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF (NEW.title <> OLD.title) THEN
            NEW.tsvectors = to_tsvector('portuguese', NEW.title);
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create a trigger to call the events_search_update function before INSERT or UPDATE on the events table
CREATE TRIGGER events_search_update
BEFORE INSERT OR UPDATE ON events
FOR EACH ROW
EXECUTE PROCEDURE events_search_update();

----------------------------------------------------------


----------------------------------------------------------
-- TRIGGERS 
----------------------------------------------------------

CREATE OR REPLACE FUNCTION user_ticket()
RETURNS TRIGGER AS
$BODY$
DECLARE
    user_ticket_count INT;
    events_ticket_limit INT;
BEGIN
    SELECT COUNT(*), MAX(events.ticket_limit)
    INTO user_ticket_count, events_ticket_limit
    FROM Ticket
    INNER JOIN Orders ON Orders.id = Ticket.id_order
    INNER JOIN TicketType ON TicketType.id = Ticket.id_ticket_type
    INNER JOIN events ON events.id = TicketType.id_event
    WHERE Orders.id_user = (SELECT id_user FROM Orders WHERE id = NEW.id_order)
    AND events.id = (SELECT id_event FROM TicketType WHERE id = NEW.id_ticket_type);

    IF user_ticket_count >= events_ticket_limit THEN
        RAISE EXCEPTION 'A user can only buy up to ticket limit per events.';
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

CREATE FUNCTION admin_events()
RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT *
        FROM Admin
        WHERE NEW.id_user = id_user
    ) THEN
        RAISE EXCEPTION 'Admins can´t enroll in an events.';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER admin_events
    BEFORE INSERT OR UPDATE ON events
    FOR EACH ROW
    EXECUTE PROCEDURE admin_events();

----------------------------------------------------------

CREATE FUNCTION verify_user_attendance()
RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT *
        FROM events_Participants
        WHERE NEW.id_user = id_user AND NEW.id_event = id_event
    ) THEN
        RAISE EXCEPTION 'A user can’t join an events that they are already a part of.';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_user_attendance
    BEFORE INSERT OR UPDATE ON events_Participants
    FOR EACH ROW
    EXECUTE PROCEDURE verify_user_attendance();

----------------------------------------------------------


CREATE OR REPLACE FUNCTION check_organizer_enrollment() RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT COUNT(*) FROM events_Participants WHERE id_user = NEW.id_user AND id_event = NEW.id_event) = 0 THEN
        RAISE EXCEPTION 'The events organizer must be enrolled in the events.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER check_organizer_enrollment_trigger
BEFORE INSERT ON events_Participants
FOR EACH ROW
EXECUTE FUNCTION check_organizer_enrollment(); 

----------------------------------------------------------

----------------------------------------------------------
-- TRASACTIONS
----------------------------------------------------------

-- Start a transaction
BEGIN TRANSACTION;

-- Your queries go here
SELECT COUNT(*) FROM TicketType WHERE availability > 0;

SELECT events.title, events.description, events.date
FROM events
INNER JOIN TicketType ON TicketType.id_event = events.id
WHERE TicketType.availability > 0
ORDER BY events.date DESC;

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
FROM events
WHERE now() < events.date;

SELECT events.id, events.title, events.description, events.type, events.date, events.capacity, events.ticket_limit, events.place, events.id_user
FROM events
INNER JOIN events_Participants ON events_Participants.id_user = events.id_user
INNER JOIN authenticateds ON authenticateds.id_user = events_Participants.id_user
WHERE now() < events.date
ORDER BY events.date ASC;

-- Commit the second transaction
END TRANSACTION; */

insert into Users (id, email, name, username, password) values (1, 'rkeach0@cmu.edu', 'Reinhard Keach', 'rkeach0', 'zN2=8na_.nA');
insert into Users (id, email, name, username, password) values (2, 'smacascaidh1@canalblog.com', 'Shela MacAscaidh', 'smacascaidh1', 'eH9/w1_.OTG2NCm');
insert into Users (id, email, name, username, password) values (3, 'odykins2@berkeley.edu', 'Ole Dykins', 'odykins2', 'yZ7}(O@xl@"|S');
insert into Users (id, email, name, username, password) values (4, 'rissacson3@time.com', 'Ross Issacson', 'rissacson3', 'kX7*)3ShR8Q=n');
insert into Users (id, email, name, username, password) values (5, 'ahendrickx4@gmpg.org', 'Austin Hendrickx', 'ahendrickx4', 'hH8}Nc7F{?StH5');
insert into Users (id, email, name, username, password) values (6, 'dplewright5@ocn.ne.jp', 'Daphene Plewright', 'dplewright5', 'xE5}NCJQ|M');
insert into Users (id, email, name, username, password) values (7, 'hovitts6@constantcontact.com', 'Hortense Ovitts', 'hovitts6', 'eL2*Z~.CC_xp>Hf{');
insert into Users (id, email, name, username, password) values (8, 'dwaistall7@sina.com.cn', 'Denney Waistall', 'dwaistall7', 'qW1(CUbx5)');
insert into Users (id, email, name, username, password) values (9, 'cbraunter8@multiply.com', 'Cristobal Braunter', 'cbraunter8', 'tQ7&`#/3L`6H');
insert into Users (id, email, name, username, password) values (10, 'gthormwell9@4shared.com', 'Granville Thormwell', 'gthormwell9', 'wC3}G0V}D"`''$');
insert into Users (id, email, name, username, password) values (11, 'gjentzscha@google.co.uk', 'Giselle Jentzsch', 'gjentzscha', 'vL8,$~aI''AV');
insert into Users (id, email, name, username, password) values (12, 'dswettb@yellowpages.com', 'Dyanne Swett', 'dswettb', 'xX6\rf6zQ');
insert into Users (id, email, name, username, password) values (13, 'pcoodec@unicef.org', 'Putnam Coode', 'pcoodec', 'cG4*0T_V');
insert into Users (id, email, name, username, password) values (14, 'chamild@wunderground.com', 'Clarie Hamil', 'chamild', 'xW4?xsdLbA7');
insert into Users (id, email, name, username, password) values (15, 'hlorrymane@ocn.ne.jp', 'Hilly Lorryman', 'hlorrymane', 'gP0,xTJ(OdO5D"');
insert into Users (id, email, name, username, password) values (16, 'lansellf@last.fm', 'Leonard Ansell', 'lansellf', 'cA1{/e}1`oC8+xH');
insert into Users (id, email, name, username, password) values (17, 'twinchcombeg@elegantthemes.com', 'Tan Winchcombe', 'twinchcombeg', 'tI6%<7xT2"Q$SF');
insert into Users (id, email, name, username, password) values (18, 'ctreneerh@mozilla.com', 'Cinda Treneer', 'ctreneerh', 'aU1_SP.i''*cH{_');
insert into Users (id, email, name, username, password) values (19, 'hsandesoni@elpais.com', 'Humberto Sandeson', 'hsandesoni', 'mB2&+Zr&oI6h<0');
insert into Users (id, email, name, username, password) values (20, 'sbosselj@ehow.com', 'Shari Bossel', 'sbosselj', 'lO6(f|1.,kOT5e');
insert into Users (id, email, name, username, password) values (21, 'gzupak@ameblo.jp', 'Goldie Zupa', 'gzupak', 'uQ6=H=X=3v9xF');
insert into Users (id, email, name, username, password) values (22, 'pwearl@sciencedirect.com', 'Peggi Wear', 'pwearl', 'cC8,|OOt1qlv');
insert into Users (id, email, name, username, password) values (23, 'sbrugmanm@cafepress.com', 'Sheelagh Brugman', 'sbrugmanm', 'gQ7$''ZJ/F');
insert into Users (id, email, name, username, password) values (24, 'ddelln@harvard.edu', 'Darrel Dell Casa', 'ddelln', 'pK9$#r8eh');
insert into Users (id, email, name, username, password) values (25, 'cbartio@buzzfeed.com', 'Christalle Barti', 'cbartio', 'sD8$tZ(j\eM');
insert into Users (id, email, name, username, password) values (26, 'amarfieldp@google.com.au', 'Alexis Marfield', 'amarfieldp', 'eO9@?zAYrsj9o');
insert into Users (id, email, name, username, password) values (27, 'emaunq@go.com', 'Emerson Maun', 'emaunq', 'hV3/1hnASd6BI');
insert into Users (id, email, name, username, password) values (28, 'mconklinr@samsung.com', 'Merilee Conklin', 'mconklinr', 'gF7?<.77M+3Ny$');
insert into Users (id, email, name, username, password) values (29, 'wranshaws@indiatimes.com', 'Wendi Ranshaw', 'wranshaws', 'qT0.peFBPYnJaA*%');
insert into Users (id, email, name, username, password) values (30, 'kescolmet@google.ru', 'Kerwin Escolme', 'kescolmet', 'eH3,NUx$4>YZ');
insert into Users (id, email, name, username, password) values (31, 'ykardosu@addthis.com', 'Yolanthe Kardos', 'ykardosu', 'oH2+%oX.U');
insert into Users (id, email, name, username, password) values (32, 'fvickermanv@umn.edu', 'Fanechka Vickerman', 'fvickermanv', 'oM9@!b>{sW');
insert into Users (id, email, name, username, password) values (33, 'nmckelveyw@jugem.jp', 'Natka McKelvey', 'nmckelveyw', 'lM9\ovy&C8eT');
insert into Users (id, email, name, username, password) values (34, 'sissakovx@issuu.com', 'Sisile Issakov', 'sissakovx', 'cB5\dMZ9*');
insert into Users (id, email, name, username, password) values (35, 'hverniy@gov.uk', 'Harrie Verni', 'hverniy', 'eL8=p|qM%''0F$P');
insert into Users (id, email, name, username, password) values (36, 'jstanmerz@auda.org.au', 'Julie Stanmer', 'jstanmerz', 'lV5`Q5%`Ib\to');
insert into Users (id, email, name, username, password) values (37, 'emcgarrahan10@omniture.com', 'Ellette McGarrahan', 'emcgarrahan10', 'gZ6<oO0oWJ#V1h.');
insert into Users (id, email, name, username, password) values (38, 'hbatter11@hc360.com', 'Hilly Batter', 'hbatter11', 'wZ9/KFE/th+lJ');
insert into Users (id, email, name, username, password) values (39, 'pjordison12@princeton.edu', 'Penrod Jordison', 'pjordison12', 'eQ7,)6BsHcaf9\`4');
insert into Users (id, email, name, username, password) values (40, 'mgiral13@linkedin.com', 'Margi Giral', 'mgiral13', 'zO1{$G&0''oo|=');
insert into Users (id, email, name, username, password) values (41, 'hfothergill14@hatena.ne.jp', 'Hillery Fothergill', 'hfothergill14', 'sE1.%a6lCT`');
insert into Users (id, email, name, username, password) values (42, 'tgery15@51.la', 'Townie Gery', 'tgery15', 'hT2)UlR/.qT');
insert into Users (id, email, name, username, password) values (43, 'kmethringham16@taobao.com', 'Ketty Methringham', 'kmethringham16', 'nA0,XUn#U}{TkSG');
insert into Users (id, email, name, username, password) values (44, 'lash17@linkedin.com', 'Laney Ash', 'lash17', 'cC6"{8X5c+Z');
insert into Users (id, email, name, username, password) values (45, 'bhowman18@privacy.gov.au', 'Brent Howman', 'bhowman18', 'hO7{Y)4rX');
insert into Users (id, email, name, username, password) values (46, 'jrobe19@tumblr.com', 'Jacky Robe', 'jrobe19', 'iO1?T$lcV4e');
insert into Users (id, email, name, username, password) values (47, 'dpalek1a@shutterfly.com', 'Davin Palek', 'dpalek1a', 'yV5}/>&$$SQ<x');
insert into Users (id, email, name, username, password) values (48, 'ccockings1b@mapquest.com', 'Corilla Cockings', 'ccockings1b', 'fD0{ZOkRQ');
insert into Users (id, email, name, username, password) values (49, 'czanazzi1c@tuttocitta.it', 'Conroy Zanazzi', 'czanazzi1c', 'vS1_>?+s`{d');
insert into Users (id, email, name, username, password) values (50, 'kstamp1d@4shared.com', 'Kristyn Stamp', 'kstamp1d', 'mV8>N6\(');

insert into Admin (id_user) values (1);

insert into authenticateds (id_user, is_verified, id_profilepic) values (2, false, 7);
insert into authenticateds (id_user, is_verified, id_profilepic) values (3, false, 31);
insert into authenticateds (id_user, is_verified, id_profilepic) values (4, false, 39);
insert into authenticateds (id_user, is_verified, id_profilepic) values (5, false, 7);
insert into authenticateds (id_user, is_verified, id_profilepic) values (6, true, 5);
insert into authenticateds (id_user, is_verified, id_profilepic) values (7, false, 50);
insert into authenticateds (id_user, is_verified, id_profilepic) values (8, false, 48);
insert into authenticateds (id_user, is_verified, id_profilepic) values (9, false, 35);
insert into authenticateds (id_user, is_verified, id_profilepic) values (10, true, 27);
insert into authenticateds (id_user, is_verified, id_profilepic) values (11, true, 33);
insert into authenticateds (id_user, is_verified, id_profilepic) values (12, true, 8);
insert into authenticateds (id_user, is_verified, id_profilepic) values (13, true, 38);
insert into authenticateds (id_user, is_verified, id_profilepic) values (14, false, 37);
insert into authenticateds (id_user, is_verified, id_profilepic) values (15, true, 11);
insert into authenticateds (id_user, is_verified, id_profilepic) values (16, true, 26);
insert into authenticateds (id_user, is_verified, id_profilepic) values (17, true, 34);
insert into authenticateds (id_user, is_verified, id_profilepic) values (18, true, 39);
insert into authenticateds (id_user, is_verified, id_profilepic) values (19, true, 46);
insert into authenticateds (id_user, is_verified, id_profilepic) values (20, true, 38);
insert into authenticateds (id_user, is_verified, id_profilepic) values (21, true, 17);
insert into authenticateds (id_user, is_verified, id_profilepic) values (22, true, 36);
insert into authenticateds (id_user, is_verified, id_profilepic) values (23, false, 17);
insert into authenticateds (id_user, is_verified, id_profilepic) values (24, false, 46);
insert into authenticateds (id_user, is_verified, id_profilepic) values (25, false, 12);
insert into authenticateds (id_user, is_verified, id_profilepic) values (26, false, 38);
insert into authenticateds (id_user, is_verified, id_profilepic) values (27, true, 49);
insert into authenticateds (id_user, is_verified, id_profilepic) values (28, false, 41);
insert into authenticateds (id_user, is_verified, id_profilepic) values (29, true, 11);
insert into authenticateds (id_user, is_verified, id_profilepic) values (30, false, 2);
insert into authenticateds (id_user, is_verified, id_profilepic) values (31, false, 15);
insert into authenticateds (id_user, is_verified, id_profilepic) values (32, false, 18);
insert into authenticateds (id_user, is_verified, id_profilepic) values (33, true, 36);
insert into authenticateds (id_user, is_verified, id_profilepic) values (34, false, 45);
insert into authenticateds (id_user, is_verified, id_profilepic) values (35, true, 40);
insert into authenticateds (id_user, is_verified, id_profilepic) values (36, false, 29);
insert into authenticateds (id_user, is_verified, id_profilepic) values (37, true, 36);
insert into authenticateds (id_user, is_verified, id_profilepic) values (38, false, 27);
insert into authenticateds (id_user, is_verified, id_profilepic) values (39, false, 29);
insert into authenticateds (id_user, is_verified, id_profilepic) values (40, true, 46);
insert into authenticateds (id_user, is_verified, id_profilepic) values (41, false, 35);
insert into authenticateds (id_user, is_verified, id_profilepic) values (42, true, 25);
insert into authenticateds (id_user, is_verified, id_profilepic) values (43, false, 46);
insert into authenticateds (id_user, is_verified, id_profilepic) values (44, true, 41);
insert into authenticateds (id_user, is_verified, id_profilepic) values (45, true, 24);
insert into authenticateds (id_user, is_verified, id_profilepic) values (46, true, 39);
insert into authenticateds (id_user, is_verified, id_profilepic) values (47, true, 30);
insert into authenticateds (id_user, is_verified, id_profilepic) values (48, false, 39);
insert into authenticateds (id_user, is_verified, id_profilepic) values (49, true, 28);
insert into authenticateds (id_user, is_verified, id_profilepic) values (50, false, 33);

insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (1, 'Explorers', 'Athletics meet at ABC stadium', 'public', '11/28/2022', 244, 2, 'Sacramento', 3);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (2, 'Six in Paris (Paris vu par...)', 'Rugby match between Team P and Team Q', 'public', '8/29/2023', 405, 5, 'Mnogoudobnoye', 27);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (3, 'Crime of Monsieur Lange, The (Le crime de Monsieur Lange)', 'Basketball game featuring Team X and Team Y', 'public', '7/12/2023', 80, 5, 'Orléans', 32);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (4, 'Downstairs', 'Cricket match between Country A and Country B', 'public', '6/12/2023', 295, 5, 'Vadstena', 33);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (5, 'Fastest Gun Alive, The', 'Rugby match between Team P and Team Q', 'approval', '8/27/2023', 467, 4, 'Grenoble', 36);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (6, 'Barbarian Invasions, The (Les invasions barbares)', 'Cricket match between Country A and Country B', 'approval', '8/18/2023', 268, 5, 'Danchang', 15);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (7, 'Jim Breuer: And Laughter for All', 'Tennis tournament finals', 'private', '6/15/2023', 52, 1, 'Palmas De Gran Canaria, Las', 33);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (8, 'Touchy Feely', 'Cricket match between Country A and Country B', 'approval', '4/20/2023', 373, 2, 'Pastores', 37);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (9, 'Nanny, The', 'Basketball game featuring Team X and Team Y', 'approval', '12/12/2022', 94, 2, 'Sośnicowice', 21);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (10, 'The Man From The Alamo', 'Basketball game featuring Team X and Team Y', 'private', '9/27/2023', 10, 2, 'Phoenix', 8);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (11, 'Off and Running ', 'Basketball game featuring Team X and Team Y', 'private', '12/5/2022', 421, 1, 'Motomiya', 40);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (12, 'Wizard of Oz, The', 'Athletics meet at ABC stadium', 'public', '3/10/2023', 200, 1, 'Ban Na San', 48);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (13, 'Hold Back the Dawn', 'Basketball game featuring Team X and Team Y', 'approval', '11/8/2022', 252, 5, 'Barakani', 49);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (14, 'Faith School Menace?', 'Volleyball tournament finals', 'approval', '5/5/2023', 490, 5, 'Roche Terre', 22);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (15, 'Last Dragon, The', 'Basketball game featuring Team X and Team Y', 'private', '3/5/2023', 385, 5, 'Vyzhnytsya', 49);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (16, 'DuckTales: The Movie - Treasure of the Lost Lamp', 'Golf championship at XYZ course', 'approval', '6/4/2023', 216, 3, 'Kungsbacka', 17);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (17, 'Salvage', 'Cricket match between Country A and Country B', 'approval', '3/9/2023', 426, 1, 'Kilim', 9);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (18, 'Goldene Zeiten', 'Athletics meet at ABC stadium', 'public', '10/2/2023', 50, 3, 'Baras', 50);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (19, 'While You Were Sleeping', 'Baseball game at ABC stadium', 'approval', '4/11/2023', 249, 3, 'Boracéia', 39);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (20, 'Glenn Miller Story, The', 'Cricket match between Country A and Country B', 'private', '3/13/2023', 191, 3, 'Kinsealy-Drinan', 31);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (21, 'Bowling for Columbine', 'Rugby match between Team P and Team Q', 'private', '1/20/2023', 78, 2, 'Muararupit', 40);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (22, 'Bad Words', 'Athletics meet at ABC stadium', 'approval', '10/24/2023', 238, 5, 'Vanves', 15);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (23, 'Dark Victory', 'Basketball game featuring Team X and Team Y', 'approval', '4/1/2023', 488, 2, 'Zhenzhushan', 39);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (24, 'Late George Apley, The', 'Baseball game at ABC stadium', 'approval', '10/20/2023', 122, 4, 'Checca', 17);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (25, 'African Queen, The', 'Rugby match between Team P and Team Q', 'public', '4/11/2023', 341, 2, 'Gia Nghĩa', 34);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (26, 'Time Bandits', 'Basketball game featuring Team X and Team Y', 'private', '4/6/2023', 407, 5, 'Yanjiang', 14);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (27, 'Slappy and the Stinkers', 'Volleyball tournament finals', 'approval', '2/19/2023', 398, 5, 'Naga', 37);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (28, 'Blackbird, The', 'Athletics meet at ABC stadium', 'private', '2/1/2023', 421, 1, 'Tours', 49);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (29, 'Live and Let Die', 'Baseball game at ABC stadium', 'public', '11/16/2022', 486, 1, 'Saint-Laurent-du-Var', 13);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (30, 'Walking Tall', 'Basketball game featuring Team X and Team Y', 'private', '8/4/2023', 337, 2, 'København', 8);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (31, 'Bachelor Party, The', 'Swimming competition at XYZ pool', 'approval', '4/19/2023', 478, 3, 'Sigma', 47);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (32, 'Passengers', 'Cricket match between Country A and Country B', 'private', '6/16/2023', 276, 5, 'Kasungu', 10);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (33, 'Presidentintekijät', 'Athletics meet at ABC stadium', 'public', '6/28/2023', 196, 5, 'Szczerców', 11);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (34, 'Queen of the Damned', 'Football match between Team A and Team B', 'approval', '11/24/2022', 325, 2, 'Tāklisah', 24);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (35, 'Long Weekend, The', 'Cricket match between Country A and Country B', 'approval', '3/2/2023', 119, 5, 'San José Poaquil', 34);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (36, 'Yanco ', 'Baseball game at ABC stadium', 'public', '4/2/2023', 166, 5, 'Loa Janan', 40);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (37, 'Highlander: The Source', 'Rugby match between Team P and Team Q', 'public', '5/12/2023', 117, 2, 'Lishui', 29);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (38, 'Fairly Odd Christmas, A', 'Football match between Team A and Team B', 'approval', '11/20/2022', 447, 4, 'Snegiri', 2);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (39, 'Duchess, The', 'Tennis tournament finals', 'approval', '9/1/2023', 76, 3, 'Kota Kinabalu', 30);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (40, 'Swamp Shark', 'Athletics meet at ABC stadium', 'private', '3/31/2023', 59, 4, 'Yinying', 39);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (41, 'Instrument', 'Baseball game at ABC stadium', 'approval', '1/2/2023', 4, 2, 'Montbrison', 46);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (42, 'Little Nicholas (Le petit Nicolas)', 'Rugby match between Team P and Team Q', 'public', '5/13/2023', 281, 4, 'Umanggudang', 45);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (43, 'I Love You Too', 'Golf championship at XYZ course', 'public', '6/19/2023', 21, 2, 'Otjiwarongo', 16);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (44, 'Backcountry', 'Tennis tournament finals', 'private', '4/24/2023', 441, 3, 'Tsagaan-Olom', 49);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (45, 'Dirty Sanchez: The Movie', 'Baseball game at ABC stadium', 'approval', '3/15/2023', 295, 2, 'Thākurgaon', 15);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (46, 'Last Hard Men, The', 'Baseball game at ABC stadium', 'private', '6/22/2023', 424, 4, 'Paiçandu', 46);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (47, 'Kiss Before Dying, A', 'Athletics meet at ABC stadium', 'public', '6/17/2023', 162, 1, 'Khairpur', 41);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (48, 'Head Above Water', 'Athletics meet at ABC stadium', 'private', '4/24/2023', 159, 3, 'Xuhang', 32);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (49, 'Let the Fire Burn', 'Volleyball tournament finals', 'approval', '6/16/2023', 76, 5, 'Al Nashmah', 37);
insert into events (id, title, description, type, date, capacity, ticket_limit, place, id_user) values (50, 'Breath (Soom)', 'Athletics meet at ABC stadium', 'private', '3/4/2023', 319, 5, 'Rodas', 14);


insert into events_Participants (id_user, id_event) values (38, 34);
insert into events_Participants (id_user, id_event) values (15, 31);
insert into events_Participants (id_user, id_event) values (35, 6);
insert into events_Participants (id_user, id_event) values (24, 43);
insert into events_Participants (id_user, id_event) values (3, 38);
insert into events_Participants (id_user, id_event) values (29, 39);
insert into events_Participants (id_user, id_event) values (38, 50);
insert into events_Participants (id_user, id_event) values (28, 12);
insert into events_Participants (id_user, id_event) values (35, 27);
