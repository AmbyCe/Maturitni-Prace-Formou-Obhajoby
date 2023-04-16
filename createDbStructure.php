<?php
	define('FileOpenedThroughRequire', TRUE);
	require('./core/src/secret/databaseConnect.php');

$news = "
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    timeCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    articleText MEDIUMTEXT NOT NULL,
    author INT NOT NULL,
    shortArticleText VARCHAR(5000) NOT NULL
)  ENGINE=INNODB;
";

$privateMessages = "
CREATE TABLE IF NOT EXISTS privatemessages (
    msgId INT AUTO_INCREMENT PRIMARY KEY,
	fromUser INT NOT NULL,
	toUser INT NOT NULL,
    timeCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	title VARCHAR(500) NOT NULL,
	content VARCHAR(7500) NOT NULL,
    msgReaded INT
)  ENGINE=INNODB;
";

$reportReasons = "
CREATE TABLE IF NOT EXISTS reportreasons (
    id INT AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(255) NOT NULL
)  ENGINE=INNODB;
";

$reportResponses = "
CREATE TABLE IF NOT EXISTS privatemessages (
    id INT AUTO_INCREMENT PRIMARY KEY,
	reportId INT NOT NULL,
	text VARCHAR(10000) NOT NULL,
	author INT NOT NULL
)  ENGINE=INNODB;
";

$reports = "
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
	author INT NOT NULL,
    dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	subject VARCHAR(255) NOT NULL,
	reportText VARCHAR(10000) NOT NULL,
    solved INT,
	solvedBy INT
)  ENGINE=INNODB;
";

$users = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255) NOT NULL,
	password VARCHAR(512) NOT NULL,
	permissions INT
)  ENGINE=INNODB;
";

$wikiCategories = "
CREATE TABLE IF NOT EXISTS wikicategories (
    id INT AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(255) NOT NULL
)  ENGINE=INNODB;
";

$wikiPages = "
CREATE TABLE IF NOT EXISTS wikipages (
    id INT AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(255) NOT NULL,
	content MEDIUMTEXT NOT NULL,
	category INT
)  ENGINE=INNODB;
";

$res1 = mysqli_query($conn, $news);
$res2 = mysqli_query($conn, $privateMessages);
$res3 = mysqli_query($conn, $reportReasons);
$res4 = mysqli_query($conn, $reportResponses);
$res5 = mysqli_query($conn, $reports);
$res6 = mysqli_query($conn, $users);
$res7 = mysqli_query($conn, $wikiCategories);
$res8 = mysqli_query($conn, $wikiPages);

echo('Operace dokončena!');

?>