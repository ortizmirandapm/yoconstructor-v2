# YoConstructor V2 — Agent Guide

## Roles & Auth
- Three user roles: `trabajador`, `empresa`, `admin` (stored in `users.tipo`).
- Middleware aliases registered in `bootstrap/app.php`: `es.trabajador`, `es.empresa`, `es.admin`.
- Auth routes provided by Laravel Breeze (`routes/auth.php`).

## Two Layout Systems
| Scope | Layout | CSS |
|---|---|---|
| Auth pages (login, register) | `layouts/guest.blade.php` | Vite (`@vite()`) |
| Public pages | `layouts.public.blade.php` | Tailwind CDN + Flowbite CDN |

If a new public page uses `npm run build`, it's wrong — use Tailwind CDN.
If a new auth page doesn't use Vite, it's wrong.

## Navbar
- `layouts.public-navbar` is shared by all public pages.
- Data injected via `NavbarComposer` (registered in `AppServiceProvider`).
- Available variables: `$navbarUser`, `$navbarNombreCompleto`, `$navbarTipoNombre`, `$navbarEmail`, `$navbarFotoPerfil`, `$navbarNotisCount`, `$navbarUltimasNotis`, `$navbarEsTrabajador`.
- Profile photo fallback: `public/img/profile.png` (checked via `file_exists()` in composer).
- Notification images stored in `uploads/perfil/{filename}`.

## Routes (alphabetical by prefix)
| Prefix | Middleware | Purpose |
|---|---|---|
| `/` (public) | none | Welcome, nosotros, ofertas public listing |
| `/trabajador` | `auth` + `es.trabajador` | Dashboard, perfil, postulaciones, notificaciones |
| `/empresa` | `auth` + `es.empresa` | Dashboard, ofertas CRUD, postulaciones received, perfil |
| `/admin` | `auth` + `es.admin` | Dashboard, usuarios, empresas, ofertas (state changes only) |
| `/api/localidades/{provincia}` | none | Dependent dropdown for provincia → localidad |
| `/profile` | `auth` | Breeze user profile |

All public routes defined in `routes/web.php`. See `php artisan route:list` for the full table.

## Models & Key Tables

| Model | Table | Fillable highlights |
|---|---|---|
| `User` | `users` | `tipo`, `estado`, `visible_busqueda` |
| `Empresa` | `empresas` | `user_id`, `nombre_empresa`, `cuit` |
| `Trabajador` | `trabajadores` | `user_id`, `dni`, `imagen_perfil`, `provincia_preferencia_id`, `localidad_preferencia_id` |
| `Oferta` | `ofertas` | `empresa_id`, `titulo`, `salario_min`, `salario_max`, `tipo_contrato`, `modalidad`, `estado` |
| `Especialidad` | `especialidades` | `nombre` (not `nombre_especialidad`), `estado` (bool) |
| `Postulacion` | `postulaciones` | `oferta_id`, `trabajador_id`, `estado` |
| `Provincia` | `provincias` | `nombre` |
| `Localidad` | `localidades` | `nombre`, `provincia_id` |
| `Rubro` | `rubros` | `nombre`, `estado` (bool) |

### Pivot Tables
- `oferta_especialidad` — `es_principal` (bool)
- `trabajador_especialidad` — `nivel_experiencia` (string), `es_principal` (bool)

### Oferta.estado enum
`Activa`, `Pausada`, `Cerrada`, `Borrador`

### Postulacion.estado enum
`Pendiente`, `Revisada`, `Entrevista`, `Aceptada`, `Rechazada`

## Dev Commands

```bash
# Start everything concurrently (server + queue + logs + Vite)
composer run dev

# Or individually:
php artisan serve
npm run dev          # Vite for auth pages
npm run build        # Production build (required after Vite changes)

# Compile Blade views (no build needed for CDN pages)
php artisan view:cache

# Run tests
composer run test    # Runs php artisan config:clear && php artisan test

# Queue listener (required for notifications)
php artisan queue:listen --tries=1 --timeout=0
```

Queue connection is `database` by default. Notifications use the `database` driver (`via()` returns `['database']`).

## Key Patterns

### Public listing (OfertaPublicaController)
- Filters: `buscar`, `especialidad`, `provincia`, `modalidad`, `contrato`.
- Uses `withQueryString()` on paginator.
- Shows `ya_postulado` badge for logged-in workers via conditional `withCount`.
- "Fuera de zona" info: compares `prefProvincia`/`prefLocalidad` (from `auth()->user()->trabajador`) against `$oferta->provincia_id`/`$oferta->localidad_id` at render time.
- `$tiposContrato` is a hardcoded array in the controller.

### OfertaObserver
Currently a no-op (empty `created()` method). When implementing notification matching, the `NuevaOfertaMatch` notification already exists and expects `via => ['database']`.

### Scripts (from composer.json)
- `composer run dev` runs php serve + queue + logs + Vite concurrently.
- `composer run test` clears config then runs `php artisan test`.
- `composer run setup` runs full install + migrate + build.

## Gotchas
- `auth()->user()->trabajador` or `auth()->user()->empresa` may return `null` if user is admin or the other type. Guard with `optional()` or `@if`.
- Welcome `/empresas/{id}` links use hardcoded `url('/empresas/' . $id)` — route/view for public empresa profile not yet built.
- Tables `reportes`, `auditoria`, `reclutadores` exist in migrations but have no models/controllers yet.
- No tests written yet (directories exist but empty).
