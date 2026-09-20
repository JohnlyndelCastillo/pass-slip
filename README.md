# Pass Slip System

[CI Tests](https://github.com/JohnlyndelCastillo/pass-slip/actions/workflows/ci-tests.yml)

A web-based pass slip management system. Students submit pass slip requests, and role-based approvers review them through a multi-stage approval process. Built with vanilla HTML, CSS, JavaScript, PHP, and MySQL, and run entirely in Docker.

## Table of Contents

- [Features](#features)
- [User Roles](#user-roles)
- [Tech Stack](#tech-stack)
- [Getting Started](#getting-started)
- [Configuration](#configuration)
- [Project Structure](#project-structure)
- [Development Workflow](#development-workflow)
- [Testing and CI](#testing-and-ci)
- [Security Notes](#security-notes)
- [Useful Docker Commands](#useful-docker-commands)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [Project Resources](#project-resources)

## Features

- Role-based access with a dedicated dashboard for each user role
- Pass slip requests: create, approve, reject, and delete
- Multi-stage approval workflow, with each slip's status tracked as it moves between approvers
- In-app notifications with an unread counter
- Admin tools for creating, editing, and deleting user accounts
- One-command Docker setup and automated CI checks

## User Roles

| Role | Dashboard folder | What they do |
| --- | --- | --- |
| Student | `dashboard/student` | Submits pass slip requests |
| Class Adviser | `dashboard/adviser` | Reviews and approves or rejects slips |
| Instructor | `dashboard/instructor` | Reviews and approves or rejects slips |
| CSD Council | `dashboard/csd_council` | Reviews and approves or rejects slips |
| Technology Head | `dashboard/technology_head` | Reviews and approves or rejects slips |
| Admin | `dashboard/admin` | Manages user accounts |

## Tech Stack

- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Backend:** PHP 8.2
- **Database:** MySQL 8.0
- **Server:** Apache (via the official PHP Docker image)
- **Containerization:** Docker and Docker Compose
- **CI:** GitHub Actions

## Getting Started

### Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [Git](https://git-scm.com/downloads)

### 1. Clone the repository

```bash
git clone <your-repository-url>
cd pass-slip
```

### 2. Configure environment variables

Copy the example file, then open `.env` and set your own values:

```bash
cp .env.example .env
```

| Variable | Description |
| --- | --- |
| `MYSQL_ROOT_PASSWORD` | Password for the MySQL root user. Choose a strong value. |
| `MYSQL_DATABASE` | Database name. Use `pass_slip_system`, which is what the app and CI expect. |

> **Never commit `.env`.** It is listed in `.gitignore`. Share credentials with teammates privately, not through the repository.

### 3. Start the application

```bash
docker compose up -d --build --wait
```

This command will:

- Build the PHP Apache container
- Pull the MySQL 8.0 image
- Create the database from `database/init.sql` (first run only)
- Wait until the database reports healthy, then start the web container

### 4. Open the application

| Service | Address |
| --- | --- |
| Web application | http://localhost:8080 |
| MySQL (from your machine only) | `127.0.0.1:3306` |

### 5. Verify everything is running

```bash
docker compose ps
```

You should see two containers, both running:

- `pass-slip-web` (PHP Apache)
- `pass-slip-mysql` (MySQL, status `healthy`)

## Configuration

### Database connection

The application connects with these settings (see `includes/db.php`). Keep them in sync with your `.env`.

| Setting | Value |
| --- | --- |
| Host | `pass-slip-mysql` (container name on the Docker network) |
| Port | `3306` |
| Database | `pass_slip_system` |
| Username | `root` |
| Password | The value of `MYSQL_ROOT_PASSWORD` in your `.env` |

### Connecting with MySQL Workbench (optional)

1. Open MySQL Workbench and create a new connection
2. Set **Hostname** to `127.0.0.1`, **Port** to `3306`, and **Username** to `root`
3. Enter the `MYSQL_ROOT_PASSWORD` from your `.env` when prompted
4. Test the connection and connect

The database port is bound to `127.0.0.1`, so it is only reachable from your own machine.

## Project Structure

```
pass-slip/
├── .github/workflows/   # CI pipeline (ci-tests.yml)
├── api/                 # API data and endpoints
├── auth/                # Login, logout, register, and slip/user actions
│   ├── admin/           # User management actions
│   └── notifications/   # Notification actions
├── components/          # Reusable PHP components (modals, dropdowns, tables)
│   ├── admin/
│   └── approval/        # Shared approval page used by every approver role
├── dashboard/           # Pages for each role, plus login and register
│   ├── admin/
│   ├── adviser/
│   ├── csd_council/
│   ├── instructor/
│   ├── student/
│   └── technology_head/
├── database/
│   └── init.sql         # Database schema, loaded on first start
├── docker/
│   └── apache-hardening.conf   # Blocks access to sensitive files
├── includes/            # Shared PHP (db.php, notifications.php)
├── middleware/
│   └── auth_guard.php   # Session and role checks
├── public/              # Static assets
│   ├── css/
│   └── js/
├── .env.example         # Template for environment variables
├── docker-compose.yml
├── Dockerfile
├── index.php            # Entry point
└── README.md
```

Each approver dashboard is a thin wrapper: it sets the role and the required slip status, runs its query, and includes the shared page from `components/approval/approval_page.php`.

## Development Workflow

### Branching

- `main` holds stable code and `develop` is the integration branch
- Create a feature branch from `develop`, then open a pull request back into it
- CI must pass before a pull request is merged

### Commit messages

Use short, descriptive messages in the conventional style, for example:

```
feat: add reject reason to slip details modal
fix: correct approver query for technology head
style(profile): set dropdown width and increase text sizes
ci: add smoke tests
```

### Making database changes

When you add tables or modify the schema:

1. Change the running database, or edit `database/init.sql` directly
2. Export the schema (structure only, so real user data never lands in git):
   ```bash
   docker exec pass-slip-mysql sh -c 'mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" --no-data pass_slip_system' > database/init.sql
   ```
   Run this in Git Bash, macOS, or Linux. Older Windows PowerShell saves the file as UTF-16, which MySQL cannot import. If the app needs seed rows (for example a default account), add them to `init.sql` by hand.
3. Commit and push the updated file:
   ```bash
   git add database/init.sql
   git commit -m "chore(db): update schema"
   git push
   ```

### Pulling the latest changes

`init.sql` only runs when the database volume is empty. After pulling a schema change, recreate the database:

```bash
git pull
docker compose down -v   # deletes local database data
docker compose up -d --build --wait
```

## Testing and CI

GitHub Actions runs on every push and pull request to `main` and `develop`.

| Job | What it checks |
| --- | --- |
| `lint` | PHP syntax check on every `.php` file |
| `test` | Starts the full Docker stack, then verifies that the database schema loads (`users` and `pass_slips` tables exist), the login page responds, protected pages reject guests, and sensitive files (`.env`, `database/init.sql`, `docker-compose.yml`) are not publicly served |

The workflow needs two repository secrets: `MYSQL_ROOT_PASSWORD` and `MYSQL_DATABASE`. Add them under **Settings → Secrets and variables → Actions**.

### Running the checks locally

```bash
# PHP syntax check
docker exec pass-slip-web sh -c 'find /var/www/html -name "*.php" -print0 | xargs -0 -n1 php -l'

# Sensitive files should return 403
curl -I http://localhost:8080/.env
curl -I http://localhost:8080/database/init.sql
```

## Security Notes

- Never commit `.env`, credentials, or tool history files (`.aider.*`)
- `docker/apache-hardening.conf` is mounted into the web container and denies access to files such as `.env`, `.git`, and SQL scripts. Keep that volume in `docker-compose.yml`
- MySQL is published on `127.0.0.1` only, so other machines on your network cannot reach it
- Use a strong, unique `MYSQL_ROOT_PASSWORD`, and rotate it if it is ever exposed
- The whole project folder is served by Apache during development. Before any real deployment, serve only `public/` and the entry pages from the web root

## Useful Docker Commands

```bash
docker compose down                      # stop the application
docker compose restart                   # restart the application
docker compose ps                        # show container status
docker logs pass-slip-web                # web server logs
docker logs pass-slip-mysql              # database logs
docker exec -it pass-slip-mysql mysql -uroot -p pass_slip_system   # MySQL CLI (prompts for the password)
docker compose down && docker compose up -d --build --wait         # rebuild after Dockerfile changes
docker compose down -v && docker compose up -d --build --wait      # fresh database (deletes all data)
```

## Troubleshooting

### Port 3306 or 8080 already in use

Stop the conflicting service, or change the host port in `docker-compose.yml`, for example `"127.0.0.1:3307:3306"` for MySQL or `"8081:80"` for the web server.

To stop a local MySQL service:

```bash
net stop MySQL80                   # Windows
brew services stop mysql           # Mac
sudo systemctl stop mysql          # Linux
```

### Container name already in use

```bash
docker rm -f pass-slip-mysql pass-slip-web
docker compose up -d --build --wait
```

### Containers not starting, or the database never becomes healthy

```bash
docker compose ps
docker logs pass-slip-mysql
docker logs pass-slip-web
```

The most common cause is a missing or empty `MYSQL_ROOT_PASSWORD` in `.env`.

### Database connection errors

1. Confirm both containers are running with `docker compose ps`
2. Confirm the database is reachable: `docker exec -it pass-slip-mysql mysql -uroot -p pass_slip_system`
3. Confirm the credentials in `includes/db.php` match your `.env`

### Changes to `init.sql` are not applied

The schema only loads into an empty volume. Run `docker compose down -v`, then start again.

### A file returns 403 Forbidden when it should be accessible

`docker/apache-hardening.conf` blocks certain file types (such as `.sql`, `.md`, and `.conf`) and folders. If an asset you need is blocked, adjust the patterns in that file and restart the web container.

## Contributing

1. Create a new branch from `develop`
2. Make your changes
3. Test locally with Docker and run the checks above
4. Commit and push your changes
5. Open a pull request and wait for CI to pass

## Project Resources

- [Project workspace (Notion)](https://www.notion.so/2f9d5a371a7280539be5ea2c98185f50?v=2f9d5a371a728004b43a000c4dadb86c)
