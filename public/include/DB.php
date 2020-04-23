<?php

/*
Welcome to Dave-Smith Johnson & Son family bank!

This is a tool to assist with scam baiting, especially with scammers attempting to
obtain bank information or to attempt to scam you into giving money.

This tool is licensed under the MIT license (copy available here https://opensource.org/licenses/mit), so it
is free to use and change for all users. Scam bait as much as you want!

This project is heavily inspired by KitBoga (https://youtube.com/c/kitbogashow) and his LR. Jenkins bank.
I thought that was a very cool idea, so I created my own version. Now it's out there for everyone!

Please, waste these people's time as much as possible. It's fun and it does good for everyone.

*/

class SimpleStatement
{
    private $queryString;

    public $result;
    public $affectedRows;

    function __construct($statement)
    {
        $this->queryString = $statement;
    }

    function __destruct()
    {
    }

    function getStatement()
    {
        return $this->queryString;
    }
}

class PreparedStatement
{
    private $template;
    private $values = array();
    private $types;

    public $result;
    public $affectedRows;

    public $success;

    function __construct($template, $boundValues, $types)
    {
        $this->template = $template;
        $this->values = $boundValues;
        $this->types = $types;
    }

    function __destruct()
    {
    }

    function getTemplate()
    {
        return $this->template;
    }

    function getBoundValue($index)
    {
        return $this->values[$index];
    }

    function getBoundValues()
    {
        return $this->values;
    }

    function getTypes()
    {
        return $this->types;
    }

    function rebindValues($types, $values)
    {
        $this->values = $values;
    }

    function rebindValue($index, $type, $value)
    {
        $types[$index] = $type;
        $values[$index] = $value;
    }
}

class DB
{

    private $sql;
    private $statement;

    private $host;
    private $database;
    private $username;
    private $password;
    private $port;

    private $autocommit = true;
    private $uncommittedChanges = false;

    private $statementPrepared = false;
    private $preparedObject;

    function __construct($hostname, $dbname, $username, $password, $port = 3306)
    {
        $this->host = $hostname;
        $this->port = $port;
        $this->database = $dbname;
        $this->username = $username;
        $this->password = $password;

        $this->sql = new mysqli($hostname, $username, $password, $dbname, $port);
    }

    function __destruct()
    {
    }

    function __get($property)
    {
        return $this->property;
    }

    function __set($property, $val)
    {
        $this->property = $val;
    }

    function validateConnection()
    {
        if ($this->sql->connect_errno) {
            return array($this->sql->connect_errno, $this->sql->connect_error);
        }
    }

    function validateAction()
    {
        return array($this->sql->errno, $this->sql->error);
    }

    function configureAutoCommit($commit = true)
    {
        $this->sql->autocommit($commit);
        $this->autocommit = $commit;
    }

    function commit()
    {
        if (!$this->autocommit) {
            $success = $this->sql->commit();
        }

        $this->uncommittedChanges = $success;
        return $success;
    }

    function disconnect()
    {
        if ($this->statementPrepared) $this->statement->close();
        $this->sql->close();
    }

    function safeDisconnect()
    {
        if ($this->autocommit = false && $this->uncommittedChanges) $this->commit();


        if ($this->statementPrepared) $this->statement->close();
        $this->disconnect();
    }

    function unsafeQuery(SimpleStatement $queryObject)
    {
        $this->uncommittedChanges = true;

        $statement = $queryObject->getStatement();

        $result = $this->sql->query($statement);

        if ($result != false) {
            $queryObject->result = $result->fetch_assoc();
            $queryObject->affectedRows = $this->sql->affected_rows;
        } else {
            $queryObject->result = $result;
            $queryObject->affectedRows = -1;
        }
    }

    function prepareQuery(PreparedStatement $queryObject)
    {
        $this->preparedObject = $queryObject;

        $this->statement = $this->sql->prepare($queryObject->getTemplate());

        if (!$this->statement) return;

        $safeBinds = $this->sanitizeInputs($queryObject->getBoundValues());
        $this->statement->bind_param($queryObject->getTypes(), ...$safeBinds);

        $this->statementPrepared = true;
    }

    function rebindQuery(PreparedStatement $queryObject)
    {
        $safeBinds = $this->sanitizeInputs($queryObject->getBoundValues());

        $this->statement->bind_param($queryObject->getTypes(), ...$safeBinds);
    }

    function clearQuery()
    {
        $this->statement->close();
        $this->statement = null;

        $this->statementPrepared = false;
    }

    function query()
    {
        if ($this->statementPrepared) {
            $result = $this->statement->execute();

            if ($result) {
                $this->preparedObject->result = $this->get_prepared_result($this->statement);
                $this->preparedObject->affectedRows = $this->statement->affected_rows;
            } else {
                return;
            }

            $this->preparedObject->success = $result;

            $this->uncommittedChanges = true;
        }
    }


    private function get_prepared_result(\mysqli_stmt $statement)
    {
        $result = array();
        $statement->store_result();
        for ($i = 0; $i < $statement->num_rows; $i++) {
            $metadata = $statement->result_metadata();
            $params = array();
            while ($field = $metadata->fetch_field()) {
                $params[] = &$result[$i][$field->name];
            }
            call_user_func_array(array($statement, 'bind_result'), $params);
            $statement->fetch();
        }
        return $result;
    }

    private function sanitizeInputs(array $inputs)
    {
        $converted = $inputs;

        foreach ($converted as &$value) {
            $value = htmlentities($value);
            $value = $this->sql->escape_string($value);
        }

        return $converted;
    }
}