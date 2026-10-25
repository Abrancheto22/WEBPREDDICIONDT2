# Entrega de auditoría: Laravel

Fecha: 2026-10-24

Este documento resume el trabajo preparado para revisión del equipo. Los
cambios se entregan en una rama de auditoría; no se desplegó Laravel ni se
modificó Supabase.

## Trabajo realizado

- Actualización de dependencias Composer compatibles con PHP 8.4 y resolución
  de avisos reportados por `composer audit`.
- Preparación de `vercel.json`, runtime PHP, `pdo_pgsql` y rutas temporales de
  Laravel para Vercel.
- Configuración de un disco configurable para adjuntos y documentación para
  usar un bucket privado S3 de Supabase.
- Protección de la llamada Laravel → API Python con una clave compartida y
  validación de URL HTTPS.
- Reglas de rol y pruebas de autorización de predicciones.
- Guía de variables y despliegue en `DEPLOY_VERCEL.md`.
- `.env.example` usa `laravel_app` como ejemplo; no contiene credenciales.

## Verificaciones realizadas

- Tests Laravel: 7 pasaron, 28 assertions (ejecutados antes de esta entrega).
- `composer check-platform-reqs --no-dev`: correcto con PHP 8.4.
- `composer validate --no-check-publish`: correcto.
- `composer audit --locked`: sin avisos.
- `pnpm run build`: correcto.
- Conexión Supabase comprobada mediante consultas de solo lectura: 16 tablas
  públicas; las 15 migraciones del repositorio figuran como ejecutadas.
- No se leyeron filas clínicas ni se hicieron escrituras en Supabase.

## Hallazgo de seguridad pendiente

RLS está activada, pero no forzada, no se encontraron políticas en el esquema
`public` y el rol actual de la conexión tiene `BYPASSRLS`. Las políticas RLS,
por tanto, no están protegiendo las consultas de Laravel. No se cambiaron roles,
GRANTs ni políticas.

Antes de reemplazar la conexión hay que acordar el modelo de acceso: Laravel
usa un único rol SQL compartido por todas las sesiones web. Ese rol no identifica
por sí mismo al médico, enfermero o paciente conectado. La autorización por
usuario debe comprobarse en Laravel; si se desea aislamiento RLS por usuario,
hay que diseñar y probar cómo propagar la identidad verificada a PostgreSQL.

## Pendiente para completar

1. Definir una matriz de acceso (administrador, médico, enfermería, paciente)
   para pacientes, citas, triajes, predicciones, PDF y adjuntos.
2. Revisar y probar acceso por ID/propietario en todas las rutas de lectura,
   edición, eliminación, PDF y descarga de adjuntos. Las pruebas existentes no
   cubren todavía todos esos paths.
3. Diseñar un rol PostgreSQL de Laravel sin `BYPASSRLS`, con GRANTs mínimos y
   políticas compatibles con el modelo decidido. Probarlo en proyecto Supabase
   aislado antes de actualizar cualquier secreto de Production.
4. Configurar almacenamiento persistente para imágenes de perfil; hoy el disco
   `public` es local y no persiste en funciones Vercel.
5. Configurar una base y bucket de Preview con datos ficticios y verificar
   login, CRUD por rol, predicción, adjuntos y PDF en el runtime de Vercel.
6. Solo después completar revisión y habilitar Production.

## Configuración que el equipo deberá coordinar

- Definir una clave aleatoria `ML_API_KEY` de 32+ caracteres. Debe guardarse
  como secreto en Vercel para Python y Laravel; nunca en Git ni en `VITE_*`.
- Configurar `ML_API_URL` en Laravel apuntando al endpoint `/predict` de Python.
- Configurar DB, `APP_KEY`, cookies HTTPS y Storage como secretos/variables de
  Vercel al preparar Preview/Production.
- Revisar `DEPLOY_VERCEL.md` antes de iniciar cualquier despliegue.

No subir `.env` ni usar datos reales de pacientes para pruebas.
