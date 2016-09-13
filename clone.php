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
 * Clone outage.
 *
 * @package    auth_outage
 * @author     Daniel Thee Roperto <daniel.roperto@catalyst-au.net>
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use auth_outage\models\outage;
use auth_outage\outagedb;
use auth_outage\outagelib;

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/formslib.php');

$renderer = outagelib::pagesetup();

$mform = new \auth_outage\forms\outage\edit();

if ($mform->is_cancelled()) {
    redirect('/auth/outage/manage.php');
} else if ($outage = $mform->get_data()) {
    $id = outagedb::save($outage);
    redirect('/auth/outage/manage.php#auth_outage_id_' . $id);
}

$id = required_param('id', PARAM_INT);
$outage = outagedb::get_by_id($id);
if ($outage == null) {
    throw new invalid_parameter_exception('Outage #' . $id . ' not found.');
}

// Remove outage id to force creating a new one.
$outage->id = null;
$mform->set_data($outage);

$PAGE->navbar->add($outage->get_title());
echo $OUTPUT->header();
echo $renderer->rendersubtitle('outageclone');
$mform->display();
echo $OUTPUT->footer();
