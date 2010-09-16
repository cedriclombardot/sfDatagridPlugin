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