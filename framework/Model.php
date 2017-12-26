<?php
/**
 * Framework Model
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/23
 * Time: 19:42
 *
 *
 */

namespace Vlite;

/**
 * Class Model
 *
 * @package Vlite
 */
class Model
{
  private $table;
  private $field = '*';
  private $where;
  private $order;
  private $join;
  private $on;
  private $limit = 15;
  private $offset = 0;
  private $lock = false;

  private static $OP1 = ['>', '<', '>=', '<='];

  public function __construct($table)
  {
    $this->table = $this->addQuotation($table);
  }

  public function select()
  {
    $select = 'SELECT ' . $this->field . ' FROM ' . $this->table . ' ';
    if (!empty($this->join))
      $select .= 'JOIN ' . $this->join . ' ';
    if (!empty($this->on))
      $select .= 'ON ' . $this->on . ' ';
    if (!empty($this->where))
      $select .= 'WHERE ' . $this->where . ' ';
    if ($this->lock)
      $select .= 'FOR UPDATE';

    $this->reset_query();
    return Db::query($select);
  }

  public function field($field)
  {
    if (!is_string($field))
      throw new \InvalidArgumentException('Field(string) Required');

    $this->field = trim($field);

    return $this;
  }

  /**
   * @param $condition
   * @return Model $this
   */
  public function where($condition)
  {
    if (!is_array($condition)) {
      $this->where = trim($condition);
      return $this;
    }

    $where_keys = array_keys($condition);
    $where_AND = preg_grep("/AND/i", $where_keys);
    $where_OR = preg_grep("/OR/i", $where_keys);

    if (!empty($where_AND) || !empty($where_OR)) {
      $this->where = $this->dataImploded($where_keys[0], $condition[$where_keys[0]]);
    } else {
      $this->where = $this->dataImploded('AND', $condition);
    }
//    if (!empty($where_AND) || !empty($where_OR)) {
//      $this->where = $this->dataImploded($where_keys[0], $condition[$where_keys[0]]);
//    } else {
//      $where = '';
//      foreach ($condition as $key => $value) {
//        if (is_string($value))
//          $value = '\'' . $value . '\'';
//        $where .= ' AND ' . $this->addQuotation($key) . ' = ' . $value;
//      }
//      $where = substr($where, 4);
//      $this->where = $where;
//    }

    return $this;
  }

  public function order($order)
  {
    if (preg_match('/\S*\s*(desc|asc)/i', $order))
      throw new \UnexpectedValueException('Order String Not Correct');

    $this->order = $order;

    return $this;
  }

  /**
   * @param string $join
   * @return Model $this
   */
  public function join($join)
  {
    if (!is_string($join))
      throw new \InvalidArgumentException('Table Name(string) Required');

    $this->join = trim($join);

    return $this;
  }

  public function on($on)
  {
    if (!is_string($on))
      throw new \InvalidArgumentException('Join Condition(string) Required');

    $this->on = trim($on);

    return $this;
  }

  public function limit($limit)
  {
    if (!is_numeric($limit))
      throw new \InvalidArgumentException('Number Required');

    $this->limit = $limit;

    return $this;
  }

  public function page($page = 1)
  {
    if (!is_numeric($page))
      throw new \InvalidArgumentException('Number Required');

    $this->offset = $this->limit * ($page - 1);

    return $this;
  }

  public function insert($data)
  {
    if (!is_array($data))
      throw new \InvalidArgumentException('Array Required');

    $columns = '';
    $query = '';
    foreach ($data as $key => $value) {
      if (is_string($value))
        $query .= ', ' . '\'' . $value . '\'';
      else
        $query .= ', ' . $value;
      $columns .= ', ' . $this->addQuotation($key);
    }
    $columns = "$this->table (" . substr($columns, 2) . ")";
    $query = "INSERT INTO $columns VALUES (" . substr($query, 2) . ")";

    $this->reset_query();
    return Db::query($query);
  }

