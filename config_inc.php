<?php
	$g_hostname = '192.168.18.10';
	$g_db_type = 'mysql';
	$g_database_name = 'mantis';
	$g_db_username = 'mantis_db_user';
	$g_db_password = '11111111';

	$g_default_language = 'auto';


	//# Будем использовать функцию mail(), а не phpMailer
	//$g_use_phpMailer = OFF;
	$g_phpMailer_method = PHPMAILER_METHOD_SMTP;
	$g_smtp_host = 'serverdnc';
	$g_smtp_username = 'mantis@osa.vaso.ru';
	$g_smtp_password = 'mantisadminz';
	$g_smtp_connection_mode = '';


	# Включить уведомления по почте
	$g_enable_email_notification = ON;

	# Set to OFF on Windows systems, as long as php-mail-function has its bcc-bug (~PHP 4.0.6)
	//$g_use_bcc = OFF;

	# the "From: " field in emails
	$g_from_email = "mantis@osa.vaso.ru";


	$g_webmaster_email = 'maa@osa.vaso.ru';
	$g_administrator_email = 'maa@osa.vaso.ru';




# --- Email Configuration ---
//$g_phpMailer_method		= PHPMAILER_METHOD_MAIL; # or PHPMAILER_METHOD_SMTP, PHPMAILER_METHOD_SENDMAIL
$g_phpMailer_method = PHPMAILER_METHOD_MAIL;
$g_smtp_connection_mode = '';
$g_smtp_host			= 'serverdnc';			# used with PHPMAILER_METHOD_SMTP
$g_smtp_username		= 'mantis@osa.vaso.ru';					# used with PHPMAILER_METHOD_SMTP
$g_smtp_password		= 'mantisadminz';					# used with PHPMAILER_METHOD_SMTP
$g_administrator_email  = 'maa@osa.vaso.ru';
$g_webmaster_email      = 'maa@osa.vaso.ru';
$g_from_email           = 'mantis@osa.vaso.ru';	# the "From: " field in emails
$g_return_path_email    = 'maa@osa.vaso.ru';	# the return address for bounced mail
# $g_from_name			= 'Mantis Bug Tracker';
$g_from_name			= 'Mantis Work';
# $g_email_receive_own	= OFF;
# $g_email_send_using_cronjob = OFF;



# --- Attachments / File Uploads ---
# $g_allow_file_upload	= ON;
# $g_file_upload_method	= DATABASE; # or DISK
// $g_file_upload_method	= DISK;
// $g_absolute_path_default_upload_folder = 'C:\MyIISServices\mantis\UPLOADED\'; # used with DISK, must contain trailing \ or /.
//# $g_max_file_size		= 5000000;	# in bytes
$g_max_file_size		= 40 * 1024 * 1024;	# in bytes
# $g_preview_attachments_inline_max_size = 256 * 1024;
//$g_preview_attachments_inline_max_size = 60 * 1024 * 1024;

# $g_allowed_files		= '';		# extensions comma separated, e.g. 'php,html,java,exe,pl'
# $g_disallowed_files		= '';		# extensions comma separated

# --- Branding ---
# $g_window_title			= 'MantisBT';
# $g_logo_image			= 'images/mantis_logo.png';
# $g_favicon_image		= 'images/favicon.ico';

# --- Real names ---
# $g_show_realname = OFF;
# $g_show_user_realname_threshold = NOBODY;	# Set to access level (e.g. VIEWER, REPORTER, DEVELOPER, MANAGER, etc)

# --- Others ---
# $g_default_home_page = 'my_view_page.php';	# Set to name of page to go to after login

//---
$g_log_level = LOG_EMAIL | LOG_EMAIL_RECIPIENT;
$g_log_destination = 'file:C:\MyIISServices\mantis\logs\mantisbt2.log';


	/************
	 * Due Date *
	 ************/

	/**
	 * threshold to update due date submitted
	 * @global int $g_due_date_update_threshold
	 */
	$g_due_date_update_threshold = VIEWER; //NOBODY;

	/**
	 * threshold to see due date
	 * @global int $g_due_date_view_threshold
	 */
	$g_due_date_view_threshold = VIEWER; //NOBODY;


	/********************
	 * Wiki Integration *
	 ********************/

	/**
	 * Wiki Integration Enabled?
	 * @global int $g_wiki_enable
	 */
	$g_wiki_enable = ON;

	/**
	 * Wiki Engine (supported engines: 'dokuwiki', 'mediawiki', 'twiki', 'wikka', 'xwiki')
	 * @global string $g_wiki_engine
	 */
	$g_wiki_engine = 'dokuwiki';

	/**
	 * Wiki namespace to be used as root for all pages relating to this MantisBT installation.
	 * @global string $g_wiki_root_namespace
	 */
	$g_wiki_root_namespace = 'mantis';

	/**
	 * URL under which the wiki engine is hosted.  Must be on the same server.
	 * @global string $g_wiki_engine_url
	 */
	$g_wiki_engine_url = 'http://192.168.18.10/wiki/';

	$g_cookie_domain = '.192.168.18.10';   // So wiki and bugs can share cookie info
	#$g_cookie_domain = '.servermcis';   // So wiki and bugs can share cookie info


	/**
	 * An array of the fields to show on the bug view page.
	 *
	 * To overload this setting per project, then the settings must be included in the database through
	 * the generic configuration form.
	 *
	 * @global array $g_bug_view_page_fields
	 */
	$g_bug_view_page_fields = array (
		'id',
		'project',
		'category_id',
		'view_state',
		'date_submitted',
		'last_updated',
		'reporter',
		'handler',
		'priority',
		'severity',
//		'reproducibility',
		'status',
		'resolution',
		'projection',
//		'eta',
//		'platform',
//		'os',
//		'os_version',
//		'product_version',
//		'product_build',
//		'target_version',
//		'fixed_in_version',
		'summary',
		'description',
//		'additional_info',
//		'steps_to_reproduce',
//		'tags',
		'attachments',
		'due_date',
	);


	/**
	 * An array of the fields to show on the bug update page.
	 *
	 * To overload this setting per project, then the settings must be included in the database through
	 * the generic configuration form.
	 *
	 * @global array $g_bug_update_page_fields
	 */
	$g_bug_update_page_fields = array (
		'id',
		'project',
		'category_id',
		'view_state',
		'date_submitted',
		'last_updated',
		'reporter',
		'handler',
		'priority',
		'severity',
//		'reproducibility',
		'status',
		'resolution',
		'projection',
//		'eta',
//		'platform',
//		'os',
//		'os_version',
//		'product_version',
//		'product_build',
//		'target_version',
//		'fixed_in_version',
		'summary',
		'description',
//		'additional_info',
//		'steps_to_reproduce',
		'attachments',
		'due_date',
	);

	/**
	 * An array of the fields to show on the bug report page.
	 *
	 * The following fields can not be included:
	 * id, project, date_submitted, last_updated, status,
	 * resolution, tags, fixed_in_version, projection, eta,
	 * reporter.
	 *
	 * The following fields must be included:
	 * category_id, summary, description.
	 *
	 * To overload this setting per project, then the settings must be included in the database through
	 * the generic configuration form.
	 *
	 * @global array $g_bug_report_page_fields
	 */
	$g_bug_report_page_fields = array(
		'category_id',
		'view_state',
		'handler',
		'priority',
		'severity',
//		'reproducibility',
//		'platform',
//		'os',
//		'os_version',
//		'product_version',
//		'product_build',
//		'target_version',
		'summary',
		'description',
//		'additional_info',
//		'steps_to_reproduce',
		'attachments',
		'due_date',
	);

?>
