# Employees

This repository provides a Docker-based LEMP (Linux, Nginx, MySQL, PHP) stack for web development, along with a Vue.js project for managing employees. It is designed to simplify the setup of a local development environment for employee management applications. The LEMP stack serves PHP files from `/pub_html`. This README guide will help you quickly get started with the setup and usage of this development environment.

## Prerequisites

Before you begin, make sure you have the following installed on your system:

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Quick Start

1. Clone this repository to your local machine:

```bash
gh repo clone leon-v/ANEmployees
cd ANEmployees
```

2. Build and start the Docker containers:

```bash
docker-compose up
```

This command will start the Nginx, PHP-FPM, and MySQL containers in the background.

3. Load the URL of the LEMP server:

[http://localhost](http://localhost)

## File Structure

The repository is structured as follows:

- `/docker`: Contains Docker-related configuration files.
- `/nginx`: Nginx configuration files.
- `/php`: PHP-FPM configuration files.
- `/mysql`: MySQL initialization files.

- `/pub_html`: Web root directory where HTML and PHP files are served.
- `/php`: PHP files and scripts (used for development and production).
- `/vue`: Compiled Vue.js project files.

- `/vue`: Vue.js source code and development environment.

## Developing PHP

To develop PHP code within this environment:

1. **Prerequisites**: Ensure that you have the necessary PHP development tools on your system.

2. **Important Note**: Be cautious when modifying PHP files in the `/pub_html/php` directory, as these same files are used in both the development and production environments.

3. Modify or add PHP files in the `/pub_html/php` directory. These files will be accessible through the LEMP stack.

## Testing PHP

There is an images dedicated to testing PHP.
The `test` image will be run once and end showing the output to the terminal.
You can start this image and run the tests with this command:

```bash
docker-compose run test
```

## Developing Vue

To develop the Vue frond end:

1. **Prerequisites**: Ensure that you have Node.js and npm installed on your system. You'll need them for Vue.js development.

2. Install Vue.js dependencies for the development environment. In the `/vue` directory, run:

```bash
cd vue
npm install
```

3. You can now start the Vue development server to work on your front-end code:

```bash
npm run serve
```

This will launch a development server for the Vue front end, which can be accessed at `http://localhost:8080`.

4. To build the Vue front end for production and have it served by the Nginx container, use:

```bash
npm run build
```

This will create a production build of the Vue application in the `/pub_html/vue` directory, which will be served by the LEMP stack at `http://localhost`.

## Additional Information

- The MySQL container is configured to initialize the database using the `init.sql` script in the `/docker/mysql` directory.

This development environment is designed to help you quickly get started with building employee management web applications using the LEMP stack for PHP and Vue.js. You can expand and customize it further to meet your specific project requirements.