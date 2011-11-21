<?php

/**
 * Compartir form base class.
 *
 * @method Compartir getObject() Returns the current form's model object
 *
 * @package    scheduler
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCompartirForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'comparte_id'   => new sfWidgetFormInputHidden(),
      'compartido_id' => new sfWidgetFormInputHidden(),
      'activo'        => new sfWidgetFormInputCheckbox(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'comparte_id'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('comparte_id')), 'empty_value' => $this->getObject()->get('comparte_id'), 'required' => false)),
      'compartido_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('compartido_id')), 'empty_value' => $this->getObject()->get('compartido_id'), 'required' => false)),
      'activo'        => new sfValidatorBoolean(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('compartir[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Compartir';
  }

}
