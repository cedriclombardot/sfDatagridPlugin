  /**
   * @return boolean true if user choose using datagrid 
   * list:
   *   use_datagrid: true
   * default is true
   */
  public function indexActionUseDatagrid(){
 	 <?php if(isset($this->config['list']['use_datagrid']) && !$this->config['list']['use_datagrid']){ ?>
 		return false;
    <?php }else{	?>
    	return true;
  	<?php } ?>
  }
  
  /**
  * @return string the name of the datagrid class to use
  */
  public function getDatagridClassName(){
    return '<?php echo isset($this->config['list']['datagrid_class'])?$this->config['list']['datagrid_class']:'sfDatagridPropel' ?>';
  }
  
  /**
  * @return string the row action for the link
  */
  public function getRowAction(){
     return <?php echo $this->asPhp(isset($this->config['list']['row_action'])? $this->config['list']['row_action']:($this->params['with_show']?'show':'edit')); ?>;
  }
  
  /**
  * Personnalize sorting columns for virtual fields
  */
  public function getColumnsSorting(){
    $sort= <?php echo $this->asPhp(isset($this->config['list']['columns_sorting'])? $this->config['list']['columns_sorting']:false); ?>;
    if(sizeof($this->getListObjectActions())>0){
    	$sort['_object_actions']='nosort';
    }
    if(sizeof($this->getValue('list.batch_actions'))>0){
    	$sort['CHECK_ALL']='nosort';
    }
    return $sort;
  }
  
  /**
  * Allow you to hide some filters
  **/
  public function getHideFilters()
  {
    return <?php echo $this->asPhp(isset($this->config['list']['hide_filters']) ? $this->config['list']['hide_filters'] : array()) ?>;
<?php unset($this->config['list']['hide_filters']) ?>
  }
  