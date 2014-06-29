-- phpMyAdmin SQL Dump
-- version 4.1.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 25, 2014 at 11:44 PM
-- Server version: 5.1.73
-- PHP Version: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpREST_Example`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `body` text,
  `ts` varchar(30) DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `author_ip` varchar(50) DEFAULT NULL,
  `replyto_postid` int(11) DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`cid`, `body`, `ts`, `author`, `author_ip`, `replyto_postid`) VALUES
(1, 'Winners don''t use drugs.', '1400499310', 4, '127.0.0.1', 1),
(2, 'Wanna see my van?', '1400499367', 6, '127.0.0.1', 1),
(3, '420 LOOOOOL', '1400499452', 3, '127.0.0.1', 1),
(4, 'I''ll see to it that you spend the rest of your life in prison, shaggy!  America''s children deserve to live in a society free of evil, drug-pushing career criminals like you!', '1400499642', 4, '127.0.0.1', 1),
(5, 'Zoiks!  420 LOOOOL', '1400499708', 3, '127.0.0.1', 1),
(6, 'I have candy.  Who wants to try some?', '1400499820', 6, '127.0.0.1', 2);

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `username1` varchar(255) DEFAULT NULL,
  `username2` varchar(255) DEFAULT NULL,
  `since` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`fid`, `username1`, `username2`, `since`) VALUES
(1, 'Kris', 'shaggy', '1397967600'),
(2, 'larry', 'Kris', '1400559600'),
(3, 'lawfullinda', 'Kris', '1400571300'),
(4, 'dickpic', 'Kris', '1369017965'),
(5, 'dickpic', 'larry', '1377830670'),
(6, 'rodeogal', 'lawfullinda', '1396203048'),
(7, 'rodeogal', 'shaggy', '1334895600');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `messageid` int(11) NOT NULL AUTO_INCREMENT,
  `from_username` varchar(255) DEFAULT NULL,
  `to_username` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` longtext,
  `ts` varchar(30) DEFAULT NULL,
  `from_ip` varchar(50) DEFAULT NULL,
  `replyto_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`messageid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`messageid`, `from_username`, `to_username`, `subject`, `body`, `ts`, `from_ip`, `replyto_id`) VALUES
