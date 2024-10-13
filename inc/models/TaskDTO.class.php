<?php

namespace Albatross;


class TaskDTO
{
    private string $title;
    private string $description;
    private int $projectID;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): TaskDTO
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): TaskDTO
    {
        $this->description = $description;
        return $this;
    }

    public function getProjectID(): int
    {
        return $this->projectID ?? 0;
    }

    public function setProjectID(int $projectID): TaskDTO
    {
        $this->projectID = $projectID;
        return $this;
    }
}
