# CompareIt

## Overview

**CompareIt** is a web app we built for comparing product prices from different stores. It lets users sign up, leave reviews, manage wishlists, and even lets retailers upload and manage their products. There’s also an admin panel for managing users.


---

## Tech Stack

We used the following technologies to build CompareIt:

- PHP 7.4+
- MySQL
- HTML5/CSS3
- JavaScript
- Composer (for dependency management)

## Getting It Running

## What You’ll Need

- XAMPP
- Composer


## Setup Instructions
1. **Clone the repo**

Open Git Bash and run:

git clone https://github.com/obedmbaya/COS221Assignment5.git
cd COS221Assignment5

2. **Install dependencies**
composer install

3. **Configure environment**

Update the .env file with your DB details (we will share one database, details will be sent):

DB_HOST=yourhostname
DB_PORT=3306
DB_DATABASE=yourdatabase
DB_USERNAME=yourusername
DB_PASSWORD=yourpassword

4. **Create the database**

- Database design from ER Diagram
- Make sure foreign keys and on delete cascade in check

5. **Run it**

    - Start Apache and MYSQL in XAMPP
    - visit http://localhost/COS221/COS221Assignment5/webpages/launch.php

## Features
1. **User Accounts**
    - Sign up / log in
    - Three types of users: Standard, Retailer, Admin
    - Edit your profile

2. **Product Comparison**
    - View the same product from different stores
    - Compare prices and specs
    - Leave reviews

3. **User Dashboards**
    - Regular users can manage wishlists and reviews
    - Retailers can manage their own products
    - Admins can manage users

4. **Shopping Tools**
    - Wishlists to save stuff you like
    - Product ratings
    - Filters and categories

## Project Structure

COS221Assignment5/
├── api/                     # Backend logic
│   ├── api.php              # Main controller
│   ├── config.php           # DB settings
│   ├── endpoints.php        # Main API routes
│   └── endpointsauthentication.php
├── webpages/                # Frontend files
│   ├── css/                 # Styles
│   ├── js/                  # Scripts
│   ├── img/                 # Images
│   ├── index.php            # Home
│   ├── login.php            # Login
│   ├── signup.php           # Register
│   └── ...more pages
├── .env                     # Your environment settings
├── .env.example             # Template for env file
├── composer.json            # Dependencies
└── README.md                # This file!


## API Documentation (Just a Few Examples)
The system includes several API endpoints such as:

- Login – Check credentials and sign in
- Signup – Register a new account
- Edit User – Update your profile info
- Remove User – Only for admins to delete users


## How to Use It
1. Open the app from the launch page
2. Create an account or log in
3. Look around for products
4. Add some to your wishlist
5. Leave a review
6. Update your profile if needed

## Security Stuff
- API keys are used for authentication
- Passwords are hashed using SHA-256 + salt
- Input validation is done on all user inputs
- Prepared statements help prevent SQL injection


## Contributors
- Mr. Josh Mahabeer - Backend API Integration
- Mr. Obed Mbaya - Backend API Integration
- Mr. Finnley Wyllie - Backend API Integration
- Mr. Zaman Bassa - Frontend development
- Miss. Mbali Thobejane - Frontend development
- Mr. Jared Williams - Backend API Integration
- Mr. Ange Yehouessi - Frontend development