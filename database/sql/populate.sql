-- Insert data into the table "user"
insert into Users (email, name, username, password) values ('admin@example.com', 'admin123', 'admin123', '$2y$10$LpU/dOWOp/u/Vsr5KhrhqOXWJh49lzDa8uo4tqr4fnv6Lm2QEkNFm');
insert into Users (email, name, username, password) values ('user@example.com', 'user', 'user', '$2y$10$1TKDy33AEW30JtqwrWva/.VauRo1vzVSUIfsPBizJmyU4cVCPsX2a');
insert into Users (email, name, username, password) values ('scp@example.com', 'Sporting Clube de Portugal', 'sporting', '1');
insert into Users (email, name, username, password) values ('diogo@example.com', 'Diogo', 'diogo', '2');
insert into Users (email, name, username, password) values ('tomas@example.com', 'Tomas', 'tomas', '3');
insert into Users (email, name, username, password) values ('joao@example.com', 'Joao', 'joao', '4');
insert into Users (email, name, username, password) values ('edson@example.com', 'Edson', 'edson', '5');
insert into Users (email, name, username, password) values ('baguim@example.com', 'Freguesia de Baguim', 'baguim', '6');
insert into Users (email, name, username, password) values ('fcp@example.com', 'Futebol Clube do Porto', 'fcporto', '7');
insert into Users (email, name, username, password) values ('slb@example.com', 'Sport Lisboa e Benfica', 'benfica', '8');
insert into Users (email, name, username, password) values ('porto@example.com', 'Freguesia do Porto', 'porto', '9');
insert into Users (email, name, username, password) values ('rtparena@example.com', 'RTP Arena', 'rtparena', '10');
insert into Users (email, name, username, password) values ('corridassolitarias@example.com', 'Corridas Solitarias', 'corridassolitarias', '-');
insert into Users (email, name, username, password) values ('ana@example.com', 'Ana', 'ana', '11');
insert into Users (email, name, username, password) values ('ricardo@example.com', 'Ricardo', 'ricardo', '12');
insert into Users (email, name, username, password) values ('maria@example.com', 'Maria', 'maria', '13');
insert into Users (email, name, username, password) values ('pedro@example.com', 'Pedro', 'pedro', '14');
insert into Users (email, name, username, password) values ('sofia@example.com', 'Sofia', 'sofia', '15');
insert into Users (email, name, username, password) values ('carla@example.com', 'Carla', 'carla', '16');
insert into Users (email, name, username, password) values ('miguel@example.com', 'Miguel', 'miguel', '17');
insert into Users (email, name, username, password) values ('sara@example.com', 'Sara', 'sara', '18');
insert into Users (email, name, username, password) values ('andre@example.com', 'Andre', 'andre', '19');
insert into Users (email, name, username, password) values ('ana2@example.com', 'Ana', 'ana2', '20');
insert into Users (email, name, username, password) values ('luis@example.com', 'Luis', 'luis', '21');
insert into Users (email, name, username, password) values ('mariana@example.com', 'Mariana', 'mariana', '22');
insert into Users (email, name, username, password) values ('roberto@example.com', 'Roberto', 'roberto', '23');
insert into Users (email, name, username, password) values ('carolina@example.com', 'Carolina', 'carolina', '24');
insert into Users (email, name, username, password) values ('nuno@example.com', 'Nuno', 'nuno', '25');
insert into Users (email, name, username, password) values ('catarina@example.com', 'Catarina', 'catarina', '26');
insert into Users (email, name, username, password) values ('jose@example.com', 'Jose', 'jose', '27');
insert into Users (email, name, username, password) values ('patricia@example.com', 'Patricia', 'patricia', '28');
insert into Users (email, name, username, password) values ('pedrina@example.com', 'Pedrina', 'pedrina', '29');
insert into Users (email, name, username, password) values ('manuel@example.com', 'Manuel', 'manuel', '30');
insert into Users (email, name, username, password) values ('filipe@example.com', 'Filipe', 'filipe', '31');
insert into Users (email, name, username, password) values ('ana3@example.com', 'Ana', 'ana3', '32');
insert into Users (email, name, username, password) values ('carlos@example.com', 'Carlos', 'carlos', '33');
insert into Users (email, name, username, password) values ('marta@example.com', 'Marta', 'marta', '34');
insert into Users (email, name, username, password) values ('ricardo2@example.com', 'Ricardo', 'ricardo2', '35');
insert into Users (email, name, username, password) values ('susana@example.com', 'Susana', 'susana', '36');
insert into Users (email, name, username, password) values ('joaquim@example.com', 'Joaquim', 'joaquim', '37');
insert into Users (email, name, username, password) values ('laura@example.com', 'Laura', 'laura', '38');
insert into Users (email, name, username, password) values ('antonio@example.com', 'Antonio', 'antonio', '39');
insert into Users (email, name, username, password) values ('marisa@example.com', 'Marisa', 'marisa', '40');

