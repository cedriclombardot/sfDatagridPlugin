[?php use_helper('I18N', 'Date') ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/assets') ?]

<div id="sf_admin_container">
  <h1>[?php echo <?php echo $this->getI18NString('list.title') ?> ?]</h1>

  [?php include_partial('<?php echo $this->getModuleName() ?>/flashes') ?]

  <div id="sf_admin_header">
    [?php include_partial('<?php echo $this->getModuleName() ?>/list_header', array()) ?]
  </div>

  <div id="sf_admin_bar">
    [?php include_partial('<?php echo $this->getModuleName() ?>/list_admin_bar', array('configuration' => $configuration, 'helper' => $helper)) ?]
  </div>

  <div id="sf_admin_content">
    [?php echo sfDatagrid::renderDirect('<?php echo $this->getModelClass() ?>Datagrid', '<?php echo $this->getModuleName() ?>','datagrid'); ?]
	<ul class="sf_admin_actions">
      [?php include_partial('<?php echo $this->getModuleName() ?>/list_actions', array('helper' => $helper)) ?]
    </ul>
  </div>

  <div id="sf_admin_footer">
    [?php include_partial('<?php echo $this->getModuleName() ?>/list_footer', array()) ?]
  </div>
</div>
