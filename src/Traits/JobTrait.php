<?php

namespace HnhDigital\HelperCollection\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait JobTrait
{
    /**
     * Scope the task in the job.
     *
     * @param Builer $query
     * @param string $task
     * @return void
     */
    public function scopeTask(Builder $query, string $task): void
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
    public function scopeModel(Builder $query, string $model, string $key): void
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
     * @param mixed $value
     * @param string $class_name
     * @param string $data_type
     * @return void
     */
    public function scopeLookupKeyValue(
        Builder $query,
        string $key,
        $value,
        string $class_name = '',
        string $data_type = 's'
    ): void
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
     * @return Carbon
     */
    public function getAvailableAtAttribute(string $value): Carbon
    {
        return Carbon::createFromTimestamp($value);
    }

    /**
     * Get the reserved at value as Carbon.
     *
     * @param string $value
     * @return Carbon
     */
    public function getReservedAtAttribute(string $value): Carbon
    {
        return Carbon::createFromTimestamp($value);
    }

    /**
     * Get the created at value as Carbon.
     *
     * @param string $value
     * @return Carbon
     */
    public function getCreatedAtAttribute(string $value): Carbon
    {
        return Carbon::createFromTimestamp($value);
    }

    /**
     * Set available_at attribute.
     *
     * @param string $value
     * @return self
     */
    public function setAvailableAtAttribute(string $value): self
    {
        $this->attributes['available_at'] = empty($value) ? 0 : $this->asDateTime($value)->timestamp;

        return $this;
    }

    /**
     * Get the payload.
     *
     * @param string $value
     * @return string
     */
    public function getPayloadAttribute(string $value): string
    {
        $value = json_decode($value);

        data_set($value, 'data.command', unserialize(data_get($value, 'data.command')));

        return $value;
    }

    /**
     * Encode a value.
     *
     * @param string $value
     * @return string
     */
    public static function encodeQueryValue(string $value): string
    {
        return str_replace('\\', '\\\\\\\\', $value);
    }
}
