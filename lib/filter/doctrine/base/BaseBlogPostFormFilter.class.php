<?php

/**
 * BlogPost filter form base class.
 *
 * @package    scheduler
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseBlogPostFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'     => new sfWidgetFormFilterInput(),
      'body'      => new sfWidgetFormFilterInput(),
      'tags_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Tag')),
    ));

    $this->setValidators(array(
      'title'     => new sfValidatorPass(array('required' => false)),
      'body'      => new sfValidatorPass(array('required' => false)),
      'tags_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Tag', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('blog_post_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addTagsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.BlogPostTag BlogPostTag')
      ->andWhereIn('BlogPostTag.tag_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'BlogPost';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'title'     => 'Text',
      'body'      => 'Text',
      'tags_list' => 'ManyKey',
    );
  }
}