(1, 'larry', 'rodeogal', 'hi', 'i liked youre post wanna hang?', '1400499968', '127.0.0.1', NULL),
(2, 'rodeogal', 'larry', 'hi', 'Sure.  What''s your favorite restaurant?', '1400500076', '127.0.0.1', 1),
(3, 'larry', 'rodeogal', 'RE: hi', 'its youre you stupid cow fuck off\r\n', '1400500144', '127.0.0.1', 2),
(4, 'rodeogal', 'larry', 'RE: hi', 'WTF?!', '1400500205', '127.0.0.1', 3),
(5, 'larry', 'rodeogal', 'RE: RE: hi', 'wow your a stupid b1tch only 3 letters wow you must be a dumb typer wh0re', '1400500337', '127.0.0.1', 4),
(6, 'larry', 'rodeogal', 'RE: RE: RE: hi', 'im sorry i got mad cuz u were acting stupid u still wanna go eat?  ill buy if its only a few bucks', '1400500559', '127.0.0.1', 4),
(7, 'rodeogal', 'larry', 'RE: RE: RE: hi', 'NO!  WTF is wrong with you?!  Are you fucking mental?  Leave me alone.', '1400500704', '127.0.0.1', 6),
(8, 'larry', 'rodeogal', 'RE: RE: RE: RE: hi', 'hahahah wow you reely like to say wtf huh your a fucking m0ron b1tch fuck you id rather eat goat sh1t than buy yuo diner you prob have rabes or somethin fuck you and die!!!!!!!!!!!!', '1400500834', '127.0.0.1', 7),
(9, 'rodeogal', 'larry', 'RE: RE: RE: RE: hi', 'Piss off you fucking creep.', '1400500920', '127.0.0.1', 8),
(10, 'larry', 'rodeogal', 'RE: RE: RE: RE: RE: hi', 'go suck a typeriters dick', '1400500968', '127.0.0.1', 9),
(11, 'dickpic', 'lawfullinda', NULL, 'What are you wearing?', '1400501344', '127.0.0.1', NULL),
(12, 'lawfullinda', 'dickpic', NULL, 'A badge and a bullet-proof vest.  You?', '1400501384', '127.0.0.1', 11),
(13, 'dickpic', 'lawfullinda', NULL, 'Nevermind.', '1400501422', '127.0.0.1', 12);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `postid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `body` text,
  `ts` varchar(30) DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `author_ip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`postid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`postid`, `title`, `body`, `ts`, `author`, `author_ip`) VALUES
(1, 'Welcome to the forum!', 'This is an internet forum.  Blah blah blah.  The rules are as follows:\r\n\r\n1. No spam.\r\n\r\n2. No hate speech.\r\n\r\n3. No speech of any kind.\r\n\r\n5. There are FOUR lights!\r\n\r\n6. Violation of these rules is punishable by death.', '1400490210', 1, '127.0.0.1'),
(2, 'SPAM', 'HILLO AND SALLUTATONS I AM Mr. NahrllllSMITH AND I NEED YOUR HELP KIND SIR !! YOU SEE I AM THE CROWN PRINCE OF EAST NIGERIA AND I HAVE SLEECTED YOU FOR YOURE GOOD REPUTATION AS A RELLY SHROOD BUSNESSPERSON !! \r\n\r\nI AM HAVE BEEN PREPARED TO COLECT AN MY FATHERS'' INHERITENSE BUT THE UNITED NATIONALS HAS RECKWIRMENT THAT DANCING VIAGRA ALL WEALTH TRANSFERS IN THE NORTHEASTERN HEMISPHEER FIRST MUST BE APPROOVED BY THE IRS BUT FOR REESONS THAT IM SURE AER UNDERSTATIBLE TO YOU THAT WOULD NOT BE IDEEL BECASE OF LAWS PAST BY THE ASIAN UNION PREVENTING MONETERY TRANSFRES THAT TAKE MORE THEN 3 (THRE) BUSNESS DAIS TO FORECLOSE UPON WHICH !! \r\n\r\nTHERFOR AS I KNOW YOU ARE TRUSTWORTHIE ONTRAPENOOR BECAUS YORE ELEKTRONIC MAIL ADRESS WAS PERSONELY VERIPHIED BY THE SULTIN OF MEXICO AND I NOW YOURE A MAN OF GOD OUR JESUS CHRIST LORD AND SAVIOUR HOLY SPIRITS I WILL TRUST YOU TOO LET ME DEPOZIT $890,000,000,000,000.45 INTO YOURE BANCK ACOWNT AND THAT YOUR TRUSTWERTHY SO I KNOW YOU CAN BE TRUSTED AND THEN I WILL GIzlksr345VE YOU 90 PROCENT OF THAT WITCH I THINK YULE AGREE IS FARE AND THEN I WILL TAKE MY UNCLES THRONE AND USE MY MONEY TO CURE CANSER VICTEMS OF MALERIA !! \r\n\r\nI KNOW YOU MUST BE ASKING OURSELF 69xfrexeep0rN!! WAT DO I NEED IT IS YOUR ATM PIN AND MOTHERS MADEIN NAME FOR VERIFICATON BECAUS I KNOW YOUR SECURITY WISE SO SEND ME THE YOURE BANK STATEMENTS AND DNA SAMPLE AND FINGERS PRINT AND THEN I WILL SHARE WITH YOU THE WELTH OF THE HONERABLE REPUBLIC OF WEST BERLIN AND THANX YOU AND GOD BLEST YOU AND YOURES AND THE HOLY GHOSTS AMEN !!!!! \r\n\r\nCONTRAGULATONS ON THIS ECSITING OPROTOONITIE AND I LOKI FORWORD TOO YOURE RESPONS !!\r\n\r\nOH AND SEE MY SEXY WEBCAM PIXXX AT http://www.go-fuck-yourself.com', '1400490515', 2, '127.0.0.1'),
(3, 'Bin Laden Determined to Strike in US', 'Quick!  There''s no time to lose!  We must hurry and do absolutely nothing before it''s too late!', '997974206', 5, '127.0.0.1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` longtext,
  `registered` varchar(30) DEFAULT NULL,
  `lastlogin` varchar(30) DEFAULT NULL,
  `lastaction` varchar(30) DEFAULT NULL,
  `lastip` varchar(50) DEFAULT NULL,
  `bio` text,
  `gender` varchar(10) DEFAULT NULL,
  `status` int(3) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `password`, `registered`, `lastlogin`, `lastaction`, `lastip`, `bio`, `gender`, `status`) VALUES
