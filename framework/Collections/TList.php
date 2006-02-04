<?php
/**
 * TList, TListIterator classes
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @version $Revision: $  $Date: $
 * @package System.Collections
 */

/**
 * TList class
 *
 * TList implements an integer-indexed collection class.
 *
 * You can access, append, insert, remove an item by using
 * {@link itemAt}, {@link add}, {@link insert}, {@link remove}, and {@link removeAt}.
 * To get the number of the items in the list, use {@link getCount}.
 * TList can also be used like a regular array as follows,
 * <code>
 * $list[]=$item;  // append at the end
 * $list[$index]=$item; // $index must be between 0 and $list->Count
 * unset($list[$index]); // remove the item at $index
 * if(isset($list[$index])) // if the list has an item at $index
 * foreach($list as $index=>$item) // traverse each item in the list
 * </code>
 * Note, count($list) will always return 1. You should use {@link getCount()}
 * to determine the number of items in the list.
 *
 * To extend TList by doing additional operations with each addition or removal
 * operations, override {@link insertAt()}, and {@link removeAt()}.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: $  $Date: $
 * @package System.Collections
 * @since 3.0
 */
class TList extends TComponent implements IteratorAggregate,ArrayAccess
{
	/**
	 * internal data storage
	 * @var array
	 */
	private $_d=array();
	/**
	 * number of items
	 * @var integer
	 */
	private $_c=0;

	/**
	 * Constructor.
	 * Initializes the list with an array or an iterable object.
	 * @param array|Iterator the intial data. Default is null, meaning no initialization.
	 * @throws TInvalidDataTypeException If data is not null and neither an array nor an iterator.
	 */
	public function __construct($data=null)
	{
		parent::__construct();
		if($data!==null)
			$this->copyFrom($data);
	}

	/**
	 * Returns an iterator for traversing the items in the list.
	 * This method is required by the interface IteratorAggregate.
	 * @return Iterator an iterator for traversing the items in the list.
	 */
	public function getIterator()
	{
		return new TListIterator($this->_d);
	}

	/**
	 * @return integer the number of items in the list
	 */
	public function getCount()
	{
		return $this->_c;
	}

	/**
	 * Returns the item at the specified offset.
	 * This method is exactly the same as {@link offsetGet}.
	 * @param integer the index of the item
	 * @return mixed the item at the index
	 * @throws TInvalidDataValueException if the index is out of the range
	 */
	public function itemAt($index)
	{
		if($index>=0 && $index<$this->_c)
			return $this->_d[$index];
		else
			throw new TInvalidDataValueException('list_index_invalid',$index);
	}

	/**
	 * Appends an item at the end of the list.
	 * @param mixed new item
	 */
	public function add($item)
	{
		$this->insertAt($this->_c,$item);
	}

	/**
	 * Inserts an item at the specified position.
	 * Original item at the position and the next items
	 * will be moved one step towards the end.
	 * @param integer the speicified position.
	 * @param mixed new item
	 * @throws TInvalidDataValueException If the index specified exceeds the bound
	 */
	public function insertAt($index,$item)
	{
		if($index===$this->_c)
			$this->_d[$this->_c++]=$item;
		else if($index>=0 && $index<$this->_c)
		{
			array_splice($this->_d,$index,0,array($item));
			$this->_c++;
		}
		else
			throw new TInvalidDataValueException('list_index_invalid',$index);
	}

	/**
	 * Removes an item from the list.
	 * The list will first search for the item.
	 * The first item found will be removed from the list.
	 * @param mixed the item to be removed.
	 * @throws TInvalidDataValueException If the item does not exist
	 */
	public function remove($item)
	{
		if(($index=$this->indexOf($item))>=0)
			$this->removeAt($index);
		else
			throw new TInvalidDataValueException('list_item_inexistent');
	}

	/**
	 * Removes an item at the specified position.
	 * @param integer the index of the item to be removed.
	 * @return mixed the removed item.
	 * @throws TOutOfRangeException If the index specified exceeds the bound
	 */
	public function removeAt($index)
	{
		if($index>=0 && $index<$this->_c)
		{
			$this->_c--;
			if($index===$this->_c)
				return array_pop($this->_d);
			else
			{
				$item=$this->_d[$index];
				array_splice($this->_d,$index,1);
				return $item;
			}
		}
		else
			throw new TInvalidDataValueException('list_index_invalid',$index);
	}

	/**
	 * Removes all items in the list.
	 */
	public function clear()
	{
		for($i=$this->_c-1;$i>=0;--$i)
			$this->removeAt($i);
	}

	/**
	 * @param mixed the item
	 * @return boolean whether the list contains the item
	 */
	public function contains($item)
	{
		return $this->indexOf($item)>=0;
	}

