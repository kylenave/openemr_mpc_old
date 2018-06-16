<?php
/**
 *
 * Copyright (C) 2012-2013 Naina Mohamed <naina@capminds.com> CapMinds Technologies
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Naina Mohamed <naina@capminds.com>
 * @link    http://www.open-emr.org
 */
 
  //SANITIZE ALL ESCAPES
 $sanitize_all_escapes=$_POST['true'];

 //STOP FAKE REGISTER GLOBALS
 $fake_register_globals=$_POST['false'];
  
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");

if (! $encounter) { // comes from globals.php
 die(xl("Internal error: we do not seem to be in an encounter!"));
}


$id = 0 + (isset($_GET['id']) ? $_GET['id'] : '');

$sets = "pid = {$_SESSION["pid"]},
  groupname = '" . $_SESSION["authProvider"] . "',
  user = '" . $_SESSION["authUser"] . "',
  authorized = $userauthorized, activity=1, date = NOW(),
  provider = '" . add_escape_custom($_POST["provider"]) . "',
  client_name          = '" . add_escape_custom($_POST["client_name"]) . "',
  reason_for_admission = '".add_escape_custom($_POST["reason_for_admission"])."',
  reason_for_discharge = '".add_escape_custom($_POST["reason_for_discharge"])."',
  transfer_to          = '" . add_escape_custom($_POST["transfer_to"]) . "',
  progress          =  '" . add_escape_custom($_POST["progress"]) . "',
  comment_on_progress  =  '" . add_escape_custom($_POST["comment_on_progress"]) . "',
  areas_of_concern     =  '" . add_escape_custom($_POST["areas_of_concern"]) . "',
  family_participation = '" . add_escape_custom($_POST["family_participation"]) . "',
  family_areas_of_growth = '" . add_escape_custom($_POST["family_areas_of_growth"]) ."'";

  
  if (empty($id)) {
  $newid = sqlInsert("INSERT INTO form_discharge_transfer SET $sets");
  addForm($encounter, "Discharge Transfer", $newid, "discharge_transfer", $pid, $userauthorized);
}
else {
  sqlStatement("UPDATE form_discharge_transfer SET $sets WHERE id = '". add_escape_custom("$id"). "'");
}

$_SESSION["encounter"] = $encounter;
formHeader("Redirecting....");
formJump();
formFooter();
?>

