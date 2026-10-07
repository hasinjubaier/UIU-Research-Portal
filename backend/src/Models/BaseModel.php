<?php

namespace App\Models;

use PDO;

abstract class BaseModel
{
    protected string $table;
    protected array $fillable = [];
    protected string $primaryKey = 'id';
    protected bool $hasSoftDelete = true;
    protected PDO $connection;

    public function __construct(PDO $database)
    {
        $this->connection = $database;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function findById(int|string $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        if ($this->hasSoftDelete) {
            $sql .= " AND deleted_at IS NULL";
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        return $record ?: null;
    }

    public function all(string $orderBy = 'id DESC'): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($this->hasSoftDelete) {
            $sql .= " WHERE deleted_at IS NULL";
        }
        $sql .= " ORDER BY {$orderBy}";

        $stmt = $this->connection->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int|string
    {
        $fields = array_intersect_key($data, array_flip($this->fillable));
        if ($this->hasSoftDelete && !isset($fields['created_at'])) {
            $fields['created_at'] = date('Y-m-d H:i:s');
        }

        $columns = implode(', ', array_keys($fields));
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));

        $stmt = $this->connection->prepare("
            INSERT INTO {$this->table} ({$columns})
            VALUES ({$placeholders})
        ");

        $stmt->execute(array_values($fields));
        return $this->connection->lastInsertId();
    }

    public function update(int|string $id, array $data): bool
    {
        $fields = array_intersect_key($data, array_flip($this->fillable));
        if ($this->hasSoftDelete && !isset($fields['updated_at'])) {
            $fields['updated_at'] = date('Y-m-d H:i:s');
        }

        if (empty($fields)) {
            return false;
        }

        $sets = [];
        $values = [];
        foreach ($fields as $col => $val) {
            $sets[] = "{$col} = ?";
            $values[] = $val;
        }

        $values[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = ?";
        if ($this->hasSoftDelete) {
            $sql .= " AND deleted_at IS NULL";
        }

        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($values);
    }

    public function delete(int|string $id): bool
    {
        if ($this->hasSoftDelete) {
            $stmt = $this->connection->prepare("
                UPDATE {$this->table}
                SET deleted_at = ?
                WHERE {$this->primaryKey} = ?
            ");
            return $stmt->execute([date('Y-m-d H:i:s'), $id]);
        }

        $stmt = $this->connection->prepare("
            DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?
        ");
        return $stmt->execute([$id]);
    }

    public function paginate(int $page = 1, int $perPage = 20, array $where = [], string $orderBy = 'id DESC'): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $conditions = [];
        $params = [];

        if ($this->hasSoftDelete) {
            $conditions[] = "deleted_at IS NULL";
        }

        foreach ($where as $field => $value) {
            $conditions[] = "{$field} = ?";
            $params[] = $value;
        }

        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        // Count total
        $countStmt = $this->connection->prepare("SELECT COUNT(*) FROM {$this->table} {$whereClause}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Fetch records
        $sql = "SELECT * FROM {$this->table} {$whereClause} ORDER BY {$orderBy} LIMIT ? OFFSET ?";
        $stmt = $this->connection->prepare($sql);

        // Bind params with offset & limit as integers
        $paramIdx = 1;
        foreach ($params as $param) {
            $stmt->bindValue($paramIdx++, $param);
        }
        $stmt->bindValue($paramIdx++, $perPage, PDO::PARAM_INT);
        $stmt->bindValue($paramIdx, $offset, PDO::PARAM_INT);

        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data'       => $records,
            'pagination' => [
                'page'      => $page,
                'per_page'  => $perPage,
                'total'     => $total,
                'pages'     => ceil($total / max(1, $perPage)),
                'has_more'  => $page < ceil($total / max(1, $perPage))
            ]
        ];
    }

    public function count(array $where = []): int
    {
        $conditions = [];
        $params = [];

        if ($this->hasSoftDelete) {
            $conditions[] = "deleted_at IS NULL";
        }

        foreach ($where as $field => $value) {
            $conditions[] = "{$field} = ?";
            $params[] = $value;
        }

        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM {$this->table} {$whereClause}");
        $stmt->execute($params);

        return (int)$stmt->fetchColumn();
    }
}