	/**
	 * @param mixed the item
	 * @return integer the index of the item in the list (0 based), -1 if not found.
	 */
	public function indexOf($item)
	{
		if(($index=array_search($item,$this->_d,true))===false)
			return -1;
		else
			return $index;
	}

	/**
	 * @return array the list of items in array
	 */
	public function toArray()
	{
		return $this->_d;
	}

	/**
	 * Copies iterable data into the list.
	 * Note, existing data in the list will be cleared first.
	 * @param mixed the data to be copied from, must be an array or object implementing Traversable
	 * @throws TInvalidDataTypeException If data is neither an array nor a Traversable.
	 */
	public function copyFrom($data)
	{
		if(is_array($data) || ($data instanceof Traversable))
		{
			if($this->_c>0)
				$this->clear();
			foreach($data as $item)
				$this->add($item);
		}
		else if($data!==null)
			throw new TInvalidDataTypeException('list_data_not_iterable');
	}

	/**
	 * Merges iterable data into the map.
	 * New data will be appended to the end of the existing data.
	 * @param mixed the data to be merged with, must be an array or object implementing Traversable
	 * @throws TInvalidDataTypeException If data is neither an array nor an iterator.
	 */
	public function mergeWith($data)
	{
		if(is_array($data) || ($data instanceof Traversable))
		{
			foreach($data as $item)
				$this->add($item);
		}
		else if($data!==null)
			throw new TInvalidDataTypeException('list_data_not_iterable');
	}

	/**
	 * Returns whether there is an item at the specified offset.
	 * This method is required by the interface ArrayAccess.
	 * @param integer the offset to check on
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return ($offset>=0 && $offset<$this->_c);
	}

	/**
	 * Returns the item at the specified offset.
	 * This method is required by the interface ArrayAccess.
	 * @param integer the offset to retrieve item.
	 * @return mixed the item at the offset
	 * @throws TInvalidDataValueException if the offset is invalid
	 */
	public function offsetGet($offset)
	{
		if($offset>=0 && $offset<$this->_c)
			return $this->_d[$offset];
		else
			throw new TInvalidDataValueException('list_index_invalid',$offset);
	}

	/**
	 * Sets the item at the specified offset.
	 * This method is required by the interface ArrayAccess.
	 * @param integer the offset to set item
	 * @param mixed the item value
	 * @throws TOutOfRangeException If the index specified exceeds the bound
	 */
	public function offsetSet($offset,$item)
	{
		if($offset===null || $offset===$this->_c)
			$this->insertAt($this->_c,$item);
		else
		{
			$this->removeAt($offset);
			$this->insertAt($offset,$item);
		}
	}

	/**
	 * Unsets the item at the specified offset.
	 * This method is required by the interface ArrayAccess.
	 * @param integer the offset to unset item
	 * @throws TOutOfRangeException If the index specified exceeds the bound
	 */
	public function offsetUnset($offset)
	{
		$this->removeAt($offset);
	}
}


/**
 * TListIterator class
 *
 * TListIterator implements Iterator interface.
 *
 * TListIterator is used by TList. It allows TList to return a new iterator
 * for traversing the items in the list.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: $  $Date: $
 * @package System.Collections
 * @since 3.0
 */
class TListIterator implements Iterator
{
	/**
	 * @var array the data to be iterated through
	 */
	private $_d;
	/**
	 * @var integer index of the current item
	 */
	private $_i;
	/**
	 * @var integer count of the data items
	 */
	private $_c;

	/**
	 * Constructor.
	 * @param array the data to be iterated through
	 */
	public function __construct(&$data)
	{
		$this->_d=&$data;
		$this->_i=0;
		$this->_c=count($this->_d);
	}

	/**
	 * Rewinds internal array pointer.
	 * This method is required by the interface Iterator.
	 */
	public function rewind()
	{
		$this->_i=0;
	}

	/**
	 * Returns the key of the current array item.
	 * This method is required by the interface Iterator.
	 * @return integer the key of the current array item
	 */
	public function key()
	{
		return $this->_i;
	}

	/**
	 * Returns the current array item.
	 * This method is required by the interface Iterator.
	 * @return mixed the current array item
	 */
	public function current()
	{
		return $this->_d[$this->_i];
	}

	/**
	 * Moves the internal pointer to the next array item.
	 * This method is required by the interface Iterator.
	 */
	public function next()
	{
		$this->_i++;
	}

	/**
	 * Returns whether there is an item at current position.
	 * This method is required by the interface Iterator.
	 * @return boolean
	 */
	public function valid()
	{
		return $this->_i<$this->_c;
	}
}

?>