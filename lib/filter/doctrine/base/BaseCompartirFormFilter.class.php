<?php

/**
 * Compartir filter form base class.
 *
 * @package    scheduler
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCompartirFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'comparte_id'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'compartido_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Usuario'), 'add_empty' => true)),
      'activo'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'comparte_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'compartido_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Usuario'), 'column' => 'id')),
      'activo'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('compartir_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Compartir';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'comparte_id'   => 'Number',
      'compartido_id' => 'ForeignKey',
      'activo'        => 'Boolean',
    );
  }
}
