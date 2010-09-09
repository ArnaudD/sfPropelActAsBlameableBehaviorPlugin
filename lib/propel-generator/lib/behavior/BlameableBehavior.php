<?php

/**
 * This file is part of the sfPropelActAsBlameableBehaviorPlugin
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

/**
 * Gives a model class the ability to track creation and last modification authors
 * Columns have to be specified manualy in your schema
 *
 * @author     Arnaud Didry
 * @package    propel.generator.behavior
 */
class BlameableBehavior extends Behavior
{
	// default parameters value
	protected $parameters = array(
		'create_column' => 'created_by',
		'update_column' => 'updated_by'
		'delete_column' => 'deleted_by'
	);
	
	/**
	 * Tells if the table has a create column
	 *
	 * @param  string $column One of the behavior colums, 'create_column' or 'update_column', 'delete_column'
	 * @return boolean
	 */
	protected function hasColumn($column)
	{
		return $this->getTable()->containsColumn($this->getParameter($column));
	}

	/**
	 * Generate the PHP code needed to retrieve the user value from sfUser
	 *
	 * @param  string $column One of the behavior colums, 'create_column' or 'update_column', 'delete_column'
	 * @return string 
	 */
	protected function generateUserValueGetter($column)
	{
		switch($this->getColumnForParameter($column)->getPdoType())
		{
			case PDO::PARAM_INT:
				return 'getId';
			case PDO::PARAM_STR:
				return '__toString';
			default:
				throw new sfException('[sfPropelActAsBlameable] column "'.$this->getParameter($column).'" must be int or string');
		}
	}
	
	/**
	 * Generate the PHP code needed to retrieve the user column value (id or string).
	 *
	 * @param  string $column One of the behavior colums, 'create_column' or 'update_column', 'delete_column'
	 * @return string
	 */
	protected function generateUserColumnValue($column)
	{
		return 'sfContext::getInstance()->getUser()->'.$this->generateUserValueGetter($column)."()";
	}
	
	/**
	 * Get the setter of one of the columns of the behavior
	 * 
	 * @param     string $column One of the behavior colums, 'create_column' or 'update_column'
	 * @return    string The related setter, 'setCreatedOn' or 'setUpdatedOn'
	 */
	protected function getColumnSetter($column)
	{
		return 'set' . $this->getColumnForParameter($column)->getPhpName();
	}
	
	protected function getColumnConstant($columnName, $builder)
	{
		return $builder->getColumnConstant($this->getColumnForParameter($columnName));
	}
	
	/**
	 * Add code in ObjectBuilder::preUpdate
	 *
	 * @return    string The code to put at the hook
	 */
	public function preUpdate($builder)
	{
		if(!$this->hasColumn('update_column'))
			return;

		return "if (\$this->isModified() && !\$this->isColumnModified(" . $this->getColumnConstant('update_column', $builder) . ")) {
	\$this->" . $this->getColumnSetter('update_column') . "(".$this->generateUserColumnValue('update_column').");
}";
	}
	
	/**
	 * Add code in ObjectBuilder::preInsert
	 *
	 * @return    string The code to put at the hook
	 */
	public function preInsert($builder)
	{
		$php = '';

		if($this->hasColumn('create_column'))
		{
			$php .= "if (!\$this->isColumnModified(" . $this->getColumnConstant('create_column', $builder) . ")) {
	\$this->" . $this->getColumnSetter('create_column') . "(".$this->generateUserColumnValue('create_column').");
}\n";
		}

		if($this->hasColumn('update_column'))
		{
			$php .= "if (!\$this->isColumnModified(" . $this->getColumnConstant('update_column', $builder) . ")) {
	\$this->" . $this->getColumnSetter('update_column') . "(".$this->generateUserColumnValue('update_column').");
}";
		}

		return $php;
	}

	public function objectMethods($builder)
	{
		$php = '';

		if($this->hasColumn('create_column'))
		{
		    $php .= "
/**
 * Mark the current object so that the updated_by column doesn't get updated during next save
 *
 * @return     " . $builder->getStubObjectBuilder()->getClassname() . " The current object (for fluent API support)
 */
public function keepUpdatedByUnchanged()
{
	\$this->modifiedColumns[] = " . $this->getColumnConstant('update_column', $builder) . ";
	return \$this;
}
";
	    }

		if($this->hasColumn('deleted_column')) // realy useful ?
		{
		    $php .= "
/**
 * Mark the current object so that the deleted_by column doesn't get updated during next save
 *
 * @return     " . $builder->getStubObjectBuilder()->getClassname() . " The current object (for fluent API support)
 */
public function keepDeletedByUnchanged()
{
	\$this->modifiedColumns[] = " . $this->getColumnConstant('delete_column', $builder) . ";
	return \$this;
}
";
        }

        return $php;
    }
}
