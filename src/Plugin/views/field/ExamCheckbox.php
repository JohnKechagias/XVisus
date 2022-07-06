<?php

namespace Drupal\xvisus\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\ViewExecutable;
use Drupal\views\ResultRow;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\UncacheableFieldHandlerTrait;

/**
 *	Provides Selected checkbox field handler.
 *
 *  @ViewsField("xvisus_checkbox")
 */
class ExamCheckbox extends FieldPluginBase {
	/**
	* The current display.
	*
	* @var string
	* The current display of the view.
	*/
	protected $currentDisplay;

	use UncacheableFieldHandlerTrait;

	/**
	* {@inheritdoc}
	*/
	public function init(ViewExecutable $view, DisplayPluginBase $display,
	array &$options = NULL) {
		parent::init($view, $display, $options);
		$this->currentDisplay = $view->current_display;
	}

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
		$options['hide_alter_empty'] = ['default' => FALSE];
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
	public function getValue(ResultRow $row, $field = NULL) {
		return '<!--form-item-' . $this->options['id'] . '--' . $row->index . '-->';
	}

	/**
	 * Form constructor for the views form.
	 *
	 * @param array $form
	 *   An associative array containing the structure of the form.
	 * @param \Drupal\Core\Form\FormStateInterface $form_state
	 *   The current state of the form.
	 */
	public function viewsForm(array &$form, FormStateInterface $form_state) {

	  // Initialize form values.
	  $form['#cache']['max-age'] = 0;

	  // The view is empty, abort.
	  if (empty($this->getView()->result)) {
	    unset($form['actions']);
	    return;
	  }

		$form[$this->options['id']]['#tree'] = TRUE;
	  foreach ($this->getView()->result as $row_index => $row) {
			$entity = $this->getEntity($row);
			$owner_username = $entity->getOwner()->getAccountName();
			$current_username = \Drupal::currentUser()->getAccountName();
			$owner_roles = $entity->getOwner()->getRoles();

			$disabled = true;
			$selected = false;
			if (in_array('administrator', $owner_roles)
				|| $owner_username === $current_username) {
				$disabled = false;
			}

			if ($owner_username === $current_username && $entity->getSelected()) {
				$selected = true;
			}

			$form[$this->options['id']][$row_index] = [
				'#type' => 'checkbox',
				'#default_value' => $selected,
				'#disabled' => $disabled,
			];
		}
	}

	/**
   * {@inheritdoc}
   */
  public function viewsFormValidate(array &$form, FormStateInterface $form_state) {
		$sum_of_selected_exams = 0;

		foreach ($this->getView()->result as $row_index => $row) {
			if($form_state->getValue([$this->options['id'], $row_index]))
				$sum_of_selected_exams += 1;
		}

		if ($sum_of_selected_exams > 5) {
			$main_form_name = '[$this->options[\'id\']]';
			$form_state->setErrorByName($main_form_name,
				$this->t('Cannot select more than 5 exams.'));
		}
	}

	/**
	 * Submit handler for the views form, adds flag to order item data.
	 *
	 * @param array $form
	 *   An associative array containing the structure of the form.
	 * @param \Drupal\Core\Form\FormStateInterface $form_state
	 *   The current state of the form.
	 */
	public function viewsFormSubmit(array &$form, FormStateInterface $form_state) {
		foreach ($this->getView()->result as $row_index => $row) {
			$entity = $this->getEntity($row);
 			$value = $form_state->getValue([$this->options['id'], $row_index]);

			 if($entity->getSelected() == $value)
			 	continue;

			if ($value) {
				$entity->setSelected($value)->save();
				$current_user = \Drupal::currentUser()->id();
				$entity->set('uid', $current_user)->save();
			}
			else {
				$owner_username = $entity->getOwner()->getAccountName();
				$current_username = \Drupal::currentUser()->getAccountName();
				if (!($owner_username === $current_username)) {
					continue;
				}

				$entity->setSelected($value)->save();
				$author_id = $entity->getAuthorID();
				$entity->set('uid', $author_id)->save();
			}
		}
	}

	/**
   * {@inheritdoc}
   */
  protected function getView() {
    return $this->view;
  }
}
