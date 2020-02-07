<?php

namespace HnhDigital\HelperCollection\Traits;

/**
 *
 * use HnhDigital\HelperCollection\Traits\HasCompositePrimaryKey;
 * use Illuminate\Database\Eloquent\Concerns\HasAttributes as EloquentHasAttributes;
 *
 * class ... extends ...
 * {
 *     use EloquentHasAttributes, HasCompositePrimaryKey {
 *         EloquentHasAttributes::getAttribute as eloquentGetAttribute;
 *         Concerns\HasAttributes::getAttribute insteadof EloquentHasAttributes;
 *     };
 * }
 */

use Illuminate\Database\Eloquent\Builder;

trait HasCompositePrimaryKey
{
    /**
     * Set the keys for a save update query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $keys = $this->getKeyName();

        if (! is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @param mixed $keyName
     *
     * @return mixed
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

    /**
     * Get attribute override when primaryKey is composite.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($keys)
    {
        if (! is_array($keys)) {
            return $this->eloquentGetAttribute($keys);
        }

        $value = [];

        foreach ($keys as $key) {
            $value[] = $this->eloquentGetAttribute($key);
        }

        return implode(';', $value);
    }
}
