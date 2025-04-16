<?php

namespace App\DTO;

class PasteDTO
{
    public string $pasteCode;
    public string $pasteName;
    public string $pasteFormat;
    public int $pastePrivate;
    public string $expireDate;
    public ?string $userKey = null;
    /**
     * Create a new class instance.
     */
    public function __construct(string $pasteCode, string $pasteName, string $pasteFormat, int $pastePrivate, string $expireDate, string $userKey = null)
    {
        $this->pasteCode = $pasteCode;
        $this->pasteName = $pasteName;
        $this->pasteFormat = $pasteFormat;
        $this->pastePrivate = $pastePrivate;
        $this->expireDate = $expireDate;
        $this->userKey = $userKey;
    }
}
