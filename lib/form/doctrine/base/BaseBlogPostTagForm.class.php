<?php

/**
 * BlogPostTag form base class.
 *
 * @method BlogPostTag getObject() Returns the current form's model object
 *
 * @package    scheduler
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseBlogPostTagForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'blog_post_id' => new sfWidgetFormInputHidden(),
      'tag_id'       => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'blog_post_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('blog_post_id')), 'empty_value' => $this->getObject()->get('blog_post_id'), 'required' => false)),
      'tag_id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('tag_id')), 'empty_value' => $this->getObject()->get('tag_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('blog_post_tag[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'BlogPostTag';
  }

}
