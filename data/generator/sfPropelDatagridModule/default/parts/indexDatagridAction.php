<?php $datagrid_actions=$this->configuration->getValue('list.batch_actions');
$tablePeer=$this->getModelClass().'Peer';
$map=$this->getModelClass().'TableMap';
$map=new $map($this->getModelClass(),new DatabaseMap('propel'));
$map->initialize();
?>
  /**
  * Render default action with a datagrid
  * @param sfWebRequest $request object
  */
  public function executeIndex(sfWebRequest $request){
    $this->setTemplate('indexDatagrid');
  }
  
  /**
  * Execute the datagrid
  * @param sfWebRequest $request object
  */
  public function executeDatagrid(sfWebRequest $request){
    $this->helper = new <?php echo $this->getModuleName() ?>GeneratorHelper();
    $this->datagrid= new <?php echo $this->configuration->getDatagridClassName() ?>('<?php echo $this->getModelClass() ?>Datagrid', '<?php echo $this->getModelClass() ?>');
    
    //prepare your query
    $c=$this->buildCriteriaFor<?php echo $this->getModelClass() ?>Datagrid($this->datagrid->getCriteria());
   	$this->datagrid->setCriteria($c);
    
    //Row action
    if(array_key_exists('<?php echo $this->params['route_prefix']; ?>_<?php echo $this->configuration->getRowAction() ?>',$this->getContext()->getRouting()->getRoutes())){
	   $this->datagrid->setRowAction('@<?php echo $this->getModuleName(); ?>_<?php echo $this->configuration->getRowAction() ?>?<?php echo strtolower($this->getPrimaryKeys(true)); ?>=','<?php echo strtolower($this->getPrimaryKeys(true)); ?>');
  	}else{
	   $this->datagrid->setRowAction('<?php echo $this->getModuleName(); ?>/<?php echo $this->configuration->getRowAction() ?>?<?php echo strtolower($this->getPrimaryKeys(true)); ?>=','<?php echo strtolower($this->getPrimaryKeys(true)); ?>');
    }
  
    //Columns sorting
    $columns=$this->datagrid->getColumnsSorting();
    $columns=$this->getColumnsSortingForDatagrid($columns);
    $this->datagrid->setColumnsSorting($columns);    
    
    //Default sorting
<?php $sort=$this->configuration->getDefaultSort();
if($sort[0]!=''):
?>
   $this->datagrid->setDefaultSortingColumn(<?php echo $this->asPhp($sort[0]) ?>,<?php echo $this->asPhp($sort[1]) ?>);
<?php endif; ?>

    //hide_filters
<?php 
$hide_filters=$this->configuration->getHideFilters();
if($hide_filters): ?>
    $array=array();
<?php foreach($hide_filters as $filter): ?>
    $array['<?php echo $filter ?>']='NOTYPE';
<?php endforeach; ?>
    $this->datagrid->setColumnsFilters($array);
<?php endif; ?>

<?php if($datagrid_actions): ?>
  	//Batch actions
<?php foreach($datagrid_actions as  $actionName => $params ){ ?>
    $actions[__('<?php echo @$params['label']?$params['label']:$actionName ?>')]= '<?php echo $this->getModuleName(); ?>/<?php echo @$params['action']?$params['action']:lcfirst(sfInflector::camelize($actionName.'_selected')); ?>';
<?php } ?>
    $this->datagrid->setDatagridActions($actions); 
<?php endif; ?>

    //Set max number of rows
   	$this->datagrid->setRowLimit(<?php echo $this->configuration->getPagerMaxPerPage();  ?>);
   	
    //Create columns
    $columns=$this->getColumnsForDatagrid();
    $this->datagrid->setColumns($columns);
    
    //Request
    $values=$this->getValuesForDatagrid();
    
    //Set datagrid content   
    $this->getResponse()->setContent($this->datagrid->getContent($values, array('lt', 'dr')));
    
    // save page
    if ($this->getRequestParameter('page')) {
        $this->getUser()->setAttribute('page', $this->getRequestParameter('page'), 'sf_admin/<?php echo $this->getSingularName() ?>');
    }
    
    return sfView::NONE;
  }
  
  /**
  * @param array
  * @return array
  */
  protected function getColumnsSortingForDatagrid($columns){
<?php foreach($this->configuration->getColumnsSorting() as $col=>$value): ?>
    $columns['<?php echo $col; ?>']='<?php echo $value; ?>';
<?php endforeach; ?>
    return $columns;
  }
  
  /**
  * @return array of values
  */
  protected function getValuesForDatagrid(){
    //Request
    $p = $this->datagrid->prepare('<?php echo $this->configuration->getPeerMethod() ?>', '<?php echo $this->configuration->getPeerCountMethod() ?>');  
    $values = array(); 
	$defaultValuesId=array(); 
	$results=$p->getResults();
	if(sizeof($results)>0):
	  foreach($results as $k=>$<?php echo $this->getSingularName() ?>):
	    $defaultValuesId[$k] = $<?php echo $this->getSingularName() ?>->getPrimaryKey();
	    
<?php if($datagrid_actions): ?>
        $values[$k][]=sfDatagrid::getCheck($<?php echo $this->getSingularName() ?>->getPrimaryKey());
<?php endif; ?>
<?php foreach ($this->configuration->getValue('list.display') as $column): ?>
<?php $credentials = $this->configuration->getValue('list.fields.'.$column->getName().'.credentials') ?>
<?php if ($credentials): $credentials = str_replace("\n", ' ', var_export($credentials, true)) ?>
        if ($sf_user->hasCredential(<?php echo $credentials ?>)): 
<?php endif; ?>
<?php if(($column->getType()=='ForeignKey')):  ?>
        if(method_exists($<?php echo $this->getSingularName() ?>,'get<?php echo sfInflector::camelize($map->getColumn($column->getName())->getRelatedTableName()).'RelatedBy'.ucfirst(sfInflector::camelize($column->getName())) ?>')):
          $values[$k][] = $<?php echo $this->getSingularName() ?>->get<?php echo sfInflector::camelize($map->getColumn($column->getName())->getRelatedTableName()).'RelatedBy'.ucfirst(sfInflector::camelize($column->getName())) ?>(); 
        else:
          $values[$k][] = $<?php echo $this->getSingularName() ?>->get<?php echo sfInflector::camelize($map->getColumn($column->getName())->getRelatedTableName()) ?>(); 
        endif;
<?php elseif($column->getType()=='Date'): ?>
        $values[$k][] = $<?php echo $this->getSingularName() ?>->get<?php echo sfInflector::camelize($column->getName()) ?>(sfConfig::get('app_datagrid_date_format'));	
<?php elseif($column->getType()=='DateTime'): ?>
         $values[$k][] = $<?php echo $this->getSingularName() ?>->get<?php echo sfInflector::camelize($column->getName()) ?>(sfConfig::get('app_datagrid_datetime_format'));	
<?php else: ?>
        $values[$k][] = $<?php echo $this->getSingularName() ?>->get<?php echo sfInflector::camelize($column->getName()) ?>();
<?php endif; ?>
<?php if ($credentials): ?>
        endif; 
<?php endif; ?>
<?php endforeach; ?>
<?php if($this->configuration->getValue('list.object_actions')): ?>
        $values[$k][] = get_partial('<?php echo $this->getModuleName(); ?>/list_td_actions',array('helper'=>$this->helper,'<?php echo $this->getSingularName() ?>'=>$<?php echo $this->getSingularName() ?>));
<?php endif; ?>
	  endforeach;
	  $this->datagrid->setRowIndexDefaultValues($defaultValuesId);
	endif;
	return $values;
  }
  
  /**
  * @return array of columns for the datagrid
  */
  protected function getColumnsForDatagrid(){
    $columns=array();
<?php foreach($this->configuration->getValue('list.display') as $column): ?>
<?php $credentials = $this->configuration->getValue('list.fields.'.$column->getName().'.credentials') ?>
<?php if ($credentials): $credentials = str_replace("\n", ' ', var_export($credentials, true)) ?>
  	if ($sf_user->hasCredential(<?php echo $credentials ?>)): 
<?php endif; ?>
	$columns['<?php echo $column->getName(); ?>']= __('<?php  echo $column->getConfig('label', '', true) ?>');
<?php if ($credentials): ?>
	endif;
<?php endif; ?>
<?php endforeach; ?>
<?php if($this->configuration->getValue('list.object_actions')){ ?>
	
	//Object actions
  	$columns['_object_actions']=__('Actions');
<?php } ?>
    return $columns;
  }
  
  /**
  * Help you to overload the filters query
  * @see fr http://www.wiki.zellerda.com/doku.php/sfdatagridplugin/prefiltrer-le-resultats
  * @see en http://www.wiki.zellerda.com/doku.php/en/sfdatagridplugin/prefiltrer-le-resultats
  * @return Criteria
  */
  public function buildCriteriaFor<?php echo $this->getModelClass() ?>Datagrid(Criteria $c){
  	return $c;
  }