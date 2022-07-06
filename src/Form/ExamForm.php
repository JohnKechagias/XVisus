<?php

namespace Drupal\xvisus\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the exam entity edit forms.
 */
class ExamForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);
    $entity = $this->getEntity();

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->getSubject(),
      'link' => $entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New exam %label has been created.', $message_arguments));
        $this->logger('xvisus')->notice('Created new exam %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The exam %label has been updated.', $message_arguments));
        $this->logger('xvisus')->notice('Updated exam %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.exam.canonical', ['exam' => $entity->id()]);

    return $result;
  }
}
