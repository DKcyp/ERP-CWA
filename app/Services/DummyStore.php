<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DummyStore
{
    protected string $file;

    public function __construct(string $name)
    {
        $this->file = "dummy/{$name}.json";
        $this->init();
    }

    protected function init(): void
    {
        if (!Storage::exists($this->file)) {
            Storage::put($this->file, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    protected function read(): array
    {
        return json_decode(Storage::get($this->file), true) ?? [];
    }

    protected function write(array $data): void
    {
        Storage::put($this->file, json_encode(array_values($data), JSON_PRETTY_PRINT));
    }

    public function all(): array
    {
        return $this->read();
    }

    public function find(string $id): ?array
    {
        $data = $this->read();
        foreach ($data as $item) {
            if ($item['id'] === $id) return $item;
        }
        return null;
    }

    public function create(array $payload): array
    {
        $data = $this->read();
        $payload['id'] = (string) Str::ulid();
        $data[] = $payload;
        $this->write($data);
        return $payload;
    }

    public function update(string $id, array $payload): ?array
    {
        $data = $this->read();
        foreach ($data as &$item) {
            if ($item['id'] === $id) {
                $item = array_merge($item, $payload);
                $this->write($data);
                return $item;
            }
        }
        return null;
    }

    public function delete(string $id): bool
    {
        $data = $this->read();
        $filtered = array_values(array_filter($data, fn($i) => $i['id'] !== $id));
        if (count($filtered) === count($data)) return false;
        $this->write($filtered);
        return true;
    }
}