-- Insert data into the table "admin"
insert into Admin (id_user) values (1);

-- Insert data into the table "authenticated"
insert into Authenticated (id_user, is_verified) values (2, false);
insert into Authenticated (id_user, is_verified) values (3, true);
insert into Authenticated (id_user, is_verified) values (4, false);
insert into Authenticated (id_user, is_verified) values (5, false);
insert into Authenticated (id_user, is_verified) values (6, false);
insert into Authenticated (id_user, is_verified) values (7, false);
insert into Authenticated (id_user, is_verified) values (8, true);
insert into Authenticated (id_user, is_verified) values (9, true);
insert into Authenticated (id_user, is_verified) values (10, true);
insert into Authenticated (id_user, is_verified) values (11, true); 
insert into Authenticated (id_user, is_verified) values (12, true);
insert into Authenticated (id_user, is_verified) values (13, true);
insert into Authenticated (id_user, is_verified) values (14, false);
insert into Authenticated (id_user, is_verified) values (15, false);
insert into Authenticated (id_user, is_verified) values (16, false);
insert into Authenticated (id_user, is_verified) values (17, true);
insert into Authenticated (id_user, is_verified) values (18, false);
insert into Authenticated (id_user, is_verified) values (19, true);
insert into Authenticated (id_user, is_verified) values (20, false);
insert into Authenticated (id_user, is_verified) values (21, true);
insert into Authenticated (id_user, is_verified) values (22, false);
insert into Authenticated (id_user, is_verified) values (23, true);
insert into Authenticated (id_user, is_verified) values (24, false);
insert into Authenticated (id_user, is_verified) values (25, false);
insert into Authenticated (id_user, is_verified) values (26, true);
insert into Authenticated (id_user, is_verified) values (27, false);
insert into Authenticated (id_user, is_verified) values (28, true);
insert into Authenticated (id_user, is_verified) values (29, true);
insert into Authenticated (id_user, is_verified) values (30, false);
insert into Authenticated (id_user, is_verified) values (31, false);
insert into Authenticated (id_user, is_verified) values (32, true);
insert into Authenticated (id_user, is_verified) values (33, false);
insert into Authenticated (id_user, is_verified) values (34, true);
insert into Authenticated (id_user, is_verified) values (35, false);
insert into Authenticated (id_user, is_verified) values (36, true);
insert into Authenticated (id_user, is_verified) values (37, false);
insert into Authenticated (id_user, is_verified) values (38, true);
insert into Authenticated (id_user, is_verified) values (39, true);
insert into Authenticated (id_user, is_verified) values (40, false);

-- Insert data into the table "hashtag"
insert into Hashtag (title) values ('football');
insert into Hashtag (title) values ('esports');
insert into Hashtag (title) values ('running');
insert into Hashtag (title) values ('basketball');
insert into Hashtag (title) values ('hockey');
insert into Hashtag (title) values ('tennis');
insert into Hashtag (title) values ('cycling');
insert into Hashtag (title) values ('gaming');
insert into Hashtag (title) values ('swimming');
insert into Hashtag (title) values ('concert');
insert into Hashtag (title) values ('art');
insert into Hashtag (title) values ('volleyball');
insert into Hashtag (title) values ('cricket');
insert into Hashtag (title) values ('surfing');
insert into Hashtag (title) values ('skiing');
insert into Hashtag (title) values ('baseball');
insert into Hashtag (title) values ('golf');
insert into Hashtag (title) values ('boxing');
insert into Hashtag (title) values ('climbing');
insert into Hashtag (title) values ('rugby');
insert into Hashtag (title) values ('badminton');


