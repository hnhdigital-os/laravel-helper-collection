<?php

namespace HnhDigital\HelperCollection;

class LaravelModel
{
    /**
     * Get the table keys based on the relation.
     *
     * @param Relation $relation
     *
     * @return array
     */
    private function getModelRelation($relation)
    {
        $method = basename(str_replace('\\', '/', get_class($relation)));

        switch ($method) {
            case 'BelongsTo':
            case 'HasMany':
            case 'HasOne':
                $model = $relation->getRelated();
                break;
            default:
                $model = $relation;
        }

        $table = $model->getTable();

        switch ($method) {
            case 'BelongsTo':
            case 'BelongsToMany':
                $parent_key = $relation->getQualifiedForeignKey();
                $foreign_key = $relation->getQualifiedOwnerKeyName();
            break;
            case 'HasMany':
                $parent_key = $relation->getQualifiedParentKeyName();
                $foreign_key = $relation->getQualifiedForeignKeyName();
            break;
            case 'HasOne':
                $parent_key = $table.'.'.$relation->getParentKey();
                $foreign_key = $table.'.'.$relation->getForeignKeyName();
            break;
        }

        return [
            'model'       => $model,
            'method'      => $method,
            'table'       => $table,
            'parent_key'  => $parent_key,
            'foreign_key' => $foreign_key,
        ];
    }

    /**
     * This determines the foreign key relations automatically to prevent the need to figure out the columns.
     *
     * @param string $relation_name
     * @param string $operator
     * @param string $type
     * @param bool   $where
     *
     * @return Builder
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function modelJoin(&$query, &$primary_model, &$relationships, $operator = '=', $type = 'left', $where = false)
    {
        if (is_string($relationships)) {
            $relationships = [$relationships => []];
        }

        if (!is_array($relationships)) {
            $relationships = [$relationships];
        }

        if (empty($query->columns)) {
            $query->selectRaw('DISTINCT '.$primary_model->getTable().'.*');
        }

        foreach ($relationships as $relation_name => &$relationship) {
            if (empty($relationship)) {
                $relationship = $this->getModelRelation($primary_model->$relation_name());
            }

            // Required variables.
            $model = array_get($relationship, 'model');
            $method = array_get($relationship, 'method');
            $table = array_get($relationship, 'table');
            $parent_key = array_get($relationship, 'parent_key');
            $foreign_key = array_get($relationship, 'foreign_key');

            // Add the columns from the other table.
            // @todo do we need this?
            //$this->query->addSelect(new Expression("`$table`.*"));
            $query->join($table, $parent_key, $operator, $foreign_key, $type, $where);

            // The join above is to the intimidatory table. This joins the query to the actual model.
            if ($method === 'BelongsToMany') {
                $related_foreign_key = $model->getQualifiedRelatedKeyName();
                $related_relation = $model->getRelated();
                $related_table = $related_relation->getTable();
                $related_qualified_key_name = $related_relation->getQualifiedKeyName();
                $query->join($related_table, $related_qualified_key_name, $operator, $related_foreign_key, $type, $where);
            }
        }

        // Group by the original model.
        $query->groupBy($primary_model->getQualifiedKeyName());
    }
}
