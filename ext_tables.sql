#
# Table structure for table 'tx_nwcitavi_domain_model_reference'
#
CREATE TABLE tx_nwcitavi_domain_model_reference (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,
	citavi_id varchar(255) DEFAULT '' NOT NULL,
	created_by varchar(255) DEFAULT '' NOT NULL,
	created_by_sid varchar(255) DEFAULT '' NOT NULL,
	created_on int(11) DEFAULT '0' NOT NULL,
	modified_by varchar(255) DEFAULT '' NOT NULL,
	modified_by_sid varchar(255) DEFAULT '' NOT NULL,
	modified_on int(11) DEFAULT '0' NOT NULL,
	reference_type varchar(255) DEFAULT '' NOT NULL,
	parent_reference_type varchar(255) DEFAULT '' NOT NULL,
	i_s_b_n varchar(255) DEFAULT '' NOT NULL,
	sequence_number varchar(255) DEFAULT '' NOT NULL,
	abstract text NOT NULL,
	abstract_r_t_f text NOT NULL,
	access_date varchar(255) DEFAULT '' NOT NULL,
	additions varchar(255) DEFAULT '' NOT NULL,
	ref_authors text NOT NULL,
	ref_categories text NOT NULL,
	citation_key_update_type varchar(255) DEFAULT '' NOT NULL,
	ref_collaborators text NOT NULL,
	custom_field1 varchar(255) DEFAULT '' NOT NULL,
	custom_field2 varchar(255) DEFAULT '' NOT NULL,
	custom_field3 varchar(255) DEFAULT '' NOT NULL,
	custom_field4 varchar(255) DEFAULT '' NOT NULL,
	custom_field5 varchar(255) DEFAULT '' NOT NULL,
	custom_field6 varchar(255) DEFAULT '' NOT NULL,
	custom_field7 varchar(255) DEFAULT '' NOT NULL,
	custom_field8 varchar(255) DEFAULT '' NOT NULL,
	custom_field9 varchar(255) DEFAULT '' NOT NULL,
	book_date varchar(255) DEFAULT '' NOT NULL,
	default_location_i_d varchar(255) DEFAULT '' NOT NULL,
	edition varchar(255) DEFAULT '' NOT NULL,
	ref_editors text NOT NULL,
	evaluation text NOT NULL,
	evaluation_r_t_f text NOT NULL,
	ref_keywords text NOT NULL,
	book_language varchar(255) DEFAULT '' NOT NULL,
	book_note text NOT NULL,
	number varchar(255) DEFAULT '' NOT NULL,
	number_of_volumes varchar(255) DEFAULT '' NOT NULL,
	online_address text NOT NULL,
	ref_organizations text NOT NULL,
	original_checked_by varchar(255) DEFAULT '' NOT NULL,
	original_publication varchar(255) DEFAULT '' NOT NULL,
	ref_others_involved text NOT NULL,
	page_count varchar(255) DEFAULT '' NOT NULL,
	page_range varchar(255) DEFAULT '' NOT NULL,
	parallel_title varchar(255) DEFAULT '' NOT NULL,
	ref_periodical text NOT NULL,
	place_of_publication varchar(255) DEFAULT '' NOT NULL,
	price double(11,2) DEFAULT '0.00' NOT NULL,
	ref_publishers text NOT NULL,
	rating int(11) DEFAULT '0' NOT NULL,
	ref_series_title text NOT NULL,
	short_title text NOT NULL,
	source_of_bibliographic_information varchar(255) DEFAULT '' NOT NULL,
	specific_field1 varchar(255) DEFAULT '' NOT NULL,
	specific_field2 varchar(255) DEFAULT '' NOT NULL,
	specific_field4 varchar(255) DEFAULT '' NOT NULL,
	specific_field7 varchar(255) DEFAULT '' NOT NULL,
	storage_medium varchar(255) DEFAULT '' NOT NULL,
	subtitle varchar(255) DEFAULT '' NOT NULL,
	table_of_contents text NOT NULL,
	table_of_contents_r_t_f text NOT NULL,
	title text NOT NULL,
	title_in_other_languages varchar(255) DEFAULT '' NOT NULL,
	title_supplement text NOT NULL,
	translated_title varchar(255) DEFAULT '' NOT NULL,
	uniform_title varchar(255) DEFAULT '' NOT NULL,
	book_volume varchar(255) DEFAULT '' NOT NULL,
	book_year varchar(255) DEFAULT '' NOT NULL,
	page_range_start varchar(255) DEFAULT '' NOT NULL,
	page_range_end varchar(255) DEFAULT '' NOT NULL,
	doi varchar(255) DEFAULT '' NOT NULL,
	sort_date varchar(255) DEFAULT '' NOT NULL,
	sort_person varchar(255) DEFAULT '' NOT NULL,
	literaturlist_id varchar(255) DEFAULT '' NOT NULL,
	attachment int(11) unsigned NOT NULL default '0',
	cover int(11) unsigned NOT NULL default '0',
	categories int(11) unsigned DEFAULT '0' NOT NULL,
	keywords int(11) unsigned DEFAULT '0' NOT NULL,
	editors int(11) unsigned DEFAULT '0' NOT NULL,
	authors int(11) unsigned DEFAULT '0' NOT NULL,
	others_involved int(11) unsigned DEFAULT '0' NOT NULL,
	collaborators int(11) unsigned DEFAULT '0' NOT NULL,
	organizations int(11) unsigned DEFAULT '0' NOT NULL,
	publishers int(11) unsigned DEFAULT '0' NOT NULL,
	periodicals int(11) unsigned DEFAULT '0' NOT NULL,
	seriestitles int(11) unsigned DEFAULT '0' NOT NULL,
	knowledge_items int(11) unsigned DEFAULT '0' NOT NULL,
	locations int(11) unsigned DEFAULT '0' NOT NULL,
	parent_references int(11) unsigned DEFAULT '0' NOT NULL,
	child_references int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_log'
#
CREATE TABLE tx_nwcitavi_domain_model_log (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	error int(11) DEFAULT '0' NOT NULL,
	errortext varchar(255) DEFAULT '' NOT NULL,
	func varchar(255) DEFAULT '' NOT NULL,
	logtype varchar(255) DEFAULT '' NOT NULL,
	details varchar(255) DEFAULT '' NOT NULL,
	importkey varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_category'
#
CREATE TABLE tx_nwcitavi_domain_model_category (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	category int(11) unsigned DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,
	citavi_id varchar(255) DEFAULT '' NOT NULL,
	created_by varchar(255) DEFAULT '' NOT NULL,
	created_by_sid varchar(255) DEFAULT '' NOT NULL,
	created_on int(11) DEFAULT '0' NOT NULL,
	modified_by varchar(255) DEFAULT '' NOT NULL,
	modified_by_sid varchar(255) DEFAULT '' NOT NULL,
	modified_on int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	description text NOT NULL,
	literaturlist_id varchar(255) DEFAULT '' NOT NULL,
	parent int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_categoryhash'
#
CREATE TABLE tx_nwcitavi_domain_model_categoryhash (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_referencehash'
#
CREATE TABLE tx_nwcitavi_domain_model_referencehash (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_keyword'
#
CREATE TABLE tx_nwcitavi_domain_model_keyword (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,
	citavi_id varchar(255) DEFAULT '' NOT NULL,
	created_by varchar(255) DEFAULT '' NOT NULL,
	created_by_sid varchar(255) DEFAULT '' NOT NULL,
	created_on int(11) DEFAULT '0' NOT NULL,
	modified_by varchar(255) DEFAULT '' NOT NULL,
	modified_by_sid varchar(255) DEFAULT '' NOT NULL,
	modified_on int(11) DEFAULT '0' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	literaturlist_id varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_keywordhash'
#
CREATE TABLE tx_nwcitavi_domain_model_keywordhash (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_knowledgeitem'
#
CREATE TABLE tx_nwcitavi_domain_model_knowledgeitem (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,
	citavi_id varchar(255) DEFAULT '' NOT NULL,
	created_by varchar(255) DEFAULT '' NOT NULL,
	created_by_sid varchar(255) DEFAULT '' NOT NULL,
	created_on int(11) DEFAULT '0' NOT NULL,
	modified_by varchar(255) DEFAULT '' NOT NULL,
	modified_by_sid varchar(255) DEFAULT '' NOT NULL,
	modified_on int(11) DEFAULT '0' NOT NULL,
	knowledge_item_type varchar(255) DEFAULT '' NOT NULL,
	quotation_type varchar(255) DEFAULT '' NOT NULL,
	core_statement varchar(255) DEFAULT '' NOT NULL,
	core_statement_update_type varchar(255) DEFAULT '' NOT NULL,
	text text NOT NULL,
	text_r_t_f text NOT NULL,
	literaturlist_id varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_knowledgeitemhash'
#
CREATE TABLE tx_nwcitavi_domain_model_knowledgeitemhash (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_library'
#
CREATE TABLE tx_nwcitavi_domain_model_library (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,
	citavi_id varchar(255) DEFAULT '' NOT NULL,
	created_by varchar(255) DEFAULT '' NOT NULL,
	created_by_sid varchar(255) DEFAULT '' NOT NULL,
	created_on int(11) DEFAULT '0' NOT NULL,
	modified_by varchar(255) DEFAULT '' NOT NULL,
	modified_by_sid varchar(255) DEFAULT '' NOT NULL,
	modified_on int(11) DEFAULT '0' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	literaturlist_id varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_libraryhash'
#
CREATE TABLE tx_nwcitavi_domain_model_libraryhash (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_location'
#
CREATE TABLE tx_nwcitavi_domain_model_location (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,
	citavi_id varchar(255) DEFAULT '' NOT NULL,
	created_by varchar(255) DEFAULT '' NOT NULL,
	created_by_sid varchar(255) DEFAULT '' NOT NULL,
	created_on int(11) DEFAULT '0' NOT NULL,
	modified_by varchar(255) DEFAULT '' NOT NULL,
	modified_by_sid varchar(255) DEFAULT '' NOT NULL,
	modified_on int(11) DEFAULT '0' NOT NULL,
	address text NOT NULL,
	notes text NOT NULL,
	location_type varchar(255) DEFAULT '' NOT NULL,
	mirrors_reference_property_id varchar(255) DEFAULT '' NOT NULL,
	address_uri varchar(255) DEFAULT '' NOT NULL,
	call_number varchar(255) DEFAULT '' NOT NULL,
	literaturlist_id varchar(255) DEFAULT '' NOT NULL,
	librarys int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_locationhash'
#
CREATE TABLE tx_nwcitavi_domain_model_locationhash (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_periodical'
#
CREATE TABLE tx_nwcitavi_domain_model_periodical (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,
	citavi_id varchar(255) DEFAULT '' NOT NULL,
	created_by varchar(255) DEFAULT '' NOT NULL,
	created_by_sid varchar(255) DEFAULT '' NOT NULL,
	created_on int(11) DEFAULT '0' NOT NULL,
	modified_by varchar(255) DEFAULT '' NOT NULL,
	modified_by_sid varchar(255) DEFAULT '' NOT NULL,
	modified_on int(11) DEFAULT '0' NOT NULL,
	i_s_s_n varchar(255) DEFAULT '' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	literaturlist_id varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_periodicalhash'
#
CREATE TABLE tx_nwcitavi_domain_model_periodicalhash (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_person'
#
CREATE TABLE tx_nwcitavi_domain_model_person (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,
	citavi_id varchar(255) DEFAULT '' NOT NULL,
	created_by varchar(255) DEFAULT '' NOT NULL,
	created_by_sid varchar(255) DEFAULT '' NOT NULL,
	created_on int(11) DEFAULT '0' NOT NULL,
	modified_by varchar(255) DEFAULT '' NOT NULL,
	modified_by_sid varchar(255) DEFAULT '' NOT NULL,
	modified_on int(11) DEFAULT '0' NOT NULL,
  full_name varchar(255) DEFAULT '' NOT NULL,
	first_name varchar(255) DEFAULT '' NOT NULL,
	last_name varchar(255) DEFAULT '' NOT NULL,
	middle_name varchar(255) DEFAULT '' NOT NULL,
	abbreviation varchar(255) DEFAULT '' NOT NULL,
	pref varchar(255) DEFAULT '' NOT NULL,
	suff varchar(255) DEFAULT '' NOT NULL,
	sex varchar(255) DEFAULT '' NOT NULL,
	person_type varchar(255) DEFAULT '' NOT NULL,
	literaturlist_id varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_personhash'
#
CREATE TABLE tx_nwcitavi_domain_model_personhash (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_publisher'
#
CREATE TABLE tx_nwcitavi_domain_model_publisher (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,
	citavi_id varchar(255) DEFAULT '' NOT NULL,
	created_by varchar(255) DEFAULT '' NOT NULL,
	created_by_sid varchar(255) DEFAULT '' NOT NULL,
	created_on int(11) DEFAULT '0' NOT NULL,
	modified_by varchar(255) DEFAULT '' NOT NULL,
	modified_by_sid varchar(255) DEFAULT '' NOT NULL,
	modified_on int(11) DEFAULT '0' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	literaturlist_id varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_publisherhash'
#
CREATE TABLE tx_nwcitavi_domain_model_publisherhash (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_seriestitle'
#
CREATE TABLE tx_nwcitavi_domain_model_seriestitle (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,
	citavi_id varchar(255) DEFAULT '' NOT NULL,
	created_by varchar(255) DEFAULT '' NOT NULL,
	created_by_sid varchar(255) DEFAULT '' NOT NULL,
	created_on int(11) DEFAULT '0' NOT NULL,
	modified_by varchar(255) DEFAULT '' NOT NULL,
	modified_by_sid varchar(255) DEFAULT '' NOT NULL,
	modified_on int(11) DEFAULT '0' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	literaturlist_id varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_domain_model_seriestitlehash'
#
CREATE TABLE tx_nwcitavi_domain_model_seriestitlehash (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	citavi_hash varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_nwcitavi_reference_category_mm'
#
CREATE TABLE tx_nwcitavi_reference_category_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_keyword_mm'
#
CREATE TABLE tx_nwcitavi_reference_keyword_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_person_mm'
#
CREATE TABLE tx_nwcitavi_reference_person_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_authors_person_mm'
#
CREATE TABLE tx_nwcitavi_reference_authors_person_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_editors_person_mm'
#
CREATE TABLE tx_nwcitavi_reference_editors_person_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_othersinvolved_person_mm'
#
CREATE TABLE tx_nwcitavi_reference_othersinvolved_person_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_collaborators_person_mm'
#
CREATE TABLE tx_nwcitavi_reference_collaborators_person_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_organizations_person_mm'
#
CREATE TABLE tx_nwcitavi_reference_organizations_person_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_publisher_mm'
#
CREATE TABLE tx_nwcitavi_reference_publisher_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_periodical_mm'
#
CREATE TABLE tx_nwcitavi_reference_periodical_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_seriestitle_mm'
#
CREATE TABLE tx_nwcitavi_reference_seriestitle_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_knowledgeitem_mm'
#
CREATE TABLE tx_nwcitavi_reference_knowledgeitem_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_location_mm'
#
CREATE TABLE tx_nwcitavi_reference_location_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_reference_mm'
#
CREATE TABLE tx_nwcitavi_reference_reference_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_category_category_mm'
#
CREATE TABLE tx_nwcitavi_category_category_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_reference_childreferences_reference_mm'
#
CREATE TABLE tx_nwcitavi_reference_childreferences_reference_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_nwcitavi_domain_model_category'
#
CREATE TABLE tx_nwcitavi_domain_model_category (

	category int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_nwcitavi_location_library_mm'
#
CREATE TABLE tx_nwcitavi_location_library_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
