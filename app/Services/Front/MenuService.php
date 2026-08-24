<?php

namespace App\Services\Front;

use App\Models\Front\MenuItem;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class MenuService
{
    /**
     * @param  Collection<int, MenuItem>  $items
     * @return Collection<int, MenuItem>
     */
    public function buildTree(Collection $items, ?int $parentId = null): Collection
    {
        return $items
            ->where('parent_id', $parentId)
            ->sortBy('weight')
            ->values()
            ->each(function (MenuItem $item) use ($items): void {
                $item->setRelation('children', $this->buildTree($items, $item->id));
            });
    }

    public function assertNotSelfParent(int $itemId, ?int $parentId): void
    {
        if ($parentId !== null && $itemId === $parentId) {
            throw ValidationException::withMessages([
                'parent_id' => 'A menu item cannot be its own parent.',
            ]);
        }
    }
}
