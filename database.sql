
CREATE DATABASE MOVIE_BOOKING;
USE MOVIE_BOOKING;

SET time_zone = '+07:00';

CREATE TABLE PROVINCE(
    provinceID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    provinceName CHAR(200)
);

CREATE TABLE CINEMA(
    cinemaID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cinemaName CHAR(200),
    cinemaAddress CHAR(200),
    mapLink CHAR(255),
    provinceID int
);

ALTER TABLE CINEMA ADD CONSTRAINT FK_CinemaProvince FOREIGN KEY (provinceID) REFERENCES PROVINCE(provinceID) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE ROOM(
    roomID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    roomName CHAR(200),
    numberOfRow INT,
    numberOfCol INT,
    cinemaID INT
);

ALTER TABLE ROOM ADD CONSTRAINT FK_RoomCinema FOREIGN KEY (cinemaID) REFERENCES CINEMA(cinemaID) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE CATEGORY(
    categoryID int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    cateName CHAR(255)
);

CREATE TABLE TAG( 
    tagID int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    tagName CHAR(255),
    minAge INT
);

CREATE TABLE MOVIE(
    movieID int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    movieName CHAR(255),
    movieDes TEXT,
    posterLink CHAR(255),
    landscapePoster CHAR(255),
    trailerLink CHAR(255),
    movieDirectors CHAR(255),
    movieActors CHAR(255),
    duringTime INT,
    dateRelease TIMESTAMP,
    movieLanguage CHAR(255),
    isFeatured boolean,
    tagID INT,
    isDeleted boolean,
    externalID INT
);

ALTER TABLE MOVIE ADD CONSTRAINT FK_MovieTag FOREIGN KEY (tagID) REFERENCES TAG(tagID) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE MOVIE_CATEGORY(
    movieID INT NOT NULL,
    categoryID INT NOT NULL,
    PRIMARY KEY (movieID, categoryID)
);

ALTER TABLE MOVIE_CATEGORY ADD CONSTRAINT FK_Movie FOREIGN KEY (movieID) REFERENCES MOVIE(movieID) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE MOVIE_CATEGORY ADD CONSTRAINT FK_Category FOREIGN KEY (categoryID) REFERENCES CATEGORY(categoryID) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE SHOWTIME(
    showID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    timeStart TIMESTAMP,
    duringTime int,
    roomID int,
    movieID int,
    isDeleted boolean
);

ALTER TABLE SHOWTIME ADD CONSTRAINT FK_ShowRoom FOREIGN KEY (roomID) REFERENCES ROOM(roomID) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE SHOWTIME ADD CONSTRAINT FK_ShowCinema FOREIGN KEY (movieID) REFERENCES MOVIE(movieID) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE SEAT_TYPE(
    seatType INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    typeName CHAR(100)
);

CREATE TABLE SEAT(
    seatID INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    seatRow CHAR(1),
    seatCol INT,
    seatType INT,
    roomID INT
);

ALTER TABLE SEAT ADD CONSTRAINT FK_SeatType FOREIGN KEY (seatType) REFERENCES SEAT_TYPE(seatType) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE SEAT ADD CONSTRAINT FK_SeatRoom FOREIGN KEY (roomID) REFERENCES ROOM(roomID) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE DISCOUNT(
    discountID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    discountValue CHAR(20) NOT NULL,
    startTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    endTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    note CHAR(255),
    isDeleted boolean
);

CREATE TABLE SHOWTIME_PRICE(
    showID INT NOT NULL,
    seatType INT NOT NULL,
    ticketPrice BIGINT NOT NULL,
    discountID INT,
    isDeleted boolean,
    PRIMARY KEY(showID, seatType)
);

ALTER TABLE SHOWTIME_PRICE ADD CONSTRAINT FK_Showtime FOREIGN KEY (showID) REFERENCES SHOWTIME(showID) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE SHOWTIME_PRICE ADD CONSTRAINT FK_ShowtimeSeat FOREIGN KEY (seatType) REFERENCES SEAT_TYPE(seatType) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE SHOWTIME_PRICE ADD CONSTRAINT FK_ShowtimePrice_Discount FOREIGN KEY (discountID) REFERENCES DISCOUNT(discountID) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE GROUP_PERMISSION(
    permissionID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    groupName CHAR(100)
);

CREATE TABLE FEATURE(
    featureID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    featureName CHAR(255)
);

