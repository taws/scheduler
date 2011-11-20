<?php

/**
 * Usuario filter form base class.
 *
 * @package    scheduler
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseUsuarioFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'identificacion'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'matricula'          => new sfWidgetFormFilterInput(),
      'nombre_usuario'     => new sfWidgetFormFilterInput(),
      'contrasenia'        => new sfWidgetFormFilterInput(),
      'nombres'            => new sfWidgetFormFilterInput(),
      'apellidos'          => new sfWidgetFormFilterInput(),
      'correo'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'correo_alternativo' => new sfWidgetFormFilterInput(),
      'telefono'           => new sfWidgetFormFilterInput(),
      'celular'            => new sfWidgetFormFilterInput(),
      'direccion'          => new sfWidgetFormFilterInput(),
      'pagina_web'         => new sfWidgetFormFilterInput(),
      'foto'               => new sfWidgetFormFilterInput(),
      'cumpleanio'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'twitter'            => new sfWidgetFormFilterInput(),
      'genero'             => new sfWidgetFormFilterInput(),
      'admin'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'espol'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'activo'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'eliminado'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'identificacion'     => new sfValidatorPass(array('required' => false)),
      'matricula'          => new sfValidatorPass(array('required' => false)),
      'nombre_usuario'     => new sfValidatorPass(array('required' => false)),
      'contrasenia'        => new sfValidatorPass(array('required' => false)),
      'nombres'            => new sfValidatorPass(array('required' => false)),
      'apellidos'          => new sfValidatorPass(array('required' => false)),
      'correo'             => new sfValidatorPass(array('required' => false)),
      'correo_alternativo' => new sfValidatorPass(array('required' => false)),
      'telefono'           => new sfValidatorPass(array('required' => false)),
      'celular'            => new sfValidatorPass(array('required' => false)),
      'direccion'          => new sfValidatorPass(array('required' => false)),
      'pagina_web'         => new sfValidatorPass(array('required' => false)),
      'foto'               => new sfValidatorPass(array('required' => false)),
      'cumpleanio'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'twitter'            => new sfValidatorPass(array('required' => false)),
      'genero'             => new sfValidatorPass(array('required' => false)),
      'admin'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'espol'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'activo'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'eliminado'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('usuario_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Usuario';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'identificacion'     => 'Text',
      'matricula'          => 'Text',
      'nombre_usuario'     => 'Text',
      'contrasenia'        => 'Text',
      'nombres'            => 'Text',
      'apellidos'          => 'Text',
      'correo'             => 'Text',
      'correo_alternativo' => 'Text',
      'telefono'           => 'Text',
      'celular'            => 'Text',
      'direccion'          => 'Text',
      'pagina_web'         => 'Text',
      'foto'               => 'Text',
      'cumpleanio'         => 'Date',
      'twitter'            => 'Text',
      'genero'             => 'Text',
      'admin'              => 'Boolean',
      'espol'              => 'Boolean',
      'activo'             => 'Boolean',
      'eliminado'          => 'Boolean',
    );
  }
}
