## Booking Reservations Parser API

This project is a Laravel-based API for uploading and parsing booking reservation files. It accepts files in various formats (Excel, HTML, PDF), processes their content, and stores relevant event data in a SQLite database.

## Table of Contents

1. [Project Overview](#project-overview)
2. [Features](#features)
3. [Requirements](#requirements)
4. [Installation](#installation)
5. [Running the Project](#running-the-project)
6. [API Endpoints](#api-endpoints)
7. [File Formats Supported](#file-formats-supported)
8. [Testing](#testing)
9. [Contributing](#contributing)
10. [License](#license)

## Project Overview

The **Booking Reservations Parser API** allows users to upload various file types that contain booking reservation data. The API will parse these files, extract the relevant information (such as flight numbers, departure times, and locations), and store them in a database.

## Features

- **File Upload**: Accepts Excel, HTML, and PDF file formats.
- **Data Parsing**: Parses uploaded files and stores booking events into a database.
- **Multiple Event Types**: Supports different event types like `OFF`, `SBY` (standby), and `FLT` (flight).
- **Optional Truncation**: Optionally truncates the database before inserting new records.
- **SQLite Database**: Uses SQLite as the primary database for event storage.

## Requirements

Before setting up the project, ensure you have the following installed:

- **PHP** >= 8.0
- **Composer**
- **Laravel** >= 9.x
- **SQLite**

Optional dependencies:

- **Node.js** (for front-end or asset management)
- **Docker** (if deploying in containers)

## Installation

Follow these steps to set up and run the project locally:

1. **Clone the repository:**

    ```bash
    git clone https://github.com/yourusername/booking-reservations-parser.git
    cd booking-reservations-parser
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Set up environment variables:**

    Copy the `.env.example` file to `.env` and update any necessary configuration (such as database connection).

    ```bash
    cp .env.example .env
    ```

4. **Set up SQLite database:**

    - In the `.env` file, configure the database to use SQLite.
    - Create an empty SQLite database file in the `database/` folder.

      ```bash
      touch database/database.sqlite
      ```

5. **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

6. **Run database migrations:**

    ```bash
    php artisan migrate
    ```

7. **Install Node.js dependencies (optional):**

    ```bash
    npm install && npm run dev
    ```

## Running the Project

To start the development server, run the following command:

```bash
php artisan serve
```

The API will be available at http://localhost:8000.

## API Endpoints

### 1. Upload File and Parse Data

- **Method**: `POST`
- **URL**: `/api/upload-excel`
- **Description**: Uploads a file (Excel, HTML, PDF) and parses its content to store events in the database.

#### Parameters:

- `file` (required): The file to upload (supports `xlsx`, `xls`, `html`, `pdf`).
- `deleteRecords` (optional): A boolean flag (`true` or `false`) indicating whether to truncate the database before inserting new records.

#### Sample Request (using Postman or CURL):

```bash
curl -X POST http://localhost:8000/api/upload-excel \
  -F 'file=@/path/to/your/file.xlsx' \
  -F 'deleteRecords=true'
