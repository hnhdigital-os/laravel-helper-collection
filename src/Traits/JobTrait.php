<?php

namespace HnhDigital\HelperCollection\Traits;

use Carbon\Carbon;

trait JobTrait
{
    /**
     * Scope the task in the job.
     *
     * @param Builer $query
     *
     * @return void
     */
    public function scopeTask($query, $task)
    {
        $query->whereRaw(sprintf(
            'payload->"$.data.command" LIKE \'%%%s%%\'',
            self::encodeQueryValue($task)
        ));
    }

    /**
     * Scope the model in the job.
     *
     * @param Builer $query
     *
     * @return void
     */
    public function scopeModel($query, $model, $key)
    {
        $query->whereRaw(sprintf(
            'payload->"$.data.command" LIKE \'%%id\\\\";s:36:\\\\"%s\\\\";%%\'',
            $key
        ));
    }

    /**
     * Scope a given key value.
     *
     * @param Builer $query
     * @param string $key
     * @param string $value
     * @param string $class_name
     * @param string $data_type
     *
     * @return void
     */
    public function scopeLookupKeyValue($query, $key, $value, $class_name = '', $data_type = 's')
    {
        // Lookup serialized array.
        if (is_array($value)) {
            $value = serialize($value);
            $value = self::encodeQueryValue(addslashes($value));

            $sql = sprintf(
                'payload->"$.data.command" LIKE \'%%%s\\\\\\\\";%s%%\'',
                $key,
                $value
            );

            $query->whereRaw($sql);

            return;
        }

        if ($value === false) {
            $data = sprintf(
                $data_type.':%s:\\\\\\\\"%s\\\\\\\\"',
                mb_strlen($class_name),
                self::encodeQueryValue($class_name)
            );

            $sql = sprintf(
                'payload->"$.data.command" LIKE \'%%%s\\\\\\\\";%s%%\'',
                $key,
                $data
            );
        } elseif ($value !== false) {
            if (stripos($value, '%') === false) {
                $value_length = mb_strlen($value);
            } else {
                $value_length = '%';
            }

            $data = sprintf(
                's:%s:\\\\\\\\"%s\\\\\\\\"',
                $value_length,
                $value
            );

            if (is_numeric($value)) {
                $data = sprintf('i:%s', $value);
            }

            $sql = sprintf(
                'payload->"$.data.command" LIKE \'%%%s\\\\\\\\";%s;%%\'',
                $key,
                $data
            );
        }

        $query->whereRaw($sql);
    }

    /**
     * Get the available at value as Carbon.
     *
     * @param string $value
     *
     * @return Carbon
     */
    public function getAvailableAtAttribute($value)
    {
        return Carbon::createFromTimestamp($value);
    }

    /**
     * Get the reserved at value as Carbon.
     *
     * @param string $value
     *
     * @return Carbon
     */
    public function getReservedAtAttribute($value)
    {
        return Carbon::createFromTimestamp($value);
    }

    /**
     * Get the created at value as Carbon.
     *
     * @param string $value
     *
     * @return Carbon
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp($value);
    }

    /**
     * Set available_at attribute.
     *
     * @param string $value
     * @param $this
     */
    public function setAvailableAtAttribute($value)
    {
        $this->attributes['available_at'] = empty($value) ? 0 : $this->asDateTime($value)->timestamp;

        return $this;
    }

    /**
     * Get the payload.
     *
     * @param string $value
     *
     * @return string
     */
    public function getPayloadAttribute($value)
    {
        $value = json_decode($value);

        data_set($value, 'data.command', unserialize(data_get($value, 'data.command')));

        return $value;
    }

    /**
     * Encode a value.
     *
     * @param string $value
     *
     * @return string
     */
    public static function encodeQueryValue($value)
    {
        return str_replace('\\', '\\\\\\\\', $value);
    }
}