  public function insertAll($data)
  {
    if (!is_array($data))
      throw new \InvalidArgumentException('Array Required');

    $query = '';
    foreach ($data as $value) {
      if (!is_array($value))
        throw new \UnexpectedValueException('Array Required');

      $columns = '';
      $values = '';
      foreach ($value as $k => $v) {
        if (is_string($v))
          $v = '\'' . $v . '\'';
        $values .= ', ' . $v;
        $columns .= ', ' . $this->addQuotation($k);
      }
      $columns = "$this->table (" . substr($columns, 2) . ")";
      $query .= "INSERT INTO $columns VALUES (" . substr($values, 2) . ");\n";
    }

    $this->reset_query();
    return Db::multi_query($query);
  }

  public function delete($table = null)
  {
    if (empty($this->where)) {
      if (empty($table))
        throw new \Exception('Give the table name to confirm deleting the whole table');

      if ($this->table != $table)
        throw new \InvalidArgumentException('Confirming Deleting Failure');

      $query = "DELETE FROM $this->table";
    } else {
      $query = "DELETE FROM $this->table WHERE $this->where";
    }

    $this->reset_query();
    return Db::query($query);
  }

  public function lock()
  {
    $this->lock = true;
  }

  private function dataImploded($key, $value)
  {
    $where = '';

    if (strtoupper($key) == 'AND') { // for 'AND' => [e1, e2, e3]
      foreach ($value as $k => $v)
        $where .= ' AND ( ' . $this->dataImploded($k, $v) . ' )';
      $where = substr($where, 4);
    } elseif (strtoupper($key) == 'OR') { // for 'OR' => [e1, e2, e3]
      foreach ($value as $k => $v)
        $where .= ' OR ( ' . $this->dataImploded($k, $v) . ' )';
      $where = substr($where, 3);
    } else {
      if (preg_match('/(\S*)\s*\[(\S+)]/', $key, $matches)) { // for 'field[>]' => 12 stuff
        $where = $this->handleOperator($matches[1], $value, $matches[2]);
      } else { // for 'field' => [v1, v2, v3]
        if (is_array($value)) {
          $where = $this->addQuotation($key) . ' IN (';
          $range = '';
          foreach ($value as $v) {
            if (is_numeric($v))
              $range .= ', ' . $v;
            else
              $range .= ', \'' . $v . '\'';
          }
          $range = substr($range, 2);
          $where = $where . $range . ')';
        } else {
          if (is_string($value))
            $value = '\'' . $value . '\'';
          $where = $this->addQuotation($key) . ' = ' . $value;
        }
      }
    }

    return $where;
  }

  private function handleOperator($key, $value, $op)
  {
    if (in_array($op, self::$OP1))
      return $this->addQuotation($key) . ' ' . $op . ' ' . $value;
    if ($op == '!')
      return $this->addQuotation($key) . ' ' . $op . '= ' . $value;

    if ($op == '<>') {
      if (!is_array($value))
        throw new \UnexpectedValueException('Range Required');
      $str = $this->addQuotation($key) . ' BETWEEN ';
      if (is_string($value[0])) {
        $value[0] = '\'' . $value[0] . '\'';
        $value[1] = '\'' . $value[1] . '\'';
      }
      $str .= $value[0] . ' AND ' . $value[1];

      return $str;
    } elseif ($op == '><') {
      if (!is_array($value))
        throw new \UnexpectedValueException('Range Required');
      $str = $this->addQuotation($key) . ' NOT BETWEEN ';
      if (is_string($value[0])) {
        $value[0] = '\'' . $value[0] . '\'';
        $value[1] = '\'' . $value[1] . '\'';
      }
      $str .= $value[0] . ' AND ' . $value[1];

      return $str;
    } else
      throw new \UnexpectedValueException('Unexpected Operator');
  }

  private function addQuotation($key)
  {
    return '`' . $key . '`';
  }

  private function reset_query()
  {
    unset($this->where);
    unset($this->order);
    unset($this->join);
    unset($this->on);
    $this->field = '*';
    $this->lock = false;
    $this->offset = 0;
  }
}