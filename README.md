# Project Setup Guide

## Getting Started with Laravel

This guide will walk you through setting up your Laravel project.

### Prerequisites

- PHP installed on your local machine
- Composer (for managing PHP packages)
- SSH access to your remote Ubuntu server

### Step 1: Install Dependencies

Open your terminal and navigate to the project directory. Install the necessary Composer packages by running:

```bash
composer install
```

### Step 2: Establish Database Connection
You'll need to create an SSH tunnel to the remote database from your local machine. Keep this terminal session open for the duration of your work:

Linux
```bash
sudo ssh -L 13306:localhost:3306 -p 35007 ubuntu@195.235.211.197 -N
```

Windows:
```bash
ssh -L 13306:localhost:3306 -p 35007 ubuntu@195.235.211.197 -N
```

### Step 3: Set Up Environment Variables
Copy the .env.example file to a new file named .env, which will store your environment variables:

Edit the .env file with your database and other environment-specific settings.

### Step 4: Run the program

```bash
php artisan serve
```

# Code that can be useful for later:

$ php artisan serve

To create a model:

$ php artisan make:model Name

To create a controller:

$ php artisan make:controller Name

$ php artisan make:middleware IsAdmin  # Create a middleware
