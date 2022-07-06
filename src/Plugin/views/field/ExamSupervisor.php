<?php

namespace Drupal\xvisus\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\ResultRow;

/**
 *	Provides Filtered Supervisor field handler.
 *
 *  @ViewsField("xvisus_supervisor")
 */
class ExamSupervisor extends FieldPluginBase {

	/**
	* {@inheritdoc}
	*/
	public function query() {
		// Leave empty to avoid a query on this field.
	}

	/**
	* {@inheritdoc}
	*/
	protected function defineOptions() {
		$options = parent::defineOptions();
		return $options;
	}

	/**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
  }

	/**
	* {@inheritdoc}
	*/
	public function render(ResultRow $values) {
		$entity = $values->_entity;
		$value = $entity->getOwner()->getAccountName();

		$owner_roles = $entity->getOwner()->getRoles();
    if (in_array('administrator', $owner_roles)) {
			$value = 'None';
		}
		return $this->sanitizeValue($value);
	}
}
