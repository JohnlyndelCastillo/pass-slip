# Pass Slip System

A pass slip management system built with vanilla HTML, CSS, JavaScript, PHP, and MySQL.

## Prerequisites

Before you begin, ensure you have the following installed:

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [Git](https://git-scm.com/downloads)
- [Notion](https://www.notion.so/2f9d5a371a7280539be5ea2c98185f50?v=2f9d5a371a728004b43a000c4dadb86c)

## Getting Started

### 1. Clone the Repository

```bash
git clone <your-repository-url>
cd pass-slip
```

### 2. Configure Environment Variables

Copy the example environment file:

```bash
cp .env.example .env
```

### 3. Start the Application

```bash
docker compose up -d
```

This command will:

- Build the PHP Apache container
- Pull the MySQL 8.0 image
- Create and initialize the database with the schema from `database/init.sql`
- Start both containers in the background

### 4. Access the Application

Once the containers are running, you can access:

- **Web Application**: http://localhost:8080
- **Database**: localhost:3306

### 5. Verify Everything is Running

```bash
docker ps
```

You should see two containers running:

- `pass-slip-web` (PHP Apache)
- `pass-slip-mysql` (MySQL)

## Database Configuration

The application connects to MySQL using these credentials (defined in `includes/db.php`):

- **Host**: `pass-slip-mysql`
- **Port**: `3306`
- **Database**: `pass_slip_system`
- **Username**: `root`
- **Password**: `group2-123`

### Connecting with MySQL Workbench (Optional)

If you want to view/edit the database using MySQL Workbench:

1. Open MySQL Workbench
2. Create a new connection with these settings:
   - **Connection Name**: Pass Slip Docker
   - **Hostname**: `127.0.0.1` or `localhost`
   - **Port**: `3306`
   - **Username**: `root`
   - **Password**: `group2-123`
3. Test the connection and connect

## Project Structure

```
pass-slip/
├── api/              # API endpoints
├── auth/             # Authentication logic
├── dashboard/        # Dashboard pages
├── database/         # Database initialization scripts
│   └── init.sql      # Initial database schema
├── includes/         # Shared PHP files
│   └── db.php        # Database connection
├── public/           # Static assets
│   ├── css/          # Stylesheets
│   └── js/           # JavaScript files
├── docker-compose.yml
├── Dockerfile
├── index.php         # Entry point
└── README.md
```

## Useful Docker Commands

### Stop the Application

```bash
docker compose down
```

### View Container Logs

```bash
# View web server logs
docker logs pass-slip-web

# View database logs
docker logs pass-slip-mysql
```

### Restart the Application

```bash
docker compose restart
```

### Access MySQL CLI

```bash
docker exec -it pass-slip-mysql mysql -uroot -pgroup2-123 pass_slip_system
```

### Rebuild Containers (after Dockerfile changes)

```bash
docker compose down
docker compose up -d --build
```

### Fresh Database (Warning: This deletes all data!)

```bash
docker compose down -v  # -v removes volumes
docker compose up -d
```

## Development Workflow

### Making Database Changes

When you add new tables or modify the schema:

1. Make changes directly in the running database OR update `database/init.sql`
2. Export the current schema:
   ```bash
   docker exec pass-slip-mysql mysqldump -u root -pgroup2-123 pass_slip_system > database/init.sql
   ```
3. Commit the updated `init.sql`:
   ```bash
   git add database/init.sql
   git commit -m "Update database schema"
   git push
   ```

### Pulling Latest Changes

When a teammate updates the database schema:

1. Pull the latest changes:
   ```bash
   git pull
   ```
2. Recreate the database with the new schema:
   ```bash
   docker compose down -v
   docker compose up -d
   ```

## Troubleshooting

### Port 3306 Already in Use

If you get an error about port 3306 being in use:

**Windows:**

```bash
net stop MySQL80
```

**Mac:**

```bash
brew services stop mysql
```

**Linux:**

```bash
sudo systemctl stop mysql
```

Or change the port in `docker-compose.yml` to `3307:3306`

### Container Name Conflict

If you see "container name already in use":

```bash
docker rm -f pass-slip-mysql pass-slip-web
docker compose up -d
```

### Containers Not Starting

Check the logs:

```bash
docker logs pass-slip-mysql
docker logs pass-slip-web
```

### Database Connection Issues

1. Verify containers are running:
   ```bash
   docker ps
   ```
2. Check database connectivity:
   ```bash
   docker exec -it pass-slip-mysql mysql -uroot -pgroup2-123 pass_slip_system
   ```

## Contributing

1. Create a new branch for your feature
2. Make your changes
3. Test locally with Docker
4. Commit and push your changes
5. Create a pull request

## Tech Stack

- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Backend**: PHP 8.2
- **Database**: MySQL 8.0
- **Server**: Apache (via PHP Docker image)
- **Containerization**: Docker & Docker Compose
