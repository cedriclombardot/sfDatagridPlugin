<?php
/**
 * This is class sfDatagridPropel
 * 
 * @author		David Zeller	<zellerda01@gmail.com>
 */
class sfDatagridPropel extends sfDatagrid
{
	protected
		$peerTable = null,				// The name of the table without "Peer" at the end
		$criteria = null,				// The criteria (instance of Criteria)
		$tableSuffix = null;			// The table suffix (e.g. If you code and SVN select you must specify the table suffix for sorting)
	
	/**
	 * Class constructor
	 *
	 * @param string $datagridName The name of the datagrid
	 * @param string $peerTable The name of the class table without "Peer"
	 * @param mixed $tableSuffix	The table suffix (e.g. If you code and SVN select you must specify the table suffix for sorting)
	 */
	public function __construct($datagridName, $peerTable, $criteria = null, $tableSuffix = null)
	{
		parent::__construct($datagridName);
		
		if(is_null($criteria))
		{
			$this->criteria = new Criteria();
		}
		else
		{
			$this->criteria = $criteria;
		}
		
		$this->peerTable = $peerTable;
		$this->tableSuffix = $tableSuffix;
	}
		
	/**
	 * Prepare the datagrid
	 *
	 * @param string The pager peer method
	 * @return object The propel resultset
	 * @see	parent::prepare()
	 */
	public function prepare($peerMethod = null, $countMethod = 'doCount')
	{	
		parent::prepare();
		
		// Sort the criteria
		$this->setCriteriaSorting();
		
		$this->addSearch();
		
		// Define the default search fields
		foreach(array_keys($this->columns) as $column)
		{
			if(!array_key_exists($column, $this->filtersTypes))
			{
				$this->filtersTypes[$column] = $this->getColumnType($column);
			}
		}
		
		// Set the pager
		$p = new sfPropelPager($this->peerTable, $this->rowLimit);
		$p->setPeerMethod($peerMethod);
		$p->setCriteria($this->criteria);
		$p->setPage($this->page);
		$p->setPeerCountMethod($countMethod);
		$p->init();
		
		$this->pager = $p;
		return $p;
	}
	
	/**
	 * Get the type of the database field
	 *
	 * @param string $column The column name
	 * @return string The type of the field
	 */
	protected function getColumnType($column)
	{
		if(array_key_exists($column, $this->columnsSort))
		{
			if($this->columnsSort[$column] != 'nosort')
			{
				$tmp = explode('::', $this->columnsSort[$column]);
				
				$tableName = $tmp[0];
				$field = $tmp[1];
			}
			else
			{
				if(defined($this->peerTable . $this->tableSuffix . 'Peer::' . strtoupper($column)))
				{
					$tableName = $this->peerTable . $this->tableSuffix . 'Peer';
					$field = $column;
				}
				else
				{
					return 'NOTYPE';
				}
			}
		}
		else
		{
			$tableName = $this->peerTable . $this->tableSuffix . 'Peer';
			$field = $column;
		}
		
		$map = call_user_func(array($tableName, 'getTableMap'));
		return $map->getColumn($field)->getType();
	}
	
	/**
	 * Add the search options to the criteria
	 */
	protected function addSearch()
	{		
		$c = $this->criteria;
		$columnsIds = array_keys($this->columns);
		
		$config = Propel::getConfiguration();
		
		if ($config['datasources']['propel']['adapter'] == 'pgsql')
		{
			$comp = Criteria::ILIKE;
		}
		else
		{
			$comp = Criteria::LIKE;
		}
		
		foreach($columnsIds as $col)
		{
			if(is_array($this->search) && array_key_exists($col, $this->search) && !is_null($this->search[$col]) && $this->search[$col] != '')
			{
				switch(strtoupper($this->getColumnType($col)))
				{
					case 'NOTYPE':
						// Do nothing
						break;
						
					case 'BOOLEAN':
						$c->add($this->getColumnSortingOption($col), $this->search[$col]);
						break;
						
					case (strtoupper($this->getColumnType($col)) == 'DATE' || strtoupper($this->getColumnType($col)) == 'TIMESTAMP'):
						if(array_key_exists('start', $this->search[$col]) && $this->search[$col]['start'] != '')
						{
							$c1 = $c->getNewCriterion($this->getColumnSortingOption($col), format_date(strtotime($this->search[$col]['start']), 'yyyy-MM-dd'), Criteria::GREATER_EQUAL);
							$c->addAnd($c1);
						}
						
						if(array_key_exists('stop', $this->search[$col]) && $this->search[$col]['stop'] != '')
						{
							$c2 = $c->getNewCriterion($this->getColumnSortingOption($col), format_date(strtotime($this->search[$col]['stop']), 'yyyy-MM-dd'), Criteria::LESS_EQUAL);
							$c->addAnd($c2);
						}
						break;
						
					default:
						$c->add($this->getColumnSortingOption($col), '%' . $this->search[$col] . '%', $comp);
						break;
				}
			}
		}
		
		$this->criteria = $c;
		//echo $this->criteria->toString();
	}
	
	/**
	 * Add the sorting paramater to the criteria
	 */
	protected function setCriteriaSorting()
	{		
		if($this->sortOrder == 'asc')
		{
			$this->criteria->addAscendingOrderByColumn($this->getColumnSortingOption($this->sortBy));
		}
		else
		{
			$this->criteria->addDescendingOrderByColumn($this->getColumnSortingOption($this->sortBy));
		}
	}
	
	/**
	 * Get the column constant for sorting
	 *
	 * @param string $columnName The name of the column
	 * @return object The column sorting constant
	 */
	protected function getColumnSortingOption($columnName)
	{
		if(array_key_exists($columnName, $this->columnsSort) && $this->columnsSort[$columnName] != 'nosort')
		{
			return constant($this->columnsSort[$columnName]);
		} 
		else 
		{
			return constant($this->peerTable . $this->tableSuffix . 'Peer::' . strtoupper($columnName));
		}
	}
}
?>