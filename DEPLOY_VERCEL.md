# Preparación de Laravel para Vercel y Supabase

Esta guía corresponde al sitio Laravel (`WEBPREDDICIONDT2`). La API Python
(`appml_tesis`) se configura por separado con su propia guía. No se ejecutó
ningún despliegue ni se modificó la base Supabase.

## Cambios preparados en el código

- Vercel usa el runtime comunitario `vercel-php@0.8.0` (PHP 8.4), coherente con
  las dependencias bloqueadas del proyecto. El runtime 0.9.0 apunta a PHP 8.5,
  pero una dependencia actual del lockfile no declara compatibilidad con 8.5.
- Composer requiere `pdo_pgsql`, que corresponde a PostgreSQL/Supabase, y el
  adaptador S3 para el disco durable de archivos.
- `api/index.php` dirige almacenamiento temporal y cachés de Laravel a `/tmp`,
  que es el espacio escribible disponible en una función serverless.
- Los adjuntos clínicos usan el disco configurado por `ATTACHMENTS_DISK`. En
  local continúa siendo `local`; en Vercel debe ser un bucket privado S3 de
  Supabase Storage.

## Configuración necesaria en Vercel

Selecciona `WEBPREDDICIONDT2` como Root Directory del proyecto. Configura estas
variables en Production (no las guardes en Git):

| Variable | Configuración |
| --- | --- |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | La clave Laravel existente, almacenada como secreto |
| `APP_URL` | Dominio HTTPS definitivo |
| `DB_CONNECTION` | `pgsql` |
| `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Datos de conexión PostgreSQL de Supabase; usar TLS y el pooler recomendado para el entorno serverless |
| `DB_SSLMODE` | `require` |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `FILESYSTEM_DISK` | `local` salvo que también se configure almacenamiento persistente para imágenes de perfil |
| `ATTACHMENTS_DISK` | `s3` |
| `AWS_ENDPOINT` | Endpoint S3 de Supabase: `https://<project-ref>.storage.supabase.co/storage/v1/s3` |
| `AWS_DEFAULT_REGION` | Región que muestra la configuración S3 de Supabase |
| `AWS_BUCKET` | Nombre de un bucket privado dedicado a adjuntos clínicos |
| `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY` | Par de claves S3 de Supabase, solo como secretos del servidor |
| `AWS_USE_PATH_STYLE_ENDPOINT` | `true` |
| `ML_API_URL` | URL HTTPS de `/predict` del proyecto Python en Vercel |
| `ML_API_KEY` | La misma clave secreta configurada en la API Python |
| `ML_AGE_MIN`, `ML_AGE_MAX` | `21` y `81` para el artefacto actual |

Las claves S3 estándar de Supabase tienen acceso amplio al Storage del proyecto
y omiten las políticas RLS de objetos. Manténlas únicamente en el backend,
limita quién puede editar variables de Vercel y, para aislar estos documentos,
usa un proyecto Supabase dedicado: crear solo otro bucket no limita el alcance
de esas claves. El bucket debe permanecer privado; el controlador Laravel
autoriza el acceso antes de descargar los archivos. [Supabase documenta el
alcance y el uso exclusivamente servidor de esas claves](https://supabase.com/docs/guides/storage/s3/authentication).

La conexión de Laravel a Supabase se comprobó en modo lectura. Las 16 tablas
públicas esperadas existen y `php artisan migrate:status` informó las 15
migraciones del repositorio como ejecutadas. No se insertaron, actualizaron ni
eliminaron datos. Esto confirma el estado registrado, pero no demuestra por sí
solo que cada restricción y relación real sea idéntica a la definición actual
del repositorio; no ejecutes migraciones en producción como parte del primer
despliegue.

La inspección de metadatos encontró RLS activada en las 16 tablas públicas,
pero no forzada y sin políticas definidas en `public`. Además, el rol que usa
la conexión tiene `BYPASSRLS`. Por eso Laravel opera con acceso amplio. Un rol
sin `BYPASSRLS` quedaría bloqueado hasta otorgarle privilegios SQL y crear
políticas RLS explícitas para las operaciones de la aplicación.

Laravel conecta todos los usuarios web con un único rol PostgreSQL. Una
política de base de datos que solo reconoce ese rol no distingue entre médico,
enfermería y paciente. Las restricciones por usuario/propietario deben seguir
validándose en Laravel, y para que RLS aisle usuarios de extremo a extremo se
necesitaría propagar una identidad verificada a PostgreSQL por petición. No
habilites políticas genéricas pensando que sustituyen esa autorización.
El diagnóstico no mostró filas clínicas ni modificó roles o políticas.

## Pendientes antes de habilitar datos reales

1. Configurar y probar el bucket privado de adjuntos y sus credenciales de
   servidor. Los adjuntos locales guardados previamente no migran solos.
2. Resolver el almacenamiento de imágenes de perfil: el disco `public` actual
   es local y no persiste entre invocaciones de Vercel. No lo uses para guardar
   documentos clínicos.
3. Diseñar y probar un rol PostgreSQL de aplicación sin `BYPASSRLS`, con
   `GRANT` mínimos y políticas RLS explícitas para las tablas que utiliza
   Laravel. Antes, decidir si se implementará aislamiento por usuario dentro
   de PostgreSQL o si Laravel seguirá siendo el punto de autorización por
   usuario. Revisar índices, restricciones y columnas en un proyecto de
   pruebas antes de habilitar datos reales.
4. Probar inicio/cierre de sesión, CRUD por rol, predicción Laravel → Python,
   descarga autorizada de archivos y generación de PDF dentro de Vercel Preview.
   La base de Preview debe ser separada o contener solo datos ficticios.
5. Revisar `composer audit`, configuración de cookies HTTPS, límites de
   ejecución/tamaño y registros antes de Production.

La API Python ya está conectada al flujo Git → Vercel: al hacer `git push`,
Vercel construye y publica ese proyecto automáticamente. Antes del siguiente
push que active la autenticación, guarda `ML_API_KEY` como secreto en el
proyecto Vercel de Python. Luego agrega exactamente la misma clave al proyecto
Laravel y configura `ML_API_URL` con la URL actual de `/predict`. No incluyas
la clave en el repositorio ni en el frontend. Para comprobar el endpoint Python,
usa los pasos de `appml_tesis/DEPLOY_VERCEL.md`.
