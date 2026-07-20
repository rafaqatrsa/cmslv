# CodeIgniter Authentication Route Migration Notes

## Staff Authentication

| CodeIgniter route | Laravel route | Controller method | Route name | Middleware |
| --- | --- | --- | --- | --- |
| `GET|POST /login` | `GET|POST /login` | `Staff\AuthController@login` | `staff.login` | `guest` |
| `GET|POST /staff/login` | `GET|POST /staff/login` | `Staff\AuthController@login` | `staff.login.legacy` | `guest` |
| `POST /staff/logout` | `POST /staff/logout` | `Staff\AuthController@logout` | `staff.logout` | `auth` |
| `GET|POST /staff/forgotpassword` | `GET|POST /staff/forgotpassword` | `Staff\AuthController@forgotPassword` | `staff.forgot_password` | `guest` |

## Site Authentication

| CodeIgniter route | Laravel route | Controller method | Route name | Middleware |
| --- | --- | --- | --- | --- |
| `GET|POST /signin` | `GET|POST /signin` | `Site\AuthController@signin` | `site.signin` | `guest` |
| `GET|POST /site/signin` | `GET|POST /site/signin` | `Site\AuthController@signin` | `site.signin.legacy` | `guest` |
| `POST /site/logout` | `POST /site/logout` | `Site\AuthController@logout` | `site.logout` | `auth` |

The converted routes are defined in `routes/web.php` using `Route::controller()`. Login-style routes keep both legacy and shorter URLs so old CodeIgniter links continue working during migration.

This app currently has a single Laravel `web` guard and `users` provider, so both converted controllers authenticate with `Auth::attempt()` against the existing users table. If the CodeIgniter application used separate staff/customer tables, add dedicated guards and providers before switching these controllers to those guards.
