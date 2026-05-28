<?php

namespace App\Search;

class SearchResult
{
    public function __construct(
        public readonly string $key,
        public readonly string $scope,
        public readonly string $source,
        public readonly string $type,
        public readonly string $title,
        public readonly ?string $summary,
        public readonly ?string $url,
        public readonly ?string $route = null,
        public readonly ?string $icon = null,
        public readonly ?string $image = null,
        public readonly ?string $badge = null,
        public readonly ?string $group = null,
        public readonly ?string $groupLabel = null,
        public readonly ?string $groupIcon = null,
        public readonly int $groupOrder = 100,
        public readonly ?string $clickAction = null,
        public readonly array $actions = [],
        public readonly int $score = 0,
        public readonly array $metadata = [],
    ) {}

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'scope' => $this->scope,
            'source' => $this->source,
            'type' => $this->type,
            'title' => $this->title,
            'summary' => $this->summary,
            'url' => $this->url,
            'route' => $this->route,
            'icon' => $this->icon,
            'image' => $this->image,
            'badge' => $this->badge,
            'group' => $this->group,
            'group_label' => $this->groupLabel,
            'group_icon' => $this->groupIcon,
            'group_order' => $this->groupOrder,
            'click_action' => $this->clickAction,
            'actions' => $this->actions,
            'score' => $this->score,
            'metadata' => $this->metadata,
        ];
    }
}
