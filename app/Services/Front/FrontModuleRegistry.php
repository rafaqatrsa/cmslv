<?php

namespace App\Services\Front;

use App\Models\Front\Banner;
use App\Models\Front\Event;
use App\Models\Front\Gallery;
use App\Models\Front\Media;
use App\Models\Front\Menu;
use App\Models\Front\Notice;
use App\Models\Front\Page;

class FrontModuleRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        return [
            'banners' => $this->module('Banners', Banner::class, 'front_cms_pages', 'admin.front.banners.index', 'banner', ['title', 'slug', 'url'], ['title', 'slug', 'page_type', 'publish', 'is_active']),
            'events' => $this->module('Events', Event::class, 'front_cms_programs', 'admin.front.events.index', 'events', ['title', 'slug', 'event_venue'], ['title', 'date', 'event_start', 'event_end', 'publish']),
            'galleries' => $this->module('Gallery', Gallery::class, 'front_cms_media_gallery', 'admin.front.galleries.index', 'gallery', ['img_name', 'vid_title', 'vid_url'], ['img_name', 'file_type', 'file_size', 'vid_title', 'created_at']),
            'media' => $this->module('Media', Media::class, 'front_cms_media_gallery', 'admin.front.media.index', 'media', ['img_name', 'vid_title', 'vid_url'], ['img_name', 'file_type', 'file_size', 'vid_title', 'created_at']),
            'menus' => $this->module('Menus', Menu::class, 'front_cms_menus', 'admin.front.menus.index', 'menus', ['menu', 'slug', 'description'], ['menu', 'slug', 'content_type', 'publish', 'is_active']),
            'notices' => $this->module('Notices', Notice::class, 'front_cms_programs', 'admin.front.notices.index', 'notice', ['title', 'slug', 'description'], ['title', 'date', 'publish_date', 'publish', 'is_active']),
            'pages' => $this->module('Pages', Page::class, 'front_cms_pages', 'admin.front.pages.index', 'page', ['title', 'slug', 'url', 'meta_title'], ['title', 'slug', 'page_type', 'publish_date', 'publish']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function get(string $key): array
    {
        return $this->all()[$key];
    }

    /**
     * @param  class-string  $model
     * @param  array<int, string>  $search
     * @param  array<int, string>  $columns
     * @return array<string, mixed>
     */
    private function module(string $label, string $model, string $table, string $route, string $permission, array $search, array $columns): array
    {
        return compact('label', 'model', 'table', 'route', 'permission', 'search', 'columns');
    }
}
