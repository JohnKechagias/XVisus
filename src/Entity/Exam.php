<?php

namespace Drupal\xvisus\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\EntityOwnerTrait;
use Drupal\xvisus\Entity\ExamInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Defines the exam entity class.
 *
 * @ContentEntityType(
 *   id = "exam",
 *   label = @Translation("Exam"),
 *   label_collection = @Translation("Exams"),
 *   label_singular = @Translation("exam"),
 *   label_plural = @Translation("exams"),
 *   label_count = @PluralTranslation(
 *     singular = "@count exams",
 *     plural = "@count exams",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\xvisus\ExamListBuilder",
 *     "views_data" = "Drupal\xvisus\Entity\ExamViewsData",
 *     "form" = {
 *       "add" = "Drupal\xvisus\Form\ExamForm",
 *       "edit" = "Drupal\xvisus\Form\ExamForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "exam",
 *   data_table = "exam_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer exam",
 *   entity_keys = {
 *     "id" = "id",
 *     "langcode" = "langcode",
 *     "label" = "subject",
 *     "uuid" = "uuid",
 * 		 "uid" = "uid",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/exam",
 *     "add-form" = "/exam/add",
 *     "canonical" = "/exam/{exam}",
 *     "edit-form" = "/exam/{exam}/edit",
 *     "delete-form" = "/exam/{exam}/delete",
 *   },
 *   field_ui_base_route = "entity.exam.settings",
 * )
 */
class Exam extends ContentEntityBase implements ExamInterface {

	use StringTranslationTrait;
  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setTranslatable(TRUE)
      ->setLabel(t('Supervisor'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(static::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

			$fields['author'] = BaseFieldDefinition::create('entity_reference')
      ->setTranslatable(TRUE)
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(static::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

		$fields['subject'] = BaseFieldDefinition::create('string')
      ->setTranslatable(TRUE)
      ->setLabel(t('Subject'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

		$fields['date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Date'))
      ->setTranslatable(TRUE)
			->setRequired(TRUE)
      ->setDescription(t('The date of the exam.'))
			->setSettings([
        'datetime_type' => 'datetime',
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'datetime_default',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_default',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

		$fields['duration'] = BaseFieldDefinition::create('integer')
			->setLabel(t('Duration'))
			->setDescription(t('The duration of the exam in minutes.'))
			->setRequired(TRUE)
			->setSettings([
			'min' => 1,
			'max' => 10000
			])
			->setDefaultValue(NULL)
			->setDisplayOptions('view', [
			'label' => 'above',
			'type' => 'number_unformatted',
			'weight' => -4,
			])
			->setDisplayOptions('form', [
			'type' => 'number',
			'weight' => -4,
			])
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['selected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Selected'))
			->setDescription(t('True if the exam is selected by a supervisor.'))
      ->setDefaultValue(FALSE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => TRUE,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the exam was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the exam was last edited.'));

    return $fields;
  }

	/**
	* {@inheritdoc}
	*/
	public function getAuthor() {
		return $this->get('author')->entity;
	}

		/**
	* {@inheritdoc}
	*/
	public function getAuthorID() {
		return $this->get('author')->entity->id();
	}

	/**
	* {@inheritdoc}
	*/
	public function setAuthor($author) {
		$this->set('author', $author);
		return $this;
	}

	/**
	* {@inheritdoc}
	*/
	public function getSubject() {
		return $this->get('subject')->value;
	}

	/**
	* {@inheritdoc}
	*/
	public function setSubject($subject) {
		$this->set('subject', $subject);
		return $this;
	}

	/**
	* {@inheritdoc}
	*/
	public function getDate() {
		return $this->get('date')->value;
	}

	/**
	* {@inheritdoc}
	*/
	public function setDate($date) {
		$this->set('date', $date);
		return $this;
	}

	/**
	* {@inheritdoc}
	*/
	public function getDuration() {
		return $this->get('duration')->value;
	}

	/**
	* {@inheritdoc}
	*/
	public function setDuration($duration) {
		$this->set('duration', $duration);
		return $this;
	}

	/**
	* {@inheritdoc}
	*/
	public function getSelected() {
		return $this->get('selected')->value;
	}

	/**
	* {@inheritdoc}
	*/
	public function setSelected($selected) {
		$this->set('selected', $selected);
		return $this;
	}

	/**
	* {@inheritdoc}
	*/
	public function getCreatedTime() {
		return $this->get('created')->value;
	}

	/**
	* {@inheritdoc}
	*/
	public function setCreatedTime($timestamp) {
		$this->set('created', $timestamp);
		return $this;
	}

	/**
	* {@inheritdoc}
	*/
	public function getChangedTime() {
		return $this->get('changed')->value;
	}

	/**
	* {@inheritdoc}
	*/
	public function setChangedTime($timestamp) {
		$this->set('changed', $timestamp);
		return $this;
	}
}
