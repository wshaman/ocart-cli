<?php
class ModelExampleOperations extends Model {
    public function getCurrencies() {
        $query = $this->db->query("SELECT * FROM `currency`" );
        return $query->row;
    }
}