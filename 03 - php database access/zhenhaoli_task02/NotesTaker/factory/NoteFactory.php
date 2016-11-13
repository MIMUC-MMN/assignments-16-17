<?php

/**
 * Created by PhpStorm.
 * User: li
 * Date: 09.11.2016
 * Time: 20:47
 */
class NoteFactory
{
  public static function create($title, $text, $userId)
  {
    return new Note($title, $text, $userId);
  }
}