# 4vGYM API

API for fitness and activity management in 4vGYM.

## Table of Contents
- [Installation](#installation)
- [API Endpoints](#api-endpoints)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Installation

Follow these steps to set up the project locally:

```bash
# Clone the repository
git clone https://github.com/xxSTUX/4vGYM-API.git

# Navigate to the project directory
cd 4vGYM-API

# Install dependencies
composer install

# Configure the database connection
# Replace db_user, db_password, and db_name with your database credentials
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

# Create the database and schema
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Start the Symfony development server
symfony server:start
```
Make sure to replace db_user, db_password, and db_name with your actual database credentials.

## API Endpoints

### `/activity-types`
- GET: Retrieve the list of activity types. Each type has an ID, name, and the number of monitors required for it.

### `/monitors`
- GET: Retrieve the list of monitors with their ID, Name, Email, Phone, and Photo.
- POST: Create new monitors and return the JSON with information about the new monitor.
- PUT: Edit existing monitors.
- DELETE: Delete monitors.

### `/activities`
- GET: Retrieve the list of activities with details on types, included monitors, and date. You can search by date using a parameter in the format dd-MM-yyyy.
- POST: Create new activities and return information about the new activity. Validation ensures that the new activity has the required monitors based on the activity type. The date and duration are not free-form fields; only 90-minute classes starting at 09:00, 13:30, and 17:30 are allowed.
- PUT: Edit existing activities.
- DELETE: Delete activities.

All API endpoints include validation for POST requests.

## Usage

Here's how you can use the 4vGYM API to manage activities and monitors:

- Use the `/activity-types` endpoint to get a list of available activity types.
- Use the `/monitors` endpoint to view, create, edit, or delete monitors.
- Use the `/activities` endpoint to manage activities. You can create, edit, delete, and view activities.

## Contributing

Contributions are welcome! If you'd like to contribute to the project, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Make your changes and commit them.
4. Push your branch to your forked repository.
5. Create a pull request to the main repository.

Your pull request will be reviewed, and once approved, it will be merged into the main branch.

## License

This project is licensed under the MIT License. See the LICENSE file for details.
