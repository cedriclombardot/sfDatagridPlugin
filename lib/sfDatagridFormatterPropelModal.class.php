<?php
/**
 * @author Cédric LOMBARDO 
 * @package sfDatagridPlugin
 * Modal tb formatter 
 */

class sfDatagridFormatterPropelModal extends sfDatagridFormatterPropel {

 /**
   * Get the html output for the row
   *
   * @param sfDatagrid $object The datagrid object
   * @param array $rowValues The array with the values
   * @param string $rowClass The css class for the row
   * @param string $rowIndexDefaultValue The RowIndex Default if ! $rowIndex
   * @return string The html output for the row
   */
   public function renderRow($object, $rowValues, $rowClass = null,$rowIndexDefaultValue=null)
   { 
     $this->onClick='tb_show(\'\',\'%url%?TB_iframe=true\')';
     return parent::renderRow($object,$rowValues,$rowClass,$rowIndexDefaultValue);
   }
   
}