-- Insert data into the table "event"
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('SCP vs. GDC - Hockey', 'Hockey game between Sporting and Chaves', 'public', '2024-03-04', 10000, 4, 'Lisboa', 3);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Corridas Solitarias', 'Corrida de 10km', 'public', '2024-03-05', 1200, 4, 'Porto', 13);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('FCP vs. SLB - Football', 'Football game between Porto and Benfica', 'public', '2024-03-06', 50000, 4, 'Porto', 9);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('RTP Arena - Esports', 'Esports event', 'public', '2024-03-07', 10000, 4, 'Lisboa', 12);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('SLB vs. SCP - Basketball', 'Basketball game between Benfica and Sporting', 'public', '2024-03-08', 10000, 4, 'Lisboa', 10);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Watch Party - SCP vs. FCP - Football', 'Watch party for the football game between Sporting and Porto', 'public', '2024-03-09', 20, 1, 'Porto', 4);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Sport Party Baguim', 'Sport party in Baguim', 'public', '2024-03-10', 2000, 4, 'Baguim', 8);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Tennis Championship', 'Tennis tournament in Lisbon', 'public', '2024-03-11', 5000, 4, 'Lisboa', 14);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Cycling Tour', 'Cycling race in Porto', 'public', '2024-03-12', 8000, 4, 'Porto', 15);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Gaming Expo', 'Gaming expo showcasing the latest games', 'public', '2024-03-13', 10000, 4, 'Lisboa', 11);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Swimming Championship', 'National swimming competition', 'public', '2024-03-14', 7000, 4, 'Porto', 16);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Live Concert - Lisbon', 'Live performance by top artists', 'public', '2024-03-15', 12000, 4, 'Lisboa', 17);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Art Exhibition', 'Contemporary art showcase', 'public', '2024-03-16', 5000, 4, 'Porto', 18);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Volleyball Tournament', 'National volleyball tournament', 'public', '2024-03-17', 5000, 4, 'Lisboa', 21);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Cricket Match', 'International cricket match', 'public', '2024-03-18', 8000, 4, 'Porto', 22);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Surfing Championship', 'World surfing championship', 'public', '2024-03-19', 10000, 4, 'Lisboa', 23);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Skiing Race', 'Alpine skiing race', 'public', '2024-03-20', 7000, 4, 'Porto', 24);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Baseball League', 'Professional baseball league game', 'public', '2024-03-21', 12000, 4, 'Lisboa', 25);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Golf Masters', 'International golf tournament', 'public', '2024-03-22', 8000, 4, 'Porto', 31);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Boxing Championship', 'World boxing championship', 'public', '2024-03-23', 10000, 4, 'Lisboa', 32);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Climbing Challenge', 'Mountain climbing competition', 'public', '2024-03-24', 5000, 4, 'Porto', 33);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Rugby League', 'National rugby league match', 'public', '2024-03-25', 12000, 4, 'Lisboa', 34);
insert into Event (title, description, type, date, capacity, ticket_limit, place, id_user) values ('Badminton Open', 'International badminton tournament', 'public', '2024-03-26', 7000, 4, 'Porto', 35);






