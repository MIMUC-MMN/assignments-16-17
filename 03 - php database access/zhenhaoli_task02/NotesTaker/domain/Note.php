<?php

class Note
{
  private $id;
  private $title;
  private $text;
  private $userId;

  /**
   * Note constructor.
   * @param $id
   * @param $title
   * @param $text
   * @param $userId
   */
  public function __construct($title, $text, $userId)
  {
    $this->title = $title;
    $this->text = $text;
    $this->userId = $userId;
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }


  /**
   * @return mixed
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * @param mixed $title
   */
  public function setTitle($title)
  {
    $this->title = $title;
  }

  /**
   * @return mixed
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * @param mixed $text
   */
  public function setText($text)
  {
    $this->text = $text;
  }

  /**
   * @return mixed
   */
  public function getUserId()
  {
    return $this->userId;
  }

}