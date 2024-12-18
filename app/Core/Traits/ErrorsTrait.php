<?php

namespace App\Core\Traits;

use Illuminate\Support\Collection;

/**
 * Trait ErrorsTrait
 * @package App\Core\Traits
 *
 * @property ?array $errors
 */
trait ErrorsTrait
{

    /**
     * Errors
     *
     * @var array|null
     */
    protected ?array $errors = null;

    /**
     * Has Errors
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get Errors
     *
     * @param bool $collect
     * @return array|Collection|null
     */
    public function getErrors(bool $collect = false): array|Collection|null
    {
        return $collect ? $this->getCollectionErrors() : $this->errors;
    }

    public function getCollectionErrors(): ?Collection
    {
        return collect($this->errors);

    }

    /**
     * Set Error
     *
     * @param string|null $error
     * @return $this
     */
    public function setError(?string $error = null): self
    {
        $this->errors[] = $error;

        return $this;
    }

    public function addErrors(?array $errors = []): self
    {
        $this->errors = array_merge($this->errors ?? [], $errors ?? []);

        return $this;
    }
}