-- Insert data into the table "event_hashtag"
insert into EventHashtag (id_event, id_hashtag) values (1, 5);
insert into EventHashtag (id_event, id_hashtag) values (2, 3);
insert into EventHashtag (id_event, id_hashtag) values (3, 1);
insert into EventHashtag (id_event, id_hashtag) values (4, 2);
insert into EventHashtag (id_event, id_hashtag) values (5, 4);
insert into EventHashtag (id_event, id_hashtag) values (6, 1);
insert into EventHashtag (id_event, id_hashtag) values (7, 1);
insert into EventHashtag (id_event, id_hashtag) values (7, 2);
insert into EventHashtag (id_event, id_hashtag) values (7, 3);
insert into EventHashtag (id_event, id_hashtag) values (7, 4);
insert into EventHashtag (id_event, id_hashtag) values (7, 5);
insert into EventHashtag (id_event, id_hashtag) values (8, 6);
insert into EventHashtag (id_event, id_hashtag) values (9, 7);
insert into EventHashtag (id_event, id_hashtag) values (10, 8);
insert into EventHashtag (id_event, id_hashtag) values (11, 9);
insert into EventHashtag (id_event, id_hashtag) values (12, 10);
insert into EventHashtag (id_event, id_hashtag) values (13, 11);
insert into EventHashtag (id_event, id_hashtag) values (14, 12);
insert into EventHashtag (id_event, id_hashtag) values (15, 13);
insert into EventHashtag (id_event, id_hashtag) values (16, 14);
insert into EventHashtag (id_event, id_hashtag) values (17, 15);
insert into EventHashtag (id_event, id_hashtag) values (18, 16);
insert into EventHashtag (id_event, id_hashtag) values (19, 17);
insert into EventHashtag (id_event, id_hashtag) values (20, 18);
insert into EventHashtag (id_event, id_hashtag) values (21, 19);
insert into EventHashtag (id_event, id_hashtag) values (22, 20);
insert into EventHashtag (id_event, id_hashtag) values (23, 21);





-- Insert data into the table "event_participants"
insert into EventParticipants (id_user, id_event) values (3, 1);
insert into EventParticipants (id_user, id_event) values (13, 2);
insert into EventParticipants (id_user, id_event) values (9, 3);
insert into EventParticipants (id_user, id_event) values (12, 4);
insert into EventParticipants (id_user, id_event) values (10, 5);
insert into EventParticipants (id_user, id_event) values (8, 7);
insert into EventParticipants (id_user, id_event) values (11, 10);
insert into EventParticipants (id_user, id_event) values (22, 15);
insert into EventParticipants (id_user, id_event) values (23, 16);
insert into EventParticipants (id_user, id_event) values (24, 17);
insert into EventParticipants (id_user, id_event) values (25, 18);
insert into EventParticipants (id_user, id_event) values (32, 20);
insert into EventParticipants (id_user, id_event) values (33, 21);
insert into EventParticipants (id_user, id_event) values (34, 22);
insert into EventParticipants (id_user, id_event) values (35, 23);
insert into EventParticipants (id_user, id_event) values (4, 1);
insert into EventParticipants (id_user, id_event) values (5, 1);
insert into EventParticipants (id_user, id_event) values (6, 1);
insert into EventParticipants (id_user, id_event) values (7, 1);
insert into EventParticipants (id_user, id_event) values (4, 2);
insert into EventParticipants (id_user, id_event) values (5, 2);
insert into EventParticipants (id_user, id_event) values (6, 2);
insert into EventParticipants (id_user, id_event) values (7, 2);
insert into EventParticipants (id_user, id_event) values (4, 3);
insert into EventParticipants (id_user, id_event) values (5, 3);
insert into EventParticipants (id_user, id_event) values (6, 3);
insert into EventParticipants (id_user, id_event) values (7, 3);
insert into EventParticipants (id_user, id_event) values (4, 4);
insert into EventParticipants (id_user, id_event) values (5, 4);
insert into EventParticipants (id_user, id_event) values (6, 4);
insert into EventParticipants (id_user, id_event) values (7, 4);
insert into EventParticipants (id_user, id_event) values (4, 5);
insert into EventParticipants (id_user, id_event) values (5, 5);
insert into EventParticipants (id_user, id_event) values (6, 5);
insert into EventParticipants (id_user, id_event) values (7, 5);
insert into EventParticipants (id_user, id_event) values (4, 6);
insert into EventParticipants (id_user, id_event) values (5, 6);
insert into EventParticipants (id_user, id_event) values (6, 6);
insert into EventParticipants (id_user, id_event) values (7, 6);
insert into EventParticipants (id_user, id_event) values (4, 7);
insert into EventParticipants (id_user, id_event) values (5, 7);
insert into EventParticipants (id_user, id_event) values (6, 7);
insert into EventParticipants (id_user, id_event) values (7, 7);
insert into EventParticipants (id_user, id_event) values (14, 8);
insert into EventParticipants (id_user, id_event) values (15, 9);
insert into EventParticipants (id_user, id_event) values (13, 10);
insert into EventParticipants (id_user, id_event) values (16, 11);
insert into EventParticipants (id_user, id_event) values (17, 12);
insert into EventParticipants (id_user, id_event) values (18, 13);
insert into EventParticipants (id_user, id_event) values (21, 14);
insert into EventParticipants (id_user, id_event) values (22, 14);
insert into EventParticipants (id_user, id_event) values (23, 15);
insert into EventParticipants (id_user, id_event) values (24, 16);
insert into EventParticipants (id_user, id_event) values (25, 17);
insert into EventParticipants (id_user, id_event) values (26, 17);
insert into EventParticipants (id_user, id_event) values (27, 18);
insert into EventParticipants (id_user, id_event) values (28, 18);
insert into EventParticipants (id_user, id_event) values (29, 18);
insert into EventParticipants (id_user, id_event) values (30, 18);
insert into EventParticipants (id_user, id_event) values (31, 19);
insert into EventParticipants (id_user, id_event) values (32, 19);
insert into EventParticipants (id_user, id_event) values (33, 20);
insert into EventParticipants (id_user, id_event) values (34, 21);
insert into EventParticipants (id_user, id_event) values (35, 22);
insert into EventParticipants (id_user, id_event) values (36, 22);
insert into EventParticipants (id_user, id_event) values (37, 23);



