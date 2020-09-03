SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `ec_users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '2',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ec_users` (`user_id`, `name`, `password`, `type`, `created`, `updated`) VALUES
(1, 'sampleuser', 'password', 2, '2019-08-07 01:17:12', '2019-08-07 01:17:12'),
(2, 'admin', 'admin', 1, '2019-08-07 10:45:11', '2019-08-07 10:45:11');

ALTER TABLE `ec_users`
ADD PRIMARY KEY (`user_id`);
  
ALTER TABLE `ec_users`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;