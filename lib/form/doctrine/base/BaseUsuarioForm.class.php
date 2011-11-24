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
      'token'              => new sfWidgetFormInputText(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
      'compartidos_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Usuario')),
      'comparten_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Usuario')),
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
      'token'              => new sfValidatorString(array('max_length' => 255)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
      'compartidos_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Usuario', 'required' => false)),
      'comparten_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Usuario', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Usuario', 'column' => array('id'))),
        new sfValidatorDoctrineUnique(array('model' => 'Usuario', 'column' => array('identificacion'))),
        new sfValidatorDoctrineUnique(array('model' => 'Usuario', 'column' => array('token'))),
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

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['compartidos_list']))
    {
      $this->setDefault('compartidos_list', $this->object->Compartidos->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['comparten_list']))
    {
      $this->setDefault('comparten_list', $this->object->Comparten->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveCompartidosList($con);
    $this->saveCompartenList($con);

    parent::doSave($con);
  }

  public function saveCompartidosList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['compartidos_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Compartidos->getPrimaryKeys();
    $values = $this->getValue('compartidos_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Compartidos', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Compartidos', array_values($link));
    }
  }

  public function saveCompartenList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['comparten_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Comparten->getPrimaryKeys();
    $values = $this->getValue('comparten_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Comparten', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Comparten', array_values($link));
    }
  }

}