-- Insert data into the table "favorite_event"
insert into FavoriteEvent (id_user, id_event) values (4, 1);
insert into FavoriteEvent (id_user, id_event) values (5, 2);
insert into FavoriteEvent (id_user, id_event) values (6, 3);
insert into FavoriteEvent (id_user, id_event) values (7, 4);
insert into FavoriteEvent (id_user, id_event) values (4, 5);
insert into FavoriteEvent (id_user, id_event) values (5, 6);
insert into FavoriteEvent (id_user, id_event) values (6, 7);
insert into FavoriteEvent (id_user, id_event) values (14, 8);
insert into FavoriteEvent (id_user, id_event) values (15, 9);
insert into FavoriteEvent (id_user, id_event) values (13, 10);
insert into FavoriteEvent (id_user, id_event) values (16, 11);
insert into FavoriteEvent (id_user, id_event) values (17, 12);
insert into FavoriteEvent (id_user, id_event) values (18, 13);
insert into FavoriteEvent (id_user, id_event) values (21, 14);
insert into FavoriteEvent (id_user, id_event) values (22, 15);
insert into FavoriteEvent (id_user, id_event) values (23, 16);
insert into FavoriteEvent (id_user, id_event) values (24, 17);
insert into FavoriteEvent (id_user, id_event) values (25, 18);
insert into FavoriteEvent (id_user, id_event) values (26, 14);
insert into FavoriteEvent (id_user, id_event) values (27, 15);
insert into FavoriteEvent (id_user, id_event) values (28, 16);
insert into FavoriteEvent (id_user, id_event) values (29, 17);
insert into FavoriteEvent (id_user, id_event) values (30, 18);
insert into FavoriteEvent (id_user, id_event) values (31, 19);
insert into FavoriteEvent (id_user, id_event) values (32, 20);
insert into FavoriteEvent (id_user, id_event) values (33, 21);
insert into FavoriteEvent (id_user, id_event) values (34, 22);
insert into FavoriteEvent (id_user, id_event) values (35, 23);
insert into FavoriteEvent (id_user, id_event) values (38, 19);
insert into FavoriteEvent (id_user, id_event) values (39, 20);
insert into FavoriteEvent (id_user, id_event) values (40, 21);



