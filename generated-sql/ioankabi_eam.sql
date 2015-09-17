
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- group_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `group_tbl`;

CREATE TABLE `group_tbl`
(
    `group_id` INTEGER NOT NULL AUTO_INCREMENT,
    `alias` VARCHAR(16) NOT NULL,
    `name` VARCHAR(40) NOT NULL,
    PRIMARY KEY (`group_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- user_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user_tbl`;

CREATE TABLE `user_tbl`
(
    `user_id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(16) NOT NULL,
    `password` CHAR(64) NOT NULL,
    `email` VARCHAR(40) NOT NULL,
    PRIMARY KEY (`user_id`),
    UNIQUE INDEX `user_tbl_U_1` (`username`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- userstudent_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `userstudent_tbl`;

CREATE TABLE `userstudent_tbl`
(
    `user_id` INTEGER NOT NULL,
    `group_id` INTEGER NOT NULL,
    `dept_id` INTEGER NOT NULL,
    `firstname` VARCHAR(20) NOT NULL,
    `lastname` VARCHAR(40) NOT NULL,
    PRIMARY KEY (`user_id`),
    INDEX `userstudent_tbl_FI_2` (`group_id`),
    INDEX `userstudent_tbl_FI_3` (`dept_id`),
    CONSTRAINT `userstudent_tbl_FK_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `user_tbl` (`user_id`),
    CONSTRAINT `userstudent_tbl_FK_2`
        FOREIGN KEY (`group_id`)
        REFERENCES `group_tbl` (`group_id`),
    CONSTRAINT `userstudent_tbl_FK_3`
        FOREIGN KEY (`dept_id`)
        REFERENCES `dept_tbl` (`dept_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- userpublisher_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `userpublisher_tbl`;

CREATE TABLE `userpublisher_tbl`
(
    `user_id` INTEGER NOT NULL,
    `group_id` INTEGER NOT NULL,
    `publisher_id` INTEGER NOT NULL,
    PRIMARY KEY (`user_id`),
    INDEX `userpublisher_tbl_FI_2` (`group_id`),
    INDEX `userpublisher_tbl_FI_3` (`publisher_id`),
    CONSTRAINT `userpublisher_tbl_FK_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `user_tbl` (`user_id`),
    CONSTRAINT `userpublisher_tbl_FK_2`
        FOREIGN KEY (`group_id`)
        REFERENCES `group_tbl` (`group_id`),
    CONSTRAINT `userpublisher_tbl_FK_3`
        FOREIGN KEY (`publisher_id`)
        REFERENCES `publisher_tbl` (`publisher_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- usersecretariat_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `usersecretariat_tbl`;

CREATE TABLE `usersecretariat_tbl`
(
    `user_id` INTEGER NOT NULL,
    `group_id` INTEGER NOT NULL,
    `dept_id` INTEGER NOT NULL,
    `firstname` VARCHAR(20) NOT NULL,
    `lastname` VARCHAR(40) NOT NULL,
    PRIMARY KEY (`user_id`),
    INDEX `usersecretariat_tbl_FI_2` (`group_id`),
    INDEX `usersecretariat_tbl_FI_3` (`dept_id`),
    CONSTRAINT `usersecretariat_tbl_FK_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `user_tbl` (`user_id`),
    CONSTRAINT `usersecretariat_tbl_FK_2`
        FOREIGN KEY (`group_id`)
        REFERENCES `group_tbl` (`group_id`),
    CONSTRAINT `usersecretariat_tbl_FK_3`
        FOREIGN KEY (`dept_id`)
        REFERENCES `dept_tbl` (`dept_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- faq_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `faq_tbl`;

CREATE TABLE `faq_tbl`
(
    `group_id` INTEGER NOT NULL,
    `index` INTEGER NOT NULL,
    `question` VARCHAR(512) NOT NULL,
    `answer` TEXT NOT NULL,
    PRIMARY KEY (`group_id`,`index`),
    CONSTRAINT `faq_tbl_FK_1`
        FOREIGN KEY (`group_id`)
        REFERENCES `group_tbl` (`group_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- inst_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `inst_tbl`;

CREATE TABLE `inst_tbl`
(
    `inst_id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(60) NOT NULL,
    PRIMARY KEY (`inst_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- dept_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `dept_tbl`;

CREATE TABLE `dept_tbl`
(
    `dept_id` INTEGER NOT NULL AUTO_INCREMENT,
    `inst_id` INTEGER NOT NULL,
    `name` VARCHAR(60) NOT NULL,
    PRIMARY KEY (`dept_id`),
    INDEX `dept_tbl_FI_1` (`inst_id`),
    CONSTRAINT `dept_tbl_FK_1`
        FOREIGN KEY (`inst_id`)
        REFERENCES `inst_tbl` (`inst_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- semester_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `semester_tbl`;

CREATE TABLE `semester_tbl`
(
    `dept_id` INTEGER NOT NULL,
    `period` INTEGER NOT NULL,
    PRIMARY KEY (`dept_id`,`period`),
    CONSTRAINT `semester_tbl_FK_1`
        FOREIGN KEY (`dept_id`)
        REFERENCES `dept_tbl` (`dept_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- course_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `course_tbl`;

CREATE TABLE `course_tbl`
(
    `course_id` INTEGER NOT NULL AUTO_INCREMENT,
    `dept_id` INTEGER NOT NULL,
    `period` INTEGER NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `staff` VARCHAR(100) NOT NULL,
    `ects` INTEGER NOT NULL,
    PRIMARY KEY (`course_id`),
    INDEX `course_tbl_FI_1` (`dept_id`, `period`),
    CONSTRAINT `course_tbl_FK_1`
        FOREIGN KEY (`dept_id`,`period`)
        REFERENCES `semester_tbl` (`dept_id`,`period`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- publisher_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `publisher_tbl`;

CREATE TABLE `publisher_tbl`
(
    `publisher_id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(60) NOT NULL,
    PRIMARY KEY (`publisher_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- book_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `book_tbl`;

CREATE TABLE `book_tbl`
(
    `book_id` INTEGER NOT NULL AUTO_INCREMENT,
    `publisher_id` INTEGER NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `title` VARCHAR(100) NOT NULL,
    `author` VARCHAR(120) NOT NULL,
    `pages` INTEGER NOT NULL,
    `isbn` CHAR(17) NOT NULL,
    `available` TINYINT(1) NOT NULL,
    `cover` BLOB NOT NULL,
    PRIMARY KEY (`book_id`),
    INDEX `book_tbl_FI_1` (`publisher_id`),
    CONSTRAINT `book_tbl_FK_1`
        FOREIGN KEY (`publisher_id`)
        REFERENCES `publisher_tbl` (`publisher_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- course_book_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `course_book_tbl`;

CREATE TABLE `course_book_tbl`
(
    `course_id` INTEGER NOT NULL,
    `book_id` INTEGER NOT NULL,
    PRIMARY KEY (`course_id`,`book_id`),
    INDEX `course_book_tbl_FI_2` (`book_id`),
    CONSTRAINT `course_book_tbl_FK_1`
        FOREIGN KEY (`course_id`)
        REFERENCES `course_tbl` (`course_id`),
    CONSTRAINT `course_book_tbl_FK_2`
        FOREIGN KEY (`book_id`)
        REFERENCES `book_tbl` (`book_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- userstudent_book_tbl
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `userstudent_book_tbl`;

CREATE TABLE `userstudent_book_tbl`
(
    `user_id` INTEGER NOT NULL,
    `book_id` INTEGER NOT NULL,
    PRIMARY KEY (`user_id`,`book_id`),
    INDEX `userstudent_book_tbl_FI_2` (`book_id`),
    CONSTRAINT `userstudent_book_tbl_FK_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `userstudent_tbl` (`user_id`),
    CONSTRAINT `userstudent_book_tbl_FK_2`
        FOREIGN KEY (`book_id`)
        REFERENCES `book_tbl` (`book_id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