CREATE TABLE FEATURE_PERMISSION(
    permissionID INT NOT NULL,
    featureID INT NOT NULL,
    actionName CHAR(20),
    isActive boolean,
    webPath CHAR(255),
    PRIMARY KEY (permissionID, featureID, actionName)
);

ALTER TABLE FEATURE_PERMISSION ADD CONSTRAINT FK_PERMISS FOREIGN KEY (permissionID) REFERENCES GROUP_PERMISSION(permissionID) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE FEATURE_PERMISSION ADD CONSTRAINT FK_FEATURE FOREIGN KEY  (featureID) REFERENCES FEATURE(featureID) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE USER(
    userID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fullName char(255),
    userPassword char(200) not null,
    email char(200) unique not null,
    isActive boolean,
    createAt timestamp,
    permissionID INT,
    isDeleted boolean
);

ALTER TABLE USER ADD CONSTRAINT FK_USER_PERMISS FOREIGN KEY (permissionID) REFERENCES GROUP_PERMISSION(permissionID) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE BOOKING(
    bookingID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    bookName char(255),
    bookEmail CHAR(255),
    bookTime TIMESTAMP,
    userID INT,
    isDeleted boolean
);

ALTER TABLE BOOKING ADD CONSTRAINT FK_BookingUSER FOREIGN KEY (userID) REFERENCES USER(userID) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE FOOD(
    foodID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    foodImage CHAR(255),
    foodName CHAR(255),
    foodPrice BIGINT,
    foodDescription CHAR(255),
    discountID INT,
    isDeleted boolean
);

ALTER TABLE FOOD ADD CONSTRAINT FK_Food_Discount FOREIGN KEY (discountID) REFERENCES DISCOUNT(discountID) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE FOOD_BOOKING(
    bookingID INT NOT NULL,
    foodID INT NOT NULL,
    foodPrice BIGINT,
    foodUnit INT,
    PRIMARY KEY (bookingID, foodID)
);

ALTER TABLE FOOD_BOOKING ADD CONSTRAINT FK_FOOD_BOOKING FOREIGN KEY (foodID) REFERENCES FOOD(foodID) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE FOOD_BOOKING ADD CONSTRAINT FK_BOOKING_FOOD FOREIGN KEY (bookingID) REFERENCES BOOKING(bookingID) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE SEAT_SHOWTIME(
    showID INT NOT NULL,
    seatID INT NOT NULL,
    pickedAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    seatPrice BIGINT,
    isBooked boolean,
    userID INT,
    bookingID INT,
    PRIMARY KEY (showID, seatID)
);

ALTER TABLE SEAT_SHOWTIME ADD CONSTRAINT FK_SEAT_SHOWTIME_USER FOREIGN KEY (userID) REFERENCES USER(userID) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE SEAT_SHOWTIME ADD CONSTRAINT FK_SEAT_SHOWTIME_BOOKING FOREIGN KEY (bookingID) REFERENCES BOOKING(bookingID) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE SEAT_SHOWTIME ADD CONSTRAINT FK_SEAT_SHOWTIME_SHOW FOREIGN KEY (showID) REFERENCES SHOWTIME(showID) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE SEAT_SHOWTIME ADD CONSTRAINT FK_SEAT_SHOWTIME_SEAT FOREIGN KEY (seatID) REFERENCES SEAT(seatID) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE RATING(
    movieID INT NOT NULL,
    userID INT NOT NULL,
    ratingScore INT,
    ratingTime timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (movieID, userID)
);

ALTER TABLE RATING ADD CONSTRAINT FK_MOVIE_RATE FOREIGN KEY (movieID) REFERENCES MOVIE(movieID) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE RATING ADD CONSTRAINT FK_USER_RATE FOREIGN KEY (userID) REFERENCES USER(userID) ON DELETE CASCADE ON UPDATE CASCADE;

-- CREATE TABLE NAVBAR(
--     navbarID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
--     navbarTitle CHAR(255),
--     navbarHref CHAR(255),
--     isDeleted boolean
-- );

INSERT INTO SEAT_TYPE VALUES (1, "Ghế thường"),
                             (2, "Ghế VIP");

INSERT INTO TAG VALUES (1, "P", 0),
                       (2, "16+", 16),
                       (3, "13+", 13),
                       (4, "18+", 18);

