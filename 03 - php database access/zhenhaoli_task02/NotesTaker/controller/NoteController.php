<?php

require_once './dao/NoteDAO.php';

/** controller to check submitted parameter in index.php for the notes */
final class NoteController
{

  private $noteDAO = null;

  public static function Instance($noteDAO)
  {
    static $inst = null;
    if ($inst === null) {
      $inst = new NoteController($noteDAO);
    }
    return $inst;
  }

  private function __construct($noteDAO)
  {
    $this->noteDAO = $noteDAO;
  }

  function add_new_note($userid, &$msg, &$err) {
    if (isset($_POST['title'], $_POST['content'])) {
      if (!empty($_POST['title'])) {

        $title = $this->noteDAO->sanitize_input($_POST['title']);
        $content = $this->noteDAO->sanitize_input($_POST['content']);

        $msg = $this->noteDAO->add_note($title, $content, $userid);
      } else {
        $err = 'Save failed, please enter a title!';
      }
    }
  }

  function update_note(&$msg, &$err){
    if (isset($_POST['noteid'], $_POST['newtitle'], $_POST['newcontent'])) {
      if (!empty($_POST['newtitle'])) {

        $noteid = substr($this->noteDAO->sanitize_input($_POST['noteid']), 5); //substr at 5 since we need the id after "edit_"

        $title = $this->noteDAO->sanitize_input($_POST['newtitle']);
        $content = $this->noteDAO->sanitize_input($_POST['newcontent']);

        $msg = $this->noteDAO->edit_note_by_id($noteid, $title, $content);
      } else {
        $err = 'Update failed, please enter a title!';
      }
    }
  }

  function find_all_notes($userid, &$err) {
    $notes = array_filter($this->noteDAO->find_all_notes_by_userid($userid), function ($note) {
      return !is_null($note);
    });
    if (!is_array($notes)) {
      $err = 'Getting notes failed, please try again!';
      $notes = [];
    }
    return $notes;
  }

  function delete_note(&$msg){
    if (isset($_POST['noteid'], $_POST['delete_note'])) {

      $noteid = substr($this->noteDAO->sanitize_input($_POST['noteid']), 4); //substr at 4 since we need the id after "del_"

      $msg = $this->noteDAO->delete_note_by_id($noteid);
    }
  }

  function delete_notes( &$msg){
    if (isset($_POST['noteids'], $_POST['delete_notes'])) {
      $selected_notes = json_decode($_POST['noteids'], true);
      foreach ($selected_notes as $note){
        $noteid = substr($this->noteDAO->sanitize_input($note), 4); //substr at 4 since we need the id after "sel_"
        $msg = $this->noteDAO->delete_note_by_id($noteid);
      }
    }
  }
}