<?php

namespace IntecPhp\Entity;

class ConsumerEntity extends Entity
{
    protected $name = 'consumidor';
    protected $id = 'id';

    public function countTotalRecords()
    {
        $sql = "select count(*) as total from $this->name";

        $stmt = $this->db->query($sql, []);

        if ($stmt) {
            return $stmt->fetch();
        }
        throw new \Exception("Não foi possível consultar a tabela consumidor");
    }

    public function countRegionRecords(string $region)
    {
        $sql = "select count(*) as total from $this->name where regiao = ?";

        $stmt = $this->db->query($sql, [$region]);

        if ($stmt) {
            return $stmt->fetch();
        }
        throw new \Exception("Não foi possível consultar a tabela consumidor");
    }

    public function countStateRecords(string $state)
    {
        $sql = "select count(*) as total from $this->name where uf = ?";

        $stmt = $this->db->query($sql, [$state]);

        if ($stmt) {
            return $stmt->fetch();
        }
        throw new \Exception("Não foi possível consultar a tabela consumidor");
    }

    public function countCompanyComplaintByState(string $state)
    {
        $sql = "select count(*) as total, nome_fantasia from $this->name where uf = ? group by nome_fantasia order by total desc limit 5";
        $params = [$state];

        $stmt = $this->db->query($sql, $params);

        if ($stmt) {
            return $stmt->fetchAll();
        }
        throw new \Exception("Não foi possível consultar a tabela consumidor");
    }

    public function getCompanyAverageGrade(string $company, int $month)
    {
        $sql = "select avg(nota_consumidor) as avg_grade from $this->name where nome_fantasia = ? and month(data_finalizacao) = ?";
        $params = [$company, $month];

        $stmt = $this->db->query($sql, $params);

        if ($stmt) {
            return $stmt->fetch();
        }
        throw new \Exception("Não foi possível consultar a tabela consumidor");
    }
}
