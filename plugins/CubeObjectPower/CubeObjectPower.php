<?php
class CubeObjectPowerPlugin extends MantisPlugin {

	function register() {
		$this->name        = 'Cube Object Power';
		$this->description = 'База данных оборудования';
		$this->version     = '1.00';
		$this->requires    = array('MantisCore'       => '1.2.0',);
		$this->author      = 'Александр Меркелов';
		$this->contact     = 'free_mind@list.ru';
		$this->url         = '';
		$this->page        = 'config';
	}

	/*** Default plugin configuration.	 */
/*
	function config() {
		return array(
			'reminder_mail_subject'			=> 'Following issue will be Due shortly' ,
			'reminder_days_treshold'		=> 2,
			'reminder_store_as_note'		=> OFF,
			'reminder_sender'				=> 'admin@example.com',
			'reminder_bug_status'			=> array(ASSIGNED),
			'reminder_ignore_unset'			=> ON,
			'reminder_ignore_past'			=> ON,
			'reminder_handler'				=> ON,
			'reminder_group_issues'			=> ON,
			'reminder_group_project'		=> OFF,
			'reminder_manager_overview'		=> ON,
			'reminder_group_subject'		=> "You have issues approaching their Due Date",
			'reminder_group_body1'			=> "Please review the following issues",
			'reminder_group_body2'			=> "Please do not reply to this message",
			'reminder_project_id'			=> 0,
			'reminder_login'				=> 'admin',
			'reminder_feedback_project'		=> 0,
			'reminder_feedback_status'		=> array(FEEDBACK),
			'reminder_subject'				=> 'Issues requiring your attention',
			'reminder_finished'				=> 'Finished processing your selection',
			'reminder_hours'				=> OFF,
			'reminder_colsep'				=> ';',
			);
	}
*/
	function init() {
		plugin_event_hook( 'EVENT_MENU_MAIN', 'menu_main' );
	}

	function menu_main() {
		return array('<a href="'. plugin_page( 'page_main.php' ) . '">' . 'БД Оборудования' . '</a>' );
	}
}
