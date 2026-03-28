<?php

namespace App\Filament\Resources\AdSlotResource\Pages;

use App\Filament\Resources\AdSlotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditAdSlot extends EditRecord
{
    protected static string $resource = AdSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $path = $data['image_path'] ?? null;
        $url = isset($data['image_url']) ? trim((string) $data['image_url']) : '';
        $data['image_url'] = $url !== '' ? $url : null;

        if (filled($path)) {
            $data['image_url'] = null;
            if ($this->record->image_path && $this->record->image_path !== $path) {
                Storage::disk('public')->delete($this->record->image_path);
            }
        } elseif (filled($data['image_url'])) {
            if ($this->record->image_path) {
                Storage::disk('public')->delete($this->record->image_path);
            }
            $data['image_path'] = null;
        } elseif (($data['display_mode'] ?? '') === 'standard'
            && array_key_exists('image_path', $data)
            && ! filled($path)
            && ! filled($data['image_url'])
            && $this->record->image_path) {
            Storage::disk('public')->delete($this->record->image_path);
            $data['image_path'] = null;
        }

        return $data;
    }
}
