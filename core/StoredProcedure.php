<?php
namespace app\core;

class StoredProcedure
{
    protected Application $app;
    protected string $spName;
    protected \PDO $pdo;
    protected string $query;
    protected array $parameters = [];
    protected $statement;
    protected $rows;

    public function __construct()
    {
        $this->app = Application::$app;
        $dbConfigs = $this->app->getParam("db_configs");

        $db_dsn = $dbConfigs["dns"];
        $db_user = $dbConfigs["username"];
        $db_password = $dbConfigs["password"];

        $this->pdo = new \PDO($db_dsn, $db_user, $db_password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    }

    public function setName(string $spName)
    {
        $this->spName = $spName;
        return $this;
    }
    public function setParameters(array $params)
    {
        foreach($params AS $key => $param)
        {
            $this->parameters[$key] = $param;
        }
        return $this;
    }
    public function prepare()
    {
        $this->query = "CALL {$this->spName}(";
        $parameters = [];
        foreach($this->parameters AS $key => $parameter)
        {
            if(is_string($parameter))
            {
                $parameters[] = "'{$parameter}'";
            }
            else if(is_numeric($parameter))
            {
                $parameters[] = "{$parameter}";
            }
        }
        if(count($parameters))
        {
            $this->query .= implode(", ",$parameters);
        }
        $this->query .= ");";
        $options = [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL];
        $this->statement = $this->pdo->prepare($this->query, $options);
        return $this;
    }
    public function getQuery()
    {
        return $this->query;
    }
    public function execute()
    {
        $this->statement->execute();
        $this->parameters = [];
        return $this;
    }
    public function fetch()
    {
        while($row = $this->statement->fetch(\PDO::FETCH_ASSOC))
        {
            $this->rows[] = $row;
        }

        return $this->rows;
    }
}
