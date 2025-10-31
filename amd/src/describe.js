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
 * Toggle footer buttons visibility.
 *
 * @param {String} uniqid
 * @param {Boolean} show
 */
const toggleFooterButtons = (uniqid, show) => {
    const footer = document.getElementById(`${uniqid}-footer`);
    if (!footer) {
        return;
    }
    if (show) {
        footer.classList.remove('d-none');
    } else {
        footer.classList.add('d-none');
    }
};

/**
 * Render a plaintext description into the target element.
 *
 * @param {HTMLElement} target
 * @param {String} description
 */
const renderDescription = (target, description) => {
    target.removeAttribute('aria-busy');
    target.classList.remove('bg-light');
    // Preserve newlines but keep output safe/simple.
    const escaped = document.createElement('div');
    escaped.textContent = description || '';
    target.innerHTML = `<div class="text-body">${escaped.innerHTML.replace(/\n/g, '<br>')}</div>`;
};

/**
 * Main entry to kick off generation.
 *
 * @param {String} textboxId - DOM id of the text container (e.g., "{{uniqid}}-text-box")
 * @param {Number} contextid  - Draft file area context ID
 * @param {Number} itemid     - Draft file item ID
 * @param {String} uniqid     - Unique id used to bind button handlers
 */
export const generate = async(textboxId, contextid, itemid, uniqid) => {
    const requestobj = {
        methodname: 'tool_imgdesc_describe_image',
        args: {
            contextid: contextid,
            itemid: itemid,
        }
    };
    const textarea = document.getElementById(textboxId);
    try {
        const responseObj = await Ajax.call([requestobj])[0];
        if (responseObj.error) {
            renderDescription(textarea, responseObj.error);
        } else {
            renderDescription(textarea, responseObj.generatedcontent);
        }
    } catch (error) {
        renderDescription(textarea, error);
    }
    toggleFooterButtons(uniqid, true);
};

/**
 * Wire up footer actions:
 *  - Regenerate: re-run generation with same contextid/itemid.
 *  - New image: do a soft reset (reload the page so user gets original form).
 *
 * @param {String} uniqid
 * @param {String} textboxId
 * @param {Number} contextid
 * @param {Number} itemid
 */
const bindFooterActions = (uniqid, textboxId, contextid, itemid) => {
    const regen = document.getElementById(`${uniqid}-btn-regenerate`);
    const reset = document.getElementById(`${uniqid}-btn-newimage`);
    if (regen) {
        regen.addEventListener('click', async(e) => {
            e.preventDefault();
            toggleFooterButtons(uniqid, false);
            await generate(textboxId, contextid, itemid, uniqid);
        });
    }
    if (reset) {
        reset.addEventListener('click', (e) => {
            e.preventDefault();
            // Minimal reset: reload the page to show original upload form.
            window.location.href = window.location.pathname + window.location.search;
        });
    }
};

/**
 * Public bootstrap called from the template JS block.
 *
 * @param {String} textboxId
 * @param {Number} contextid
 * @param {Number} itemid
 * @param {String} uniqid
 */
export const init = (textboxId, contextid, itemid, uniqid) => {
    bindFooterActions(uniqid, textboxId, contextid, itemid);
    return generate(textboxId, contextid, itemid, uniqid);
};

