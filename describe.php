<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Create AI Descriptions for uploaded images.
 *
 * @package    tool_imgdesc
 * @copyright  2025 Matt Porritt <matt.porritt@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_imgdesc\form\img_desc_form;

require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

// Initial Page setup.
admin_externalpage_setup('tool_imgdesc');
$title = get_string('describeimage', 'tool_imgdesc');
$PAGE->set_url('/admin/tool/imgdesc/describe.php');
$PAGE->set_title($title);
$PAGE->set_heading($title);

$mform = new img_desc_form();

if ($data = $mform->get_data()) {
    // Get the uploaded image and create a Moodle URL so it can be rendered on the page.
    $fs = get_file_storage();
    $contextid = context_user::instance($USER->id)->id;
    $fileobj = $fs->get_area_files(
        contextid: $contextid,
        component: 'user',
        filearea: 'draft',
        itemid: $data->image,
        includedirs: false
    );
    $file = reset($fileobj);
    $url = moodle_url::make_draftfile_url(
            draftid: $data->image,
            pathname: $file->get_filepath(),
            filename: $file->get_filename()
    )->out();

    // Page output.
    echo $OUTPUT->header();
    // Add a description of the page.
    echo $OUTPUT->render_from_template('tool_imgdesc/describedisplay', ["drafturl" => $url]);

    echo $OUTPUT->footer();

} else {
    // Page output.
    echo $OUTPUT->header();
    // Add a description of the page.
    echo $OUTPUT->render_from_template('tool_imgdesc/describeintro', []);
    // Display the form.
    $mform->display();
    echo $OUTPUT->footer();
}


