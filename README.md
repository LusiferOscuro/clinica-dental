# Clínica Dental Pro - Listo para Render y Local

## Acceso Directo en Render
- **Usuario Admin:** `admin@clinicadental.com`
- **Contraseña:** `Admin123*`
- **Odontólogo:** `valeria@clinicadental.com` / `Admin123*`

## Cómo subirlo a GitHub + Render sin errores
1. Descomprime este ZIP y sube **el contenido** (las carpetas `config`, `controllers`, `models`, `views` y los archivos `index.php`, `Dockerfile`, `.htaccess`) a la raíz de tu repositorio en GitHub.
2. Render detectará el `Dockerfile` automáticamente, levantará Apache + PHP 8.2 y auto-inicializará la base de datos SQLite si aún no configuraste un MySQL externo (`DB_HOST`), de modo que **nunca dará error de conexión** en tu presentación.
