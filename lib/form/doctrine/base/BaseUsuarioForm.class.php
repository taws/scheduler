<?php

/**
 * Usuario form base class.
 *
 * @method Usuario getObject() Returns the current form's model object
 *
 * @package    scheduler
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseUsuarioForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'identificacion'     => new sfWidgetFormInputText(),
      'matricula'          => new sfWidgetFormInputText(),
      'nombre_usuario'     => new sfWidgetFormInputText(),
      'contrasenia'        => new sfWidgetFormInputText(),
      'nombres'            => new sfWidgetFormInputText(),
      'apellidos'          => new sfWidgetFormInputText(),
      'correo'             => new sfWidgetFormInputText(),
      'correo_alternativo' => new sfWidgetFormInputText(),
      'telefono'           => new sfWidgetFormInputText(),
      'celular'            => new sfWidgetFormInputText(),
      'direccion'          => new sfWidgetFormInputText(),
      'pagina_web'         => new sfWidgetFormInputText(),
      'foto'               => new sfWidgetFormTextarea(),
      'cumpleanio'         => new sfWidgetFormDate(),
      'twitter'            => new sfWidgetFormInputText(),
      'genero'             => new sfWidgetFormInputText(),
      'admin'              => new sfWidgetFormInputCheckbox(),
      'espol'              => new sfWidgetFormInputCheckbox(),
      'activo'             => new sfWidgetFormInputCheckbox(),
      'eliminado'          => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'identificacion'     => new sfValidatorString(array('max_length' => 255)),
      'matricula'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'nombre_usuario'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'contrasenia'        => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'nombres'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'apellidos'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'correo'             => new sfValidatorString(array('max_length' => 255)),
      'correo_alternativo' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'telefono'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'celular'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'direccion'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'pagina_web'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'foto'               => new sfValidatorString(array('required' => false)),
      'cumpleanio'         => new sfValidatorDate(array('required' => false)),
      'twitter'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'genero'             => new sfValidatorString(array('max_length' => 5, 'required' => false)),
      'admin'              => new sfValidatorBoolean(array('required' => false)),
      'espol'              => new sfValidatorBoolean(array('required' => false)),
      'activo'             => new sfValidatorBoolean(array('required' => false)),
      'eliminado'          => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Usuario', 'column' => array('id'))),
        new sfValidatorDoctrineUnique(array('model' => 'Usuario', 'column' => array('identificacion'))),
      ))
    );

    $this->widgetSchema->setNameFormat('usuario[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Usuario';
  }

}
