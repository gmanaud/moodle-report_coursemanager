<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Shows number of files and total weight in table mdl_file
 * distributed by component.
 *
 *
 * @package     report_coursemanager
 * @copyright   2022 Olivier VALENTIN
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
global $PAGE, $DB, $USER, $CFG;

require_once(__DIR__ . '/../../../config.php');

require_login();

$site = get_site();

$PAGE = new moodle_page();
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('title', 'report_coursemanager'));
$PAGE->set_url('/report/coursemanager/admin_dashboard/files_by_component.php');
$PAGE->set_pagelayout('mycourses');
// $PAGE->set_secondary_navigation(false);

$PAGE->set_pagetype('teachertools');
$PAGE->blocks->add_region('content');
$PAGE->set_title($site->fullname);

echo $OUTPUT->header();

echo html_writer::div(get_string('title_admin_files_distribution', 'report_coursemanager'));
echo html_writer::div(get_string('admin_files_distribution_info', 'report_coursemanager'));

$table = new html_table();
$table->attributes['class'] = 'admintable generaltable';
$table->align = array('left', 'left', 'left', 'left');
$table->head = array ();

// Define headings for table.
$table->head[] = get_string('filesdistributiontablecomponent', 'report_coursemanager');
$table->head[] = get_string('filesdistributiontotalweight', 'report_coursemanager');
$table->head[] = get_string('filesdistributiontotalfiles', 'report_coursemanager');

$sqldistributionfiles = 'SELECT COUNT(f.id) AS totalfiles, ROUND(SUM(f.filesize/1024/1024)) AS totalweight, component
    FROM {files} AS f
    GROUP BY component
    ORDER BY totalweight DESC';

$dbresultdistributionfiles = $DB->get_records_sql($sqldistributionfiles);

foreach ($dbresultdistributionfiles as $component) {
    $row = array ();
    $row[] = html_writer::label($component->component, null);
    $row[] = html_writer::label($component->totalweight, null);
    $row[] = html_writer::label($component->totalfiles, null);
    $table->data[] = $row;
    }

    // Print the whole table.
    echo html_writer::table($table);

echo $OUTPUT->footer();
