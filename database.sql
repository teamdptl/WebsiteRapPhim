
CREATE DATABASE MOVIE_BOOKING;
USE MOVIE_BOOKING;

CREATE TABLE PROVINCE(
    provinceID INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    province_name CHAR(200)
);

CREATE TABLE CINEMA(
    cinemaID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cinema_name CHAR(200),
    map_link CHAR(500),
    provinceID int
);

ALTER TABLE CINEMA ADD CONSTRAINT FK_CinemaProvince FOREIGN KEY (provinceID) REFERENCES PROVINCE(provinceID);

CREATE TABLE ROOM(
    roomID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    room_name CHAR(200),
    cinemaID INT
);

ALTER TABLE ROOM ADD CONSTRAINT FK_RoomCinema FOREIGN KEY (cinemaID) REFERENCES CINEMA(cinemaID);

CREATE TABLE SHOWTIME(
    showID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    time_start TIMESTAMP,
    during_time int,
    roomID int,
    movieID int
);

ALTER TABLE SHOWTIME ADD CONSTRAINT FK_ShowRoom FOREIGN KEY (roomID) REFERENCES ROOM(roomID);
ALTER TABLE SHOWTIME ADD CONSTRAINT FK_ShowCinema FOREIGN KEY (movieID) REFERENCES MOVIE(movieID);

CREATE TABLE CATEGORY(
    categoryID int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    cate_name CHAR(100)
);

CREATE TABLE TAG( 
    tagID int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    tag_name CHAR(100),
    min_age int
);

CREATE TABLE MOVIE(
    movieID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title CHAR(200),
    descriptions CHAR(500), 
    poster_link CHAR(300),
    trailer_link CHAR(300),
    director CHAR(200),
    actor CHAR(200),
    during_time INT,
    start_show TIMESTAMP,
    movie_language CHAR(100),
    categoryID INT,
    tagID INT
);

ALTER TABLE MOVIE ADD CONSTRAINT FK_MovieCate FOREIGN KEY (categoryID) REFERENCES CATEGORY(categoryID);
ALTER TABLE MOVIE ADD CONSTRAINT FK_MovieTag FOREIGN KEY (tagID) REFERENCES TAG(tagID);

CREATE TABLE SEAT_TYPE(
    seat_type INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type_name CHAR(100)
);

CREATE TABLE SEAT(
    seatID INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    seat_row CHAR(1),
    seat_num INT, 
    seat_slot INT,
    seat_type INT,
    roomID INT
);

ALTER TABLE SEAT ADD CONSTRAINT FK_SeatType FOREIGN KEY (seat_type) REFERENCES SEAT_TYPE(seat_type);
ALTER TABLE ROOM ADD CONSTRAINT FK_SeatRoom FOREIGN KEY (roomID) REFERENCES ROOM(roomID);

CREATE TABLE SHOWTIME_PRICE(
    showID INT NOT NULL,
    seat_type INT NOT NULL,
    PRIMARY KEY(showID, seat_type)
);

ALTER TABLE SHOWTIME_PRICE ADD CONSTRAINT FK_Showtime FOREIGN KEY (showID) REFERENCES SHOWTIME(showID);
ALTER TABLE SHOWTIME_PRICE ADD CONSTRAINT FK_ShowtimeSeat FOREIGN KEY (seat_type) REFERENCES SEAT_TYPE(seat_type);

CREATE TABLE GROUP_PERMISSION(
    permissionID INT NOT NULL PRIMARY KEY,
    group_name CHAR(100)
);

CREATE TABLE FEATURE(
    featureID INT NOT NULL PRIMARY KEY,
    feature_name CHAR(100)
);

CREATE TABLE FEATURE_PERMISSION(
    permissionID INT NOT NULL,
    featureID INT NOT NULL,
    actions CHAR(20),
    is_active boolean,
    web_url CHAR(200),
    PRIMARY KEY (permissionID, featureID, actions)
);

ALTER TABLE FEATURE_PERMISSION ADD CONSTRAINT FK_PERMISS FOREIGN KEY (permissionID) REFERENCES GROUP_PERMISSION(permissionID);
ALTER TABLE FEATURE_PERMISSION ADD CONSTRAINT FK_FEATURE FOREIGN KEY  (featureID) REFERENCES FEATURE_PERMISSION(featureID);

CREATE TABLE ACCOUNT(
    accountID INT NOT NULL PRIMARY KEY,
    username char(200) unique not null,
    passwords char(200) not null,
    email char(200) unique,
    phone char(15),
    is_active boolean,
    is_admin boolean,
    is_verify boolean,
    created_at timestamp,
    permissionID INT
);

