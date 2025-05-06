# Weather Alert Service (v1.2)

A modern web application that allows you to receive up-to-date weather information for specified cities and subscribe to notifications about certain weather conditions. The system automatically sends notifications to registered users when their specified conditions are met.

## Version History

- **v1.2** - UI improvements: optimized spacing between sections, improved title positioning, reduced back-to-top button size
- **v1.1** - Added detailed weather forecasts, additional metrics, and expanded alert types
- **v1.0** - Initial release with basic weather data and alert functionality

## Features

- **Weather Data Retrieval**: Get current weather information for any city worldwide
- **Detailed Weather Information**: Access temperature, humidity, wind speed, pressure, visibility, and more
- **Hourly and 10-Day Forecasts**: Plan ahead with detailed hourly and extended forecasts
- **Weather Alert Subscriptions**: Subscribe to receive notifications when specific weather conditions are met
- **Automated Notifications**: Daily checks for weather conditions and email notifications to subscribers
- **Multiple Alert Types**: Support for temperature thresholds, precipitation types, wind conditions, and environmental alerts
- **Categorized Alerts**: Organized into Weather Conditions and Health & Environmental categories
- **User-Friendly Interface**: Clean, responsive design with Tailwind CSS
- **Data Caching**: Weather data is cached for 30 minutes to improve performance and reduce API calls
- **Fallback Mechanism**: Simulated weather data is provided when API calls fail or no API key is available
- **Email Testing**: Integrated with Mailhog for easy email notification testing

## User Interface

The Weather Alert Service features a modern, responsive user interface built with Tailwind CSS:

- **Home Page**: Overview of the service with features and available alert types
- **Weather Search**: Find weather information for any city worldwide
- **Weather Details**: Comprehensive weather data including current conditions, hourly forecast, and 10-day forecast
- **Alert Subscription**: Easy-to-use form for creating weather alert subscriptions
- **Subscription Management**: View and manage existing alert subscriptions
- **Mobile Responsive**: Optimized for all device sizes from mobile to desktop

## Architecture

The Weather Alert Service is built using Laravel, a PHP framework, and follows a service-oriented architecture. The main components are:

- **API Controllers**: Handle HTTP requests and responses
- **Web Controllers**: Manage the user interface and web routes
- **Services**: Contain business logic for weather data retrieval and condition checking
- **Models**: Represent database entities and relationships
- **Notifications**: Handle sending email alerts to subscribers
- **Scheduled Commands**: Run daily to check weather conditions and send notifications
- **Blade Templates**: Render the responsive user interface

## Technical Decisions

- **Laravel Framework**: Provides a robust foundation for building web applications, handling database operations, and scheduling tasks
- **Tailwind CSS**: Utility-first CSS framework for creating a modern, responsive user interface
- **OpenWeatherMap API**: Used for retrieving real-time weather data (can be configured or uses simulated data if no API key is provided)
- **Alpine.js**: Lightweight JavaScript framework for adding interactivity to the user interface
- **Remix Icon**: Modern icon library for consistent and attractive UI elements
- **Docker**: Containerization for easy deployment and environment consistency
- **MySQL**: Database for storing user subscriptions and notification logs
- **Mailhog**: Email testing tool for development environment
- **PHPUnit**: Testing framework for ensuring code quality and functionality

## Project Structure

```
├── app/
│   ├── Console/                # Scheduled tasks and Artisan commands
│   │   ├── Commands/           # Custom Artisan commands (e.g., CheckWeatherConditions)
│   │   └── Kernel.php          # Task scheduler configuration
│   ├── Http/
│   │   ├── Controllers/        # API and web controllers
│   │   │   ├── Api/            # API endpoints
│   │   │   └── Web/            # Web UI controllers
│   │   └── Requests/           # Form validation rules
│   ├── Models/                 # Eloquent models (User, City, WeatherSubscription)
│   ├── Notifications/          # Email notification templates
│   └── Services/               # Core business logic (WeatherService)
├── database/
│   ├── migrations/             # Database schema definitions
│   └── seeders/                # Sample data generators
├── resources/
│   ├── views/                  # Blade templates for web UI
│   │   ├── emails/             # Email templates
│   │   ├── subscriptions/      # Subscription management views
│   │   └── weather/            # Weather display views
├── routes/
│   ├── api.php                 # API route definitions
│   └── web.php                 # Web UI route definitions
├── tests/
│   ├── Feature/                # Feature/integration tests
│   │   └── Api/                # API endpoint tests
│   └── Unit/                   # Unit tests
│       └── Services/           # Service layer tests
├── docker/                     # Docker configuration files
├── docker-compose.yml          # Container orchestration
└── Dockerfile                  # Service container definition
```

## API Endpoints

### GET /api/weather

Returns the current weather information for a given city.

**Query Parameters:**
- `city` (required): The name of the city to get weather data for

**Example Response:**
```json
{
  "data": {
    "city": "London",
    "country": "GB",
    "temperature": 15.5,
    "humidity": 70,
    "wind_speed": 5.5,
    "weather": "Clouds",
    "description": "scattered clouds",
    "simulated": false
  }
}
```

### POST /api/subscriptions

Creates a new subscription for weather alerts.

**Request Body:**
```json
{
  "email": "user@example.com",
  "city": "London",
  "condition_type": "temperature_below",
  "condition_value": 10
}
```

