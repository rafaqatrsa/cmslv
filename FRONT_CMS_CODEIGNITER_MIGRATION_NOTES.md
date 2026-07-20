# Front CMS CodeIgniter Migration Notes

The Laravel workspace does not contain the original CodeIgniter Front CMS controllers, models, views, helpers, libraries, JavaScript files, AJAX endpoints, upload handlers, or image-processing configuration. A workspace search for `/admin/front`, `front_cms`, banner/menu/gallery controller names, and CodeIgniter branch helpers found only existing Laravel frontend/admin migration files.

This implementation preserves the known `/admin/front/...` legacy URLs and creates a Laravel 12, table-backed conversion foundation that remains compatible with the existing `Frontend\WelcomeController` tables. It does not invent missing create/edit/delete/status/sort/upload endpoints or frontend JSON contracts that require the missing CodeIgniter source.

## Migration Map

| Legacy URL | CodeIgniter controller | CodeIgniter method | CodeIgniter model method | Database tables | Laravel controller | Laravel method | Laravel route name | Laravel model or service | Admin Blade view | Frontend dependency | Permission key | AJAX dependency | File dependency |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `/admin/front/banner` | Missing source | `index` | Missing source | `front_cms_pages`; no banner table found | `BannerController` | `index` | `admin.front.banners.index` | `Banner`, `FrontIndexService` | `admin.front.modules.index` | Home page/banner behavior unknown | `banner` | Missing status/order/upload endpoints | Page `feature_image`, `photo` paths |
| `/admin/front/events` | Missing source | `index` | Missing source | `front_cms_programs`, `front_cms_program_photos`, `front_cms_media_gallery` | `EventController` | `index` | `admin.front.events.index` | `Event`, `ProgramPhoto` | `admin.front.modules.index` | `WelcomeController::read()` uses `Post` on `front_cms_programs` | `events` | Missing filters/status/sort endpoints | Program `feature_image`, `image` paths |
| `/admin/front/gallery` | Missing source | `index` | Missing source | `front_cms_media_gallery`, `front_cms_program_photos` | `GalleryController` | `index` | `admin.front.galleries.index` | `Gallery`, `ProgramPhoto` | `admin.front.modules.index` | Home/branch gallery uses `Gallery` | `gallery` | Missing multi-upload/delete endpoints | Gallery image/thumb paths |
| `/admin/front/media` | Missing source | `index` | Missing source | `front_cms_media_gallery` | `MediaController` | `index` | `admin.front.media.index` | `Media`, `FrontFileService` | `admin.front.modules.index` | Gallery/media library usage | `media` | Missing media picker/upload endpoints | Gallery media paths and videos |
| `/admin/front/menus` | Missing source | `index` | Missing source | `front_cms_menus`, `front_cms_menu_items`, `front_cms_pages` | `MenuController` | `index` | `admin.front.menus.index` | `Menu`, `MenuItem`, `MenuService` | `admin.front.modules.index` | Frontend menu rendering helper missing | `menus` | Missing nested reorder endpoint/payload | None direct |
| `/admin/front/notice` | Missing source | `index` | Missing source | `front_cms_programs`; no notice table found | `NoticeController` | `index` | `admin.front.notices.index` | `Notice` | `admin.front.modules.index` | Notice board/ticker behavior unknown | `notice` | Missing status/filter endpoints | Program attachment/image paths |
| `/admin/front/page` | Missing source | `index` | Missing source | `front_cms_pages`, `front_cms_page_contents` | `PageController` | `index` | `admin.front.pages.index` | `Page` | `admin.front.modules.index` | `/page/{branch}/{slug}` uses `Page` and keeps slugs | `page` | Missing rich-text/create/edit endpoints | Page feature/photo/sign paths |

## Implemented Laravel Files

- Routes: `routes/web.php`
- Controllers: `app/Http/Controllers/Admin/Front/*Controller.php`
- Models: `app/Models/Front/*.php`
- Services: `app/Services/Front/FrontIndexService.php`, `FrontModuleRegistry.php`, `FrontFileService.php`, `MenuService.php`
- Views: `resources/views/admin/front/partials/nav.blade.php`, `resources/views/admin/front/modules/index.blade.php`
- Tests: `tests/Feature/FrontCmsLegacyRoutesTest.php`, `tests/Unit/FrontMenuServiceTest.php`

## Unresolved Dependencies

- Original CodeIgniter Front CMS controllers and method list
- Create/edit/delete/status/sort/upload legacy URLs beyond the top-level URLs
- Banner and notice table semantics; no dedicated tables were found
- Frontend menu-rendering helper and menu locations
- AJAX/DataTables response keys and payload shapes
- Upload directories, thumbnail dimensions, cropping/compression rules, and old filename expectations
- Page slug generation and uniqueness rules for new records
- Publishing state meanings beyond existing `publish` and `is_active` columns
- Rich-text editor configuration and sanitization model
- Cache behavior and invalidation keys, if any existed in CodeIgniter

## Artisan Commands Used Or Relevant

```bash
php artisan route:list --path=admin/front
vendor/bin/pint --dirty --format agent
php artisan test --compact tests/Feature/FrontCmsLegacyRoutesTest.php tests/Unit/FrontMenuServiceTest.php
```
