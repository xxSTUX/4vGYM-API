# 4vGYM API

API para la gestión de fitness y actividades en 4vGYM.

## Índice de Contenidos
- [Instalación](#instalación)
- [Puntos de Acceso en la API](#puntos-de-acceso-en-la-api)
- [Uso](#uso)
- [Contribuir](#contribuir)
- [Licencia](#licencia)

## Instalación

Sigue estos pasos para configurar el proyecto localmente:

```bash
# Clona el repositorio
git clone https://github.com/xxSTUX/4vGYM-API.git

# Navega al directorio del proyecto
cd 4vGYM-API

# Instala las dependencias
composer install

# Configura la conexión a la base de datos
# Reemplaza db_user, db_password y db_name con tus credenciales de base de datos
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

# Crea la base de datos y el esquema
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Inicia el servidor de desarrollo de Symfony
symfony server:start
```
Asegúrate de reemplazar db_user, db_password y db_name con tus credenciales reales de base de datos.

## Puntos de Acceso en la API

### `/activity-types`
- GET: Obtén la lista de tipos de actividad. Cada tipo de actividad tiene un ID, nombre y el número de monitores requeridos para realizarla.

### `/monitors`
- GET: Obtén la lista de monitores con su ID, Nombre, Email, Teléfono y Foto.
- POST: Crea nuevos monitores y devuelve el JSON con información sobre el nuevo monitor.
- PUT: Edita monitores existentes.
- DELETE: Elimina monitores.

### `/activities`
- GET: Obtén la lista de actividades con detalles sobre tipos, monitores incluidos y fecha. Puedes buscar por fecha usando un parámetro con el formato dd-MM-yyyy.
- POST: Crea nuevas actividades y devuelve información sobre la nueva actividad. La validación asegura que la nueva actividad tenga los monitores requeridos basados en el tipo de actividad. La fecha y duración no son campos de libre formato; solo se permiten clases de 90 minutos que comiencen a las 09:00, 13:30 y 17:30.
- PUT: Edita actividades existentes.
- DELETE: Elimina actividades.

Todos los puntos de acceso de la API incluyen validación para solicitudes POST.

## Uso

Así es como puedes usar la API 4vGYM para gestionar actividades y monitores:

- Utiliza el punto de acceso `/activity-types` para obtener una lista de tipos de actividad disponibles.
- Utiliza el punto de acceso `/monitors` para ver, crear, editar o eliminar monitores.
- Utiliza el punto de acceso `/activities` para gestionar actividades. Puedes crear, editar, eliminar y ver actividades.

## Contribuir

¡Las contribuciones son bienvenidas! Si deseas contribuir al proyecto, por favor sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una nueva rama para tu característica o corrección de errores.
3. Realiza tus cambios y haz el commit de los mismos.
4. Empuja tu rama al repositorio bifurcado.
5. Crea una solicitud de extracción al repositorio principal.

Tu solicitud de extracción será revisada y, una vez aprobada, se fusionará con la rama principal.

## Licencia

Este proyecto está licenciado bajo la Licencia MIT. Consulta el archivo LICENSE para más detalles.