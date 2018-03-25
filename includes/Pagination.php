<?php
class Pagination
{
    private $connection;
    public $current_page;
    public $per_page;
    public $total_count;
    public $total_pages;
    public $sql;

    public function __construct(Int_Connection $connection,$page = 1, $per_page = 2)
    {
        $this->connection = $connection;
        $this->current_page = (int)$page;
        $this->per_page = (int)$per_page;
    }
    public function set_sql($sql){
        $this->sql = $sql;
        $this->set_total_pages($this->sql);
    }
    public function get_offset()
    {
        //page 1 has offset of 0 (1-1) * 20
        //page 2 has offset of (2-1) * 20
        //page 2 starts with item 21
        $sql = $this->sql . " LIMIT {$this->per_page}";
        $offset = (($this->current_page - 1) * $this->per_page);
        if ($offset > 0){
            $sql .= ", " . $offset . "";
    }
            return $sql;
    }
    public function set_total_pages()
    {
        $this->connection->get_query(preg_replace('/SELECT .* FROM/', 'SELECT COUNT(*) as total_count FROM', $this->sql), $this);
        $this->total_pages = ceil($this->total_count / $this->per_page);
    }
    public function get_previous_page()
    {
        $previous_page = $this->current_page - 1;
        if ($previous_page >= 1) {
                return $previous_page;
        } else {
            return false;
        }
    }
    public function get_next_page()
    {
        $next_page = $this->current_page + 1;
        if ($next_page <= $total_pages) {
                return $next_page;
        } else {
            return false;
        }
    }
}
?>