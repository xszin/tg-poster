SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `Accounts` (
  `id` int(11) NOT NULL,
  `login` text,
  `password` text,
  `rang` int(11) DEFAULT NULL,
  `email` text,
  `date` int(11) NOT NULL,
  `lock` int(11) NOT NULL,
  `notify` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `Accounts` (`id`, `login`, `password`, `rang`, `email`, `date`, `lock`, `notify`) VALUES
(1, 'admin', '098f794ffea8716d93976b9a298fd3fb', 1, '', 0, 0, 1);

CREATE TABLE `accounts_setting` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `profile_tg` text NOT NULL,
  `bot_token` text NOT NULL,
  `bot_username` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `channels_origin` (
  `id` int(11) NOT NULL,
  `channel_origin` text NOT NULL,
  `channel_origin_id` text NOT NULL,
  `origin_name` text NOT NULL,
  `channel_publish` text NOT NULL,
  `channel_publish_id` text NOT NULL,
  `publish_name` text NOT NULL,
  `status` int(11) NOT NULL,
  `all_post` int(11) NOT NULL,
  `invalid` int(11) NOT NULL,
  `album_get` int(11) NOT NULL,
  `setting_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `channels_settings` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `spam_filter` text NOT NULL,
  `replace_words` text NOT NULL,
  `filter_links` text NOT NULL,
  `filter_message` int(11) NOT NULL,
  `enable_text_message` int(11) NOT NULL,
  `my_text_message` text NOT NULL,
  `filter_inline` int(11) NOT NULL,
  `replace_link` text NOT NULL,
  `enable_bot_vote` int(11) NOT NULL,
  `skip_text` int(11) NOT NULL,
  `timetable` int(11) NOT NULL,
  `limit_status` int(11) NOT NULL,
  `time_post` text NOT NULL,
  `limit_post` int(11) NOT NULL,
  `limit_hours` int(11) NOT NULL,
  `limit_time` int(11) NOT NULL,
  `limit_self` int(11) NOT NULL,
  `forward_message` int(11) NOT NULL,
  `ignore_post_type` int(11) NOT NULL,
  `sugn_channel` text NOT NULL,
  `word_send_post` text NOT NULL,
  `word_send_post_func` int(11) NOT NULL,
  `word_send_post_type` int(11) NOT NULL,
  `replace_username_stat` int(11) NOT NULL,
  `replace_username` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `join_exists` (
  `id` int(11) NOT NULL,
  `channel_url` text NOT NULL,
  `name` text NOT NULL,
  `channel_id` text NOT NULL,
  `profile_tg` text NOT NULL,
  `id_account` int(11) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `self_account_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `setting` (`id`, `status`, `self_account_id`) VALUES
(1, 1, 1);

CREATE TABLE `vote_channels` (
  `id` int(11) NOT NULL,
  `channel_id` text NOT NULL,
  `message` text NOT NULL,
  `message_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `plus` int(11) NOT NULL,
  `minus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `vote_channels_exits` (
  `id` int(11) NOT NULL,
  `id_chat` int(11) NOT NULL,
  `type` text NOT NULL,
  `id_post` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `Accounts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `accounts_setting`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `channels_origin`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `channels_settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `join_exists`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vote_channels`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `vote_channels_exits`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `accounts_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `channels_origin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `channels_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `join_exists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `vote_channels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `vote_channels_exits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