**Condition Types:**
- `temperature_below`: Notify when temperature is below the specified value
- `temperature_above`: Notify when temperature is above the specified value
- `rain`: Notify when it's raining
- `snow`: Notify when it's snowing
- `wind_speed_above`: Notify when wind speed is above the specified value
- `thunderstorm`: Notify when there's a thunderstorm
- `tornado`: Notify when there's a tornado warning
- `poor_air_quality`: Notify when air quality is poor
- `high_pollution`: Notify when pollution levels are high
- `high_uv_index`: Notify when UV index is high
- `extreme_uv_index`: Notify when UV index is extreme
- `high_humidity`: Notify when humidity is high
- `low_humidity`: Notify when humidity is low
- `pressure_increase`: Notify when there's a rapid pressure increase
- `pressure_decrease`: Notify when there's a rapid pressure decrease

**Example Response:**
```json
{
  "data": {
    "id": 1,
    "email": "user@example.com",
    "city": "London",
    "condition_type": "temperature_below",
    "condition_value": 10,
    "created_at": "2025-04-22T00:42:20.000000Z"
  },
  "message": "Subscription created successfully"
}
```

## Setup Instructions

### Prerequisites

- Docker and Docker Compose
- PHP 8.4+ (for local development without Docker)
- Composer (for local development without Docker)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/vsevolodanhelis/WeatherAlertService.git
   cd WeatherAlertService
   ```

2. Copy the environment file and configure it:
   ```bash
   cp .env.example .env
   ```

   Update the following variables in the `.env` file:

   ## Environment Variables

   | Variable | Description |
   |----------|-------------|
   | `APP_ENV` | Application environment (`local`, `production`) |
   | `APP_DEBUG` | Enable debug mode (`true`, `false`) |
   | `APP_URL` | Base URL of the application |
   | `DB_CONNECTION` | Database driver (`mysql`, `sqlite`) |
   | `DB_HOST` | Database host (use `db` for Docker) |
   | `DB_PORT` | Database port (typically `3306` for MySQL) |
   | `DB_DATABASE` | Database name (e.g., `weather_alert`) |
   | `DB_USERNAME` | Database username |
   | `DB_PASSWORD` | Database password |
   | `OPENWEATHERMAP_API_KEY` | API key for weather data (optional) |
   | `MAIL_MAILER` | Mail driver (`smtp`, `mailhog`, etc.) |
   | `MAIL_HOST` | SMTP server host |
   | `MAIL_PORT` | SMTP server port |
   | `MAIL_USERNAME` | SMTP username |
   | `MAIL_PASSWORD` | SMTP password |
   | `MAIL_ENCRYPTION` | SMTP encryption (`tls`, `ssl`, `null`) |
   | `MAIL_FROM_ADDRESS` | Default sender email address |

3. Start the Docker containers:
   ```bash
   docker-compose up -d
   ```

4. Run the database migrations:
   ```bash
   docker-compose exec app php artisan migrate
   ```

5. Generate the application key:
   ```bash
   docker-compose exec app php artisan key:generate
   ```

### Running Tests

```bash
docker-compose exec app php artisan test
```

## Manual Testing Examples

### Get Weather Data

```bash
# Using cURL
curl "http://localhost:8000/api/weather?city=London"

# Using wget
wget -O - "http://localhost:8000/api/weather?city=London"
```

### Create Subscription

```bash
# Using cURL
curl -X POST http://localhost:8000/api/subscriptions \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "city": "London",
    "condition_type": "temperature_below",
    "condition_value": 5
}'

# Using Postman
# POST http://localhost:8000/api/subscriptions
# Headers: Content-Type: application/json
# Body (raw JSON):
# {
#   "email": "test@example.com",
#   "city": "London",
#   "condition_type": "temperature_below",
#   "condition_value": 5
# }
```

### Manually Trigger Weather Check

```bash
# Run the weather check command manually
docker-compose exec app php artisan app:check-weather-conditions
```

## Architecture Diagram

```
+----------------+     +-----------------+     +------------------+
|                |     |                 |     |                  |
|  User/Client   +---->+  API Endpoints  +---->+  Weather Service |
|                |     |                 |     |                  |
+----------------+     +-----------------+     +--------+---------+
                                                        |
                                                        v
+----------------+     +-----------------+     +------------------+
|                |     |                 |     |                  |
| Email Notifier |<----+  Condition     |<----+ OpenWeatherMap   |
|                |     |  Checker       |     | API / Simulator  |
+----------------+     +-----------------+     +------------------+
        ^                      ^
        |                      |
        |                      |
+-------+---------+   +--------+---------+   +------------------+
|                 |   |                  |   |                  |
| Notification    |   | Weather          |   | User &           |
| Logs            |   | Subscriptions    |   | City Data        |
|                 |   |                  |   |                  |
+-----------------+   +------------------+   +------------------+
        ^                      ^                     ^
        |                      |                     |
        |                      |                     |
        +----------------------+---------------------+
                               |
                     +---------+-----------+
                     |                     |
                     |      Database       |
                     |                     |
                     +---------------------+
```

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/new-alert-type`
3. Commit and push your changes
4. Submit a Pull Request

Please write tests for new functionality and ensure all tests pass before submitting PRs.

### Development Workflow

1. Set up the local development environment
2. Make changes to the codebase
3. Write tests for your changes
4. Run tests to ensure they pass
5. Submit a pull request

## Known Limitations

- Notifications are sent once daily per subscription (no real-time alerts)
- Only email notification channel is currently supported
- City name input is not fuzzy-matched or auto-completed
- Weather data may be simulated if no API key is provided
- Some advanced condition types (like air quality) may use simulated data even with API key
- The scheduler runs at 8 AM daily and cannot be configured through environment variables
- No user authentication for managing subscriptions (email-based access only)
- No support for location-based services or geolocation

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