INSERT INTO CATEGORY VALUES (28, "Hành động"),
                            (12, "Phiêu lưu"),
                            (16, "Hoạt hình"),
                            (35, "Hài hước"),
                            (80, "Tội phạm"),
                            (99, "Tài liệu"),
                            (18, "Drama"),
                            (10751, "Gia đình"),
                            (14, "Fantasy"),
                            (36, "Lịch sử"),
                            (27, "Kinh dị"),
                            (10402, "Nhạc kịch"),
                            (9648, "Bí ẩn"),
                            (10749, "Lãng mạng"),
                            (878, "Khoa học viễn tưởng"),
                            (10770, "Truyền hình"),
                            (53, "Gây cấn"),
                            (10752, "Chiến tranh"),
                            (37, "Miền tây");

INSERT INTO PROVINCE VALUES (1, "Hồ Chí Minh"),
                            (2, "Hà Nội"),
                            (3, "Tây Ninh");

INSERT INTO CINEMA VALUES (1, "SGU Cinema Nguyễn Trãi", "65 Nguyễn Trãi, Phường 2, Quận 5, Thành phố Hồ Chí Minh", "https://goo.gl/maps/C4McnC9i8Tqapm4r9", 1),
                          (2, "SGU Cinema An Dương Vương", "273 An Dương Vương, Phường 3, Quận 5, Thành phố Hồ Chí Minh", "https://goo.gl/maps/YGcCnGBDPmfFxHJg6", 1),
                          (3, "SGU Cinema Thủ Đức", "Tầng 5, TTTM Vincom Thủ Đức, 216 Võ Văn Ngân, Phường Bình Thọ, Quận Thủ Đức", "https://goo.gl/maps/ZeDJCrpSbLBvtCo47", 1),
                          (4, "SGU Cinema Âu Dương Lân", "141 Âu Đương Lân, Phường 2, Quận 8, Thành phố Hồ Chí Minh", "https://goo.gl/maps/nDykg5fdBnvn5x4d6", 1),
                          (5, "SGU Cinema Bình Tân", "1 Đường Số 17A, Bình Trị Đông B, Bình Tân, Thành phố Hồ Chí Minh", "https://goo.gl/maps/A6FfVF39D7NAJsHEA", 1),
                          (6, "SGU Cinema Huỳnh Tấn Phát", "1362 Huỳnh Tấn Phát, Phú Mỹ, Quận 7, Thành phố Hồ Chí Minh", "https://goo.gl/maps/hW1V1EKQiSTydARN6", 1),
                          (7, "SGU Cinema Cầu Giấy", "222 Trần Duy Hưng, Trung Hoà, Cầu Giấy, Hà Nội", "https://goo.gl/maps/7BNEpNvt7WfVwGAE7", 2),
                          (8, "SGU Cinema Nhà Bạn Tuấn", "Khu thương mại Mai Anh, A3, 2 Trường Chinh, Khu phố 6, Tây Ninh", "https://goo.gl/maps/2apdJyPLcYMxbp4r6", 3);

INSERT INTO ROOM VALUES (1, "Phòng 1", 10, 15, 1),
                        (2, "Phòng 2", 15, 40, 1),
                        (3, "Phòng 3", 5, 15, 1),

                        (4, "Phòng 1", 12, 40, 2),
                        (5, "Phòng 2", 8, 18, 2),
                        (6, "Phòng 3", 10, 30, 2),

                        (7, "Phòng 1", 8, 18, 3),
                        (8, "Phòng 2", 8, 18, 3),

                        (9, "Phòng 1", 8, 20, 4),
                        (10, "Phòng 2", 8, 20, 4),

                        (11, "Phòng 1", 10, 16, 5),
                        (12, "Phòng 2", 10, 16, 5),

                        (13, "Phòng 1", 7, 16, 6),
                        (14, "Phòng 2", 7, 20, 6),

                        (15, "Phòng 1", 10, 25, 7),
                        (16, "Phòng 2", 10, 30, 7),

                        (17, "Phòng 1", 6, 15, 8),
                        (18, "Phòng 2", 8, 20, 8);

# Cần thêm insert của ghế vào nữa
# Thêm combo (bắp và nước)

