# Frontend CodeIgniter Migration Notes

## Route Mapping

| CodeIgniter route | CodeIgniter controller | Laravel route name | Laravel controller | Laravel view |
| --- | --- | --- | --- | --- |
| `/` | `welcome/index` | `frontend.home` | `Frontend\WelcomeController@index` | `frontend.index` |
| `/frontend` | `welcome/index` | `frontend.index` | `Frontend\WelcomeController@index` | `frontend.index` |
| `/page/{branch}/{slug}` | `welcome/page` | `frontend.page` | `Frontend\WelcomeController@page` | `frontend.page` |
| `/read/{branch}/{slug}` | `welcome/read` | `frontend.read` | `Frontend\WelcomeController@read` | `frontend.read` |
| `/branch/{id}` | `welcome/branch` | `frontend.branch` | `Frontend\WelcomeController@branch` | `frontend.branch` |
| `/franchises` | `welcome/franchises` | `frontend.franchises` | `Frontend\WelcomeController@franchises` | `frontend.franchises` |
| `/franchiseoffer` | `welcome/franchiseoffer` | `frontend.franchise-offer` | `Frontend\WelcomeController@franchiseOffer` | `frontend.franchise-offer` |
| `/register` | `welcome/register` | `frontend.register` | `Frontend\WelcomeController@register` | `frontend.register` |
| `/privacypolicy` | `welcome/privacypolicy` | `frontend.privacy-policy` | `Frontend\WelcomeController@privacyPolicy` | `frontend.privacy-policy` |
| `/contactus` | `welcome/contactus` | `frontend.contact-us` | `Frontend\WelcomeController@contactUs` | `frontend.contact-us` |

## Data Mapping

| Legacy table | Laravel model | Purpose |
| --- | --- | --- |
| `branch` | `App\Models\Branch`, `App\Models\Franchise` | Branch pages and franchise locations |
| `front_cms_pages` | `App\Models\Page` | CMS pages and SEO metadata |
| `front_cms_programs` | `App\Models\Post` | Readable frontend articles, news, events, or posts |
| `front_cms_media_gallery` | `App\Models\Gallery` | Frontend gallery media |
| `front_cms_settings` | `App\Models\FrontCmsSetting` | Frontend logo, social, footer, and CMS settings |
| `system_settings` | `App\Models\Setting` | School/site settings, contact details, branch settings |
| `enquiry` | `App\Models\Enquiry` | Registration and contact submissions |

The original CodeIgniter `Welcome` controller and model files were not present in this workspace, so exact CodeIgniter model-method mappings could not be verified. The Laravel implementation is based on the live database schema exposed by Laravel Boost and keeps all requested public URLs unchanged.
