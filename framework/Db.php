<?php
/**
 * Framework Db
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/23
 * Time: 20:12
 */

namespace Vlite;

class Db
{
  public $mysqli;
  public static $db;

  public function __construct()
  {
    $config = require_once BASE_CONFIG_PATH . '/database.php';
    if (empty($config))
      throw new \ErrorException('Cannot find database config');
    $this->mysqli = new \mysqli($config['host'], $config['username'], $config['passwd'], $config['dbname'], $config['port']);
    if (mysqli_connect_errno()) {
      throw \Exception("Mysqli connection error: {$this->mysqli->connect_errno}");
    }
    $this->mysqli->autocommit(true);
    $this->mysqli->set_charset('UTF-8');
    self::$db = $this;
  }

  public function __destruct()
  {
    if ($this->mysqli != null)
      $this->mysqli->close();
  }

  public static function query($sql)
  {
//    echo $sql;
    $result = self::$db->mysqli->query($sql);
    if ($result === false)
      return false;
    elseif ($result === true)
      return true;
    $data = [];
    while ($obj = $result->fetch_object())
      $data[] = $obj;
    $result->free();
    return $data;
  }

  public static function multi_query($sql)
  {
    return self::$db->mysqli->multi_query($sql);
  }

  public static function begin_transaction()
  {
    return self::$db->mysqli->begin_transaction();
  }

  public static function commit()
  {
    return self::$db->mysqli->commit();
  }

  public static function rollback()
  {
    return self::$db->mysqli->rollback();
  }
}