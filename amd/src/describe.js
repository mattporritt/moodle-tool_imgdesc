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
 * AI Subsystem policy functions.
 *
 * @module     core_ai/repository
 * @copyright  Matt Porritt <matt.porritt@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      4.5
 */

import Ajax from 'core/ajax';

/**
 * Generate the description of the image.
 *
 * @param {string} id The unique id of the text box.
 * @param {int} contextid The users context for the file.
 * @param {int} itemid The item id for the file.
 * @return {void}
 */
export const generate = async(id, contextid, itemid) => {
    const requestobj = {
        methodname: 'tool_imgdesc_describe_image',
        args: {
            contextid: contextid,
            itemid: itemid,
        }
    };
    const textarea = document.getElementById(id);
    try {
        const responseObj = await Ajax.call([requestobj])[0];
        if (responseObj.error) {
            textarea.innerHTML = responseObj.error;
        } else {
            textarea.innerHTML = responseObj.generatedcontent;
        }
    } catch (error) {
        window.console.log(error);
        this.displayError();
    }
};

