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
    public function modelJoin(&$primary_model, $relationships, $operator = '=', $type = 'left', $where = false)
    {
        if (!is_array($relationships)) {
            $relationships = [$relationships];
        }

        if (empty($primary_model->query->columns)) {
            $primary_model->query->selectRaw('DISTINCT '.$primary_model->model->getTable().'.*');
        }

        foreach ($relationships as $relation_name => $load_relationship) {

            // Required variables.
            $model = array_get($primary_model->relationships, $relation_name.'.model');
            $method = array_get($primary_model->relationships, $relation_name.'.method');
            $table = array_get($primary_model->relationships, $relation_name.'.table');
            $parent_key = array_get($primary_model->relationships, $relation_name.'.parent_key');
            $foreign_key = array_get($primary_model->relationships, $relation_name.'.foreign_key');

            // Add the columns from the other table.
            // @todo do we need this?
            //$this->query->addSelect(new Expression("`$table`.*"));
            $primary_model->query->join($table, $parent_key, $operator, $foreign_key, $type, $where);

            // The join above is to the intimidatory table. This joins the query to the actual model.
            if ($method === 'BelongsToMany') {
                $related_foreign_key = $model->getQualifiedRelatedKeyName();
                $related_relation = $model->getRelated();
                $related_table = $related_relation->getTable();
                $related_qualified_key_name = $related_relation->getQualifiedKeyName();
                $primary_model->query->join($related_table, $related_qualified_key_name, $operator, $related_foreign_key, $type, $where);
            }
        }

        // Group by the original model.
        $primary_model->query->groupBy($primary_model->model->getQualifiedKeyName());
    }

}