(1, 'Kris', 'F54120316CF5287CC8D9A41E40D36227B56E4C0DCFC5D7E7DE7AE5581AE614646E52F6260AD8969161F275CE40BA3880F968BB42E07962F60846A7A74848177C', '1400412174', '1400412174', '1400412174', '127.0.0.1', 'Have you ever wondered what Seinfeld would''ve been like if it had been set in North Korea?  What am I saying; of course you have!  Who hasn''t?\r\n\r\nWell, wonder no more!  After painstaking investigation, I managed to locate a portion of a screenplay from a parallel universe where Seinfeld was set in the DPRK and cats make love to dogs.\r\n\r\nHere, now, is the screenplay that will finally answer your burning questions....\r\n\r\n----\r\n\r\nJerry: Are you master of your domain?\r\n\r\nGeorge: I am king of the country. You?\r\n\r\nJerry: Lord of the--\r\n\r\nThe door is kicked open and a half-dozen soldiers storm into the room. One of them turns to George.\r\n\r\nSoldier: You''re under arrest for conspiracy to overthrow the government!\r\n\r\nGeorge: What?! No! We-- we were just joking around! We always joke around. My-- my friend, he''s a comedian! Tell them, Jerry! Tell them!\r\n\r\nJerry: Umm.... I''m sorry, who are you?\r\n\r\nGeorge: Jerry!\r\n\r\nSoldier: Alright, let''s go.\r\n\r\nThe soldiers grab George and drag him out of the apartment.\r\n\r\nGeorge [Shouting, his voice growing more distant down the hall]: No, it''s not what it looks like! We were just talking about masturbating!\r\n\r\nKramer [From down the hall]: Hey, George.\r\n\r\nGeorge [Voice fading out]: Kramer! Kramer! Tell them about the bet! The masturbating! Kramer!....\r\n\r\nKramer enters the apartment.\r\n\r\nKramer: Where''s he off to?\r\n\r\nJerry: Labor camp.\r\n\r\nKramer: What for?\r\n\r\nJerry: He got caught red-handed.\r\n\r\nBrief pause.\r\n\r\nKramer: So, that means he''s out of the bet?\r\n\r\nJerry: Yeah, I guess.\r\n\r\nKramer: Giddyup! One down, two to go. Got any pretzels?\r\n', 'male', 1),
(2, 'larry', '6CCBCBFBFF5EBF9815FEBDBF58625311D8C33AAE70BB3AAB13B50ECE522C55E076638B18B17EC0A8B90F512727B46F4C62F14B09BC54C060DF31FF957D031084', '1392722573', '1400412707', '1400412808', '127.0.0.1', NULL, 'male', 1),
(3, 'shaggy', '1B62CDF14E227B37EB72E0AE3207BD1159B1A972C5CD1D636D2A74728C8CB0B949336B3C9967BE7CA22D55F319A0A820776128DBA21482C27C66C91FA2A94F02', '1397967600', '1397967600', '1397967600', '127.0.0.1', 'Zoiks!', 'male', 1),
(4, 'lawfullinda', '579C93176F0B1E5E22AD8B0A57E782681281976D6E7AFE85ABEA2B8CD0CE9D9434654725161609712627853C5D7FBF7BD4CBB0F5308679E686F9DC51C7757A0B', '1400413238', '1400413238', '1400413238', '127.0.0.1', 'I am a police officer.', 'female', 1),
(5, 'rodeogal', 'B22036C7E336E103DCE74C24942AAD02D5858CB98886D9DF867B332E3182CA7679F2BE4E87B221C7E5CAFEED1426A2DE5EA299F86C5F26469E39F3355FF17D00', '995033254', '1400412174', '1400413372', '127.0.0.1', NULL, 'female', 1),
(6, 'dickpic', '2553046DDD352349CD9C00DE5912458CBD2E7DBEB90701FED1EEE3780D740B461C91E704EBDFE33737D41E85B0C41AA888A9212153839A04F58684642F37B114', '1400413480', '1400413480', '1400413480', '127.0.0.1', 'I am a registered sex offender.  Call me if that turns you on.', 'male', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
