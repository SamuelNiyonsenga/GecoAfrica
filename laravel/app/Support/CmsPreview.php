<?php

namespace App\Support;

class CmsPreview
{
    private ?string $pageKey = null;
    private ?int $entryId = null;
    private ?string $section = null;

    public function forPage(string $pageKey): void
    {
        $this->pageKey = $pageKey;
    }

    public function forEntry(int $entryId, string $section): void
    {
        $this->entryId = $entryId;
        $this->section = $section;
    }

    public function active(): bool
    {
        return $this->pageKey !== null || $this->entryId !== null;
    }

    public function pageKey(): ?string
    {
        return $this->pageKey;
    }

    public function entryId(): ?int
    {
        return $this->entryId;
    }

    public function section(): ?string
    {
        return $this->section;
    }

    public function clear(): void
    {
        $this->pageKey = null;
        $this->entryId = null;
        $this->section = null;
    }
}