INSERT INTO FOOD(`foodID`, `foodImage`, `foodName`, `foodPrice`, `foodDescription`, `discountID`, `isDeleted`) VALUES (null,'https://www.cgv.vn/media/concession/web/63fec00baf8f2_1677639692.png','JUNGLE BROWN MY COMBO',259000,'1 Jungle Brown tumbler + 1 Jumbo Coke
*Redeem on the showing date
**Design depends on site availability',1,false);

INSERT INTO FOOD(`foodID`, `foodImage`, `foodName`, `foodPrice`, `foodDescription`, `discountID`, `isDeleted`) VALUES (null,'https://www.cgv.vn/media/concession/web/643fbb867f021_1681898375.png','FAST X MY COMBO',169000,'1 Fast X cup + 1 Jumbo Coke + 1 Large Popcorn (free flavor)
*Redeem on the showing date*
**Design depends on site availability**',1,false);

INSERT INTO FOOD(`foodID`, `foodImage`, `foodName`, `foodPrice`, `foodDescription`, `discountID`, `isDeleted`) VALUES (null,'https://www.cgv.vn/media/concession/web/6447521b8e460_1682395676.png','GUADIAN TIN CASE MY COMBO',179000,'1 Guadian Tin Case + 1 Jumbo Coke + 1 Large Popcorn
* Redeem on the showing date
* Design depends on site availability',1,false);

INSERT INTO FOOD(`foodID`, `foodImage`, `foodName`, `foodPrice`, `foodDescription`, `discountID`, `isDeleted`) VALUES (null,'https://www.cgv.vn/media/concession/web/6448fa964a72c_1682504342.png','KAKAO FRIEND MY COMBO',229000,'1 Kakao Friend cup (with drink) + 1 Large Popcorn (free flavor)
*Redeem on the showing date*
**Design depends on site availability**',1,false);

INSERT INTO FOOD(`foodID`, `foodImage`, `foodName`, `foodPrice`, `foodDescription`, `discountID`, `isDeleted`) VALUES (null,'https://www.cgv.vn/media/concession/web/6448fc0abb1c8_1682504715.png','KAKAO FRIEND COUPLE COMBO',399000,'2 Kakao Friend cup (with drink) + 1 Large Popcorn (free flavor)
*Redeem on the showing date*
**Design depends on site availability**',1,false);

INSERT INTO FOOD(`foodID`, `foodImage`, `foodName`, `foodPrice`, `foodDescription`, `discountID`, `isDeleted`) VALUES (null,'https://www.cgv.vn/media/concession/web/645cba6772123_1683798631.png','LITTLE MERMAID MY COMBO',189000,'1 Little Mermaid cup + 1 Jumbo Coke + 1 Large Popcorn (free flavor)
*Redeem on the showing date*',1,false);

INSERT INTO FOOD(`foodID`, `foodImage`, `foodName`, `foodPrice`, `foodDescription`, `discountID`, `isDeleted`) VALUES (null,'https://www.cgv.vn/media/concession/web/6426a4fc4b1ca_1680254204.png','JUNGLE BROWN COUPLE COMBO',499000,'2 Jungle Brown tumbler + 1 Jumbo Coke + 1 Large Sweet Popcorn
*Redeem on the showing date
**Design depends on site availability',1,false);

INSERT INTO FOOD(`foodID`, `foodImage`, `foodName`, `foodPrice`, `foodDescription`, `discountID`, `isDeleted`) VALUES (null,'https://www.cgv.vn/media/concession/web/644e34e066ddc_1682846944.png','PREMIUM MY SNACK COMBO 2023',109000,'1 Large Popcorn + 1 Jumbo Drink + 1 Snack (include: Scorched Rice; Kitkat; or Spring Rolls). Redeem on showing date.
* Free upgrade flavor for Caramel *
**Surcharge when upgrade Cheese popcorn**',1,false);

INSERT INTO FOOD(`foodID`, `foodImage`, `foodName`, `foodPrice`, `foodDescription`, `discountID`, `isDeleted`) VALUES (null,'https://www.cgv.vn/media/concession/web/63aaa2d81b6bf_1672127192.png','MY COMBO',89000,'1 Large Popcorn + 1 Jumbo Drink. Redeem on showing date.
* Free upgrade flavor for Caramel *
**Surcharge when upgrade Cheese popcorn**',1,false);

INSERT INTO FOOD(`foodID`, `foodImage`, `foodName`, `foodPrice`, `foodDescription`, `discountID`, `isDeleted`) VALUES (null,'https://www.cgv.vn/media/concession/web/63aaa31525d4c_1672127253.png','CGV COMBO',115000,'1 Large Popcorn + 2 Jumbo Drinks. Redeem on showing date.
* Free upgrade flavor for Caramel *
**Surcharge when upgrade Cheese popcorn**',1,false);
