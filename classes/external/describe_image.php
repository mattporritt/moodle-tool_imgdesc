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

namespace tool_imgdesc\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_value;

/**
 * External function tool_imgdesc_describe_image
 *
 * @package    tool_imgdesc
 * @copyright  2025 Matt Porritt <matt.porritt@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class describe_image extends external_api {
    /**
     * Describes the parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'contextid' => new external_value(PARAM_INT, 'The context ID of the file.'),
            'itemid' => new external_value(PARAM_INT, 'The item ID of the file.'),
        ]);
    }

    /**
     * External function to delete custom presets.
     *
     * @param int $contextid The context ID of the file.
     * @param int $itemid The item ID of the file.
     */
    public static function execute(int $contextid, int $itemid): void {
        // Parameter validation.
        [
            'contextid' => $contextid,
            'itemid' => $itemid,
        ] = self::validate_parameters(self::execute_parameters(), [
            'icontextidd' => $contextid,
            'itemid' => $itemid,
        ]);

        // Validate context.
        $context = \context_system::instance();
        self::validate_context($context);

        require_capability('moodle/site:config', $context);

    }

    /**
     * Describes the data returned from the external function.
     */
    public static function execute_returns(): void {
    }

}
