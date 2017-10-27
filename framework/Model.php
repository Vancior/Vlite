<?php
/**
 * Framework Model
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/23
 * Time: 19:42
 */

namespace Vlite;

class Model
{
  private $table;
  private $where;
  private $order;
  private $limit = 30;
  private $offset = 0;

  private static $OP1 = ['>', '<', '>=', '<='];

  public function __construct($table)
  {
    $this->table = $table;
  }

  public function select()
  {
    
  }

  public function where($condition)
  {
    if (!is_array($condition)) {
      $this->where = ' ' . $condition;
      return $this;
    }

    $where_keys = array_keys($condition);
    $where_AND = preg_grep("/AND/i", $where_keys);
    $where_OR = preg_grep("/OR/i", $where_keys);

    if (!empty($where_AND) || !empty($where_OR)) {
      $this->where = $this->dataImploded($where_keys[0], $condition[$where_keys[0]]);
    } else {
      $where = '';
      foreach ($condition as $key => $value) {
        if (is_string($value))
          $value = '\'' . $value . '\'';
        $where .= ' AND ' . $this->addQuotation($key) . ' = ' . $value;
      }
      $where = substr($where, 4);
      $this->where = $where;
    }

    $this->where = 'WHERE ' . $this->where;
//    return $this->where;
    return $this;
  }

  public function order($order)
  {
    if (preg_match('/\S*\s*(desc|asc)/i', $order))
      throw new \UnexpectedValueException('Order String Not Correct');

    $this->order = $order;

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
      $query .= ', ' . $value;
      $columns .= ', ' . $key;
    }
    $columns = "[$this->table] (" . substr($columns, 2) . ")";
    $query = "INSERT INTO $columns VALUES (" . substr($query, 2) . ");";

    return $query;
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
        $columns .= ', ' . $k;
      }
      $columns = "[$this->table] (" . substr($columns, 2) . ")";
      $query .= "INSERT INTO $columns VALUES (" . substr($values, 2) . ");\n";
    }

    return $query;
  }

  public function delete($table = null)
  {
    if (empty($this->where)) {
      if (empty($table))
        throw new \Exception('Give the table name to confirm deleting the whole table');

      if ($this->table != $table)
        throw new \InvalidArgumentException('Confirming Deleting Failure');

      $query = "DELETE FROM $this->table;";
    } else {
      $query = "DELETE FROM $this->table $this->where;";
    }

    return $query;
  }

  private function dataImploded($key, $value)
  {
    $where = '';

    if (strtoupper($key) == 'AND') {
      foreach ($value as $k => $v)
        $where .= ' AND ( ' . $this->dataImploded($k, $v) . ' )';
      $where = substr($where, 4);
    } elseif (strtoupper($key) == 'OR') {
      foreach ($value as $k => $v)
        $where .= ' OR ( ' . $this->dataImploded($k, $v) . ' )';
      $where = substr($where, 3);
    } else {
      if (preg_match('/(\S*)\s*\[(\S+)]/', $key, $matches)) {
        $where = $this->handleOperator($matches[1], $value, $matches[2]);
      } else {
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
    return '"' . $key . '"';
  }
}