<?php

namespace HnhDigital\HelperCollection\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * To use this trait, you must install it using:-
 *
 * ```
 * use App\Models\Traits\SummaryKeyTrait;
 * use HnhDigital\HelperCollection\Traits\HasCompositePrimaryKey;
 * use Illuminate\Database\Eloquent\Concerns\HasAttributes;
 *
 * class Classname
 * {
 *     use HasAttributes, HasCompositePrimaryKey {
 *         HasAttributes::getAttribute as eloquentGetAttribute;
 *         HasCompositePrimaryKey::getAttribute insteadof HasAttributes;
 *     }
 *
 *     use SummaryKeyTrait;
 *
 *     protected static $mutatorCache = [];
 * }
 *
 * ```
 *
 * getAttribute is overridden as this method can be called and an exception thrown as $key is now array
 * when been called with the $primaryKey.
 *
 */
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
     * Get an attribute from the model.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (is_array($key)) {
            $attribute = [];

            foreach ($key as $key_name) {
                $attribute[] = $this->eloquentGetAttribute($key_name);
            }

            return implode('-', $attribute);
        }

        return $this->eloquentGetAttribute($key);
    }
}