ALTER TABLE ACCOUNT ADD CONSTRAINT FK_ACCOUNT_PERMISS FOREIGN KEY (permissionID) REFERENCES GROUP_PERMISSION(permissionID);

CREATE TABLE CUSTOMER(
    customerID INT NOT NULL PRIMARY KEY,
    fullname char(200),
    address char(200),
    gender char(10),
    birth_day timestamp,
    accountID INT
);

ALTER TABLE CUSTOMER ADD CONSTRAINT FK_CUSTOMER_ACCOUNT FOREIGN KEY (accountID) REFERENCES ACCOUNT(accountID);

CREATE TABLE ROLE(
    roleID INT NOT NULL PRIMARY KEY,
    role_name char(200)
);

CREATE TABLE EMPLOYEE(
    emloyeeID INT NOT NULL PRIMARY KEY,
    fullname char(200),
    address char(200),
    gender char(10),
    date_join timestamp,
    birth_day timestamp,
    accountID INT,
    roleID INT
);

ALTER TABLE EMPLOYEE ADD CONSTRAINT FK_EMPLOYEE_ACCOUNT FOREIGN KEY (accountID) REFERENCES ACCOUNT(accountID);
ALTER TABLE EMPLOYEE ADD CONSTRAINT FK_EMPLOYEE_ROLE FOREIGN KEY (roleID) REFERENCES ROLE(roleID);

CREATE TABLE BOOKING(
    bookingID INT NOT NULL PRIMARY KEY,
    total_price LONG,
    paid_price LONG,
    is_pay BOOLEAN,
    book_name CHAR(100),
    book_email CHAR(200),
    book_phone CHAR(15),
    time_book TIMESTAMP,
    showID INT,
    accountID INT
);

ALTER TABLE BOOKING ADD CONSTRAINT FK_BookingShow FOREIGN KEY (showID) REFERENCES SHOWTIME(showID);
ALTER TABLE BOOKING ADD CONSTRAINT FK_BookingAccount FOREIGN KEY (accountID) REFERENCES ACCOUNT(accountID);

CREATE TABLE TICKET (
    ticketID INT NOT NULL PRIMARY KEY,
    ticket_price LONG,
    ticket_paid LONG,
    ticket_qrcode char(400) unique,
    seatID INT,
    bookingID INT
);

ALTER TABLE TICKET ADD CONSTRAINT FK_TicketSeat FOREIGN KEY (seatID) REFERENCES SEAT(seatID);
ALTER TABLE TICKET ADD CONSTRAINT FK_TicketBook FOREIGN KEY (bookingID) REFERENCES BOOKING(bookingID);

CREATE TABLE SEAT_HOLDER(
    showID INT NOT NULL,
    seatID INT NOT NULL,
    picket_at TIMESTAMP,
    hold_time INT,
    accountID INT,
    PRIMARY KEY(showID, seatID)
);

ALTER TABLE SEAT_HOLDER ADD CONSTRAINT FK_SeatHoldAccount FOREIGN KEY (accountID) REFERENCES ACCOUNT(accountID);

CREATE TABLE FOOD(
    foodID INT NOT NULL PRIMARY KEY,
    food_image CHAR(300),
    food_name CHAR(200),
    price LONG,
    DESCRIPTIONS CHAR(300)
);

CREATE TABLE FOOD_BOOKING(
    foodBookingID INT NOT NULL PRIMARY KEY,
    food_price LONG,
    food_unit INT,
    bookingID INT,
    foodID INT
);

ALTER TABLE FOOD_BOOKING ADD CONSTRAINT FK_FOOD_BOOKING FOREIGN KEY (foodID) REFERENCES FOOD(foodID);
ALTER TABLE FOOD_BOOKING ADD CONSTRAINT FK_BOOKING_FOOD FOREIGN KEY (bookingID) REFERENCES BOOKING(bookingID);

CREATE TABLE VERIFICATION(
    verifyID INT NOT NULL PRIMARY KEY,
    otp_code INT,
    created_at timestamp,
    time_valid INT,
    accountID INT
);

ALTER TABLE VERIFICATION ADD CONSTRAINT FK_ACCOUNT_VERI FOREIGN KEY (accountID) REFERENCES ACCOUNT(accountID);

CREATE TABLE THAMSO(
    ID INT NOT NULL PRIMARY KEY,
    max_seat_hol INT,
    max_seat_buy INT,
    max_email_per_min INT,
    otp_code_valid INT
);

CREATE TABLE EMAIL_TEMPLATE(
    templateID INT NOT NULL PRIMARY KEY,
    template_name CHAR(200),
    template_header CHAR(300),
    template_body CHAR(1000)
);