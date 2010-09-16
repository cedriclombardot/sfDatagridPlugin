<?php
/**
 * Generator with sfDatagridPlugin works with sfPropelPlugin
 * 
 * @author Cédric Lombardot <cedric.lombardot@gmail.com>
 *
 */

class sfPropelDatagridGenerator extends sfPropelGenerator{
	
  /**
   * Initializes the current sfGenerator instance.
   *
   * @param sfGeneratorManager $generatorManager A sfGeneratorManager instance
   */
  public function initialize(sfGeneratorManager $generatorManager)
  {
    parent::initialize($generatorManager);

    $this->setGeneratorClass('sfPropelDatagridModule');
  }
  
}
?>