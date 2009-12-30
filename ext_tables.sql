#
# Table structure for table 'tx_aigallery_galleries'
#
CREATE TABLE tx_aigallery_galleries (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	CType varchar(30) DEFAULT '' NOT NULL,
	title tinytext,
	description text,
	images text,
	image_folder tinytext,
	live_update tinyint(4) DEFAULT '0' NOT NULL,
	alt_attributes text,
	title_attributes text,
	image_descriptions text,
	max_images varchar(3) DEFAULT '' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);