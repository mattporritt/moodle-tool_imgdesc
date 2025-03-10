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

namespace tool_imgdesc\form;

use moodleform;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * AI image describe form.
 *
 * @package    tool_imgdesc
 * @copyright  2025 Matt Porritt <matt.porritt@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class img_desc_form extends moodleform {

    #[\Override]
    protected function definition() {
        $mform = $this->_form;

        // Add image file manager, so user can upload an image that a description will be generated for.
        // We use the file manager element so the image can be uploaded and stored in the draft area.
        // Then displayed on the page for the user to see.
        $mform->addElement('filemanager', 'image', get_string('uploadimage', 'tool_imgdesc'), null, [
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => ['image'],
            'return_types' => FILE_INTERNAL,
            'show_upload_button' => true,
            'show_remove_button' => true,
            'show_edit_button' => false,
            'show_move' => false,
            'show_rename' => false,
            'show_maxfiles' => 1,
        ]);

        // Add the submit button.
        $this->add_action_buttons();
    }
}
