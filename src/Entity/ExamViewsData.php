<?php

namespace Drupal\xvisus\Entity;

use Drupal\views\EntityViewsData;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\views\EntityViewsDataInterface;

/**
* Provides Views data for Exam entities.
*/
class ExamViewsData extends EntityViewsData implements EntityViewsDataInterface {

	/**
	* {@inheritdoc}
	*/
	public function getViewsData() {
		$data = parent::getViewsData();

		$data['exam']['filtered_supervisor'] = [
			'title' => $this->t('Filtered Supervisor'),
			'help' => $this->t('Filtered name of the supervisor.'),
			'field' => [
				'id' => 'xvisus_supervisor',
			],
		];

		$data['exam']['checkbox'] = [
			'title' => $this->t('Checkbox'),
			'help' => $this->t('Checkbox to select an exam.'),
			'field' => [
				'id' => 'xvisus_checkbox',
			],
		];

		return $data;
	}

	/**
	* {@inheritdoc}
	*/
	function getViewsTableForEntityType(EntityTypeInterface $entity_type) {
		return '';
	}
}
