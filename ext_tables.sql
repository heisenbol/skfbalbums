#
# Table structure for table 'tx_skfbalbums_domain_model_album'
#
CREATE TABLE tx_skfbalbums_domain_model_album (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	description text,
	name_override varchar(255) DEFAULT '' NOT NULL,
	description_override text,
	facebook_id varchar(255) DEFAULT '' NOT NULL,
	link varchar(255) DEFAULT '' NOT NULL,
	cover_photo_fb_id varchar(255) DEFAULT '' NOT NULL,
	last_synced int(11) DEFAULT '0' NOT NULL,
	token int(11) unsigned DEFAULT '0',
	download tinyint(4) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	sorting int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_skfbalbums_domain_model_token'
#
CREATE TABLE tx_skfbalbums_domain_model_token (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	access_token varchar(255) DEFAULT '' NOT NULL,
	page_id varchar(255) DEFAULT '' NOT NULL,
	exclude_album_ids text NOT NULL,
	include_album_ids text NOT NULL,
	defaultdownload tinyint(4) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_skfbalbums_domain_model_photo'
#
CREATE TABLE tx_skfbalbums_domain_model_photo (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	facebook_id varchar(255) DEFAULT '' NOT NULL,
	images text NOT NULL,
	caption text,
	caption_override text NOT NULL,
	album int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	sorting int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);
