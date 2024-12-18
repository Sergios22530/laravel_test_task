<?php

namespace App\Services\Tasks;

use App\Services\BaseAttribute;

/**
 * @property ?string $title
 * @property ?string $description
 * @property ?string $status
 * @property ?string $priority
 * @property ?string $completed_at
 * @property ?int $user_id
 */
class TaskAttribute extends BaseAttribute
{

    public ?string $title = null;
    public ?string $description = null;
    public ?string $status = null;
    public ?string $priority = null;
    public ?string $completed_at = null;
    public ?int $user_id = null;

    public function assignAttributes(): self
    {
        if (!$this->request) return $this;

        if ($this->request->has('title')) $this->setTitle($this->request->validated('title'));
        if ($this->request->has('status')) $this->setStatus($this->request->validated('status'));
        if ($this->request->has('description')) $this->setDescription($this->request->validated('description'));
        if ($this->request->has('priority')) $this->setPriority($this->request->validated('priority'));
        if ($this->request->has('completed_at')) $this->setCompletedAt($this->request->validated('completed_at'));
        if ($this->request->has('user_id')) $this->setUserId($this->request->validated('user_id'));

        return $this;
    }

    public function getEloquentAttributes(): array
    {
        return [
            'title' => $this->getTitle(),
            'status' => $this->getStatus(),
            'description' => $this->getDescription(),
            'priority' => $this->getPriority(),
            'completed_at' => $this->getCompletedAt(),
            'user_id' => $this->getUserId(),
        ];
    }


    public function setTitle(?string $value = null): self
    {
        $this->title = $value;
        return $this;
    }

    public function setStatus(?string $value = null): self
    {
        $this->status = $value;
        return $this;
    }

    public function setDescription(?string $value = null): self
    {
        $this->description = $value;
        return $this;
    }

    public function setPriority(?string $value = null): self
    {
        $this->priority = $value;
        return $this;
    }

    public function setCompletedAt(?string $value = null): self
    {
        $this->completed_at = $value;
        return $this;
    }

    public function setUserId(?string $value = null): self
    {
        $this->user_id = $value;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function getCompletedAt(): ?string
    {
        return $this->completed_at;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

}
