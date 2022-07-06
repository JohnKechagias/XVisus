<?php

namespace Drupal\xvisus\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining an exam entity type.
 */
interface ExamInterface extends ContentEntityInterface,
	EntityOwnerInterface, EntityChangedInterface {

	/**
	* Gets the exam subject.
	*
	* @return string
	*/
	public function getSubject();
	/**
	* Sets the exam subject.
	*
	* @param string $subject
	*
	* @return \Drupal\xvisus\Entity\ExamInterface
	* The called Exam entity.
	*/
	public function setSubject($subject);

	/**
	* Gets the (date) timestamp of the exam.
	*
	* @return string
	*/
	public function getDate();

	/**
	* Sets the (date) timestamp of the exam.
	*
	* @param string $date
	*
	* @return \Drupal\xvisus\Entity\ExamInterface
	* The called Exam entity.
	*/
	public function setDate($date);

	/**
	* Gets the duration of the exam.
	*
	* @return int
	*/
	public function getDuration();

	/**
	* Sets the duration of the exam.
	*
	* @param int $number
	*
	* @return \Drupal\xvisus\Entity\ExamInterface
	* The called Exam entity.
	*/
	public function setDuration($duration);

	/**
	* Gets the selected status of the exam.
	*
	* @return bool
	*/
	public function getSelected();

	/**
	* Sets the selected status of the exam.
	*
	* @param bool $selected
	*
	* @return \Drupal\xvisus\Entity\ExamInterface
	* The called Exam entity.
	*/
	public function setSelected($selected);

	/**
	* Gets the exam creation timestamp.
	*
	* @return int
	*/
	public function getCreatedTime();

	/**
	* Sets the exam creation timestamp.
	*
	* @param int $timestamp
	*
	* @return \Drupal\xvisus\Entity\ExamInterface
	* The called Exam entity.
	*/
	public function setCreatedTime($timestamp);

	/**
	* Gets the exam last changed timestamp.
	*
	* @return int
	*/
	public function getChangedTime();

	/**
	* Sets the exam last changed timestamp.
	*
	* @param int $timestamp
	*
	* @return \Drupal\xvisus\Entity\ExamInterface
	* The called Exam entity.
	*/
	public function setChangedTime($timestamp);
}
