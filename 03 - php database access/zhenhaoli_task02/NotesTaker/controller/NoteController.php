<?php

/**
 * Created by PhpStorm.
 * User: li
 * Date: 09.11.2016
 * Time: 20:59
 */
class NoteController
{

  private $noteDAO = null;

  function add_new_note($noteDAO, $userid, &$msg, &$err) {
    if (isset($_POST['title'], $_POST['content'])) {
      if (!empty($_POST['title'])) {

        $title = $noteDAO->sanitize_input($_POST['title']);
        $content = $noteDAO->sanitize_input($_POST['content']);

        $msg = $noteDAO->add_note($title, $content, $userid);
      } else {
        $err = 'Save failed, please enter a title!';
      }
    }
  }

  function update_note($noteDAO, &$msg, &$err){
    if (isset($_POST['noteid'], $_POST['newtitle'], $_POST['newcontent'])) {
      if (!empty($_POST['newtitle'])) {

        $noteid = substr($noteDAO->sanitize_input($_POST['noteid']), 5); //substr at 5 since we need the id after "edit_"

        $title = $noteDAO->sanitize_input($_POST['newtitle']);
        $content = $noteDAO->sanitize_input($_POST['newcontent']);

        $msg = $noteDAO->edit_note_by_id($noteid, $title, $content);
      } else {
        $err = 'Update failed, please enter a title!';
      }
    }
  }

  function find_all_notes($noteDAO, $userid, &$err) {
    $notes = array_filter($noteDAO->find_all_notes_by_userid($userid), function ($note) {
      return !is_null($note);
    });
    if (!is_array($notes)) {
      $err = 'Getting notes failed, please try again!';
      $notes = [];
    }
    return $notes;
  }

  function delete_note($noteDAO, &$msg){
    if (isset($_POST['noteid'], $_POST['delete_note'])) {

      $noteid = substr($noteDAO->sanitize_input($_POST['noteid']), 4); //substr at 4 since we need the id after "del_"

      $msg = $noteDAO->delete_note_by_id($noteid);
    }
  }

  function delete_notes($noteDAO, &$msg){
    if (isset($_POST['noteids'], $_POST['delete_notes'])) {
      $selected_notes = json_decode($_POST['noteids'], true);
      foreach ($selected_notes as $note){
        $noteid = substr($noteDAO->sanitize_input($note), 4); //substr at 4 since we need the id after "sel_"
        $msg = $noteDAO->delete_note_by_id($noteid);
      }
    }
  }
}