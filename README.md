<a ref="https://github.com/Developers-Studio-Limited/iCarePro_Backend/blob/dev/documents/assets/logo.png">
   <img src="https://github.com/Developers-Studio-Limited/iCarePro_Backend/blob/dev/documents/assets/logo.png"  width="200px" align="right">
</a>

# iCarePro

## Introduction
The purpose of iCarePro EMR is to develop a market competitive EMR solution that is fully HIPAA compliant. iCarePro EMR will be providing a way to store and maintain standard clinical data that will be gathered about a patient. It will help streamline many of the functions integral for running the clinical practice. iCarePro EMR helps in increasing the revenue, can easily be accountable, and helps in the hectic procedure of billing patients. Reimbursements can happen at faster rates It will eradicate all the paperwork and the strain of storing all the paperwork.
<br>
<br>

## Table of Contents
  <summary><b>Table of Contents</b></summary>
  <br>
  <ol>
    <li><a href="#modules">Modules</a>
        <ul>
             <li><a href="#1-practice">Practice</a></li>
             <li><a href="#2-doctors">Doctors</a></li>
             <li><a href="#3-patient">Patient</a></li>
        </ul>
    </li>
    <li><a href="#stack-used">Stack Used</a></li>
    <li><a href="#installation-procedure">Installation Procedure</a>
        <ul>
             <li><a href="#prerequisites">Prerequisites</a></li>
             <li><a href="#installation-steps">Installation Steps</a></li>
            <li><a href="#installation-via-docker">Installation via Docker</a></li>
        </ul>
    </li>
    <li><a href="#directory-structure">Directory Structure</a></li>
    <li><a href="#third-party-integrations">Third Party Integrations</a></li>
    <li><a href="#external-packages">External Packages</a></li>
    <li><a href="#postman-collection-link">Postman Collection Link</a></li>
    <li><a href="#erd-link">ERD Link</a></li>
  </ol>

<br>

## Modules

### 1. Practice
Practice registration will consist of two parts, Initial request form & Practice registration form. In the first part, practice will submit the initial request form, which will be verified by the system administrator, upon confirmation the Practice registration form (PRF) will be sent to practice in order to complete the registration process. Once practice registration form (PFR) is submitted, system administrator will review the form, upon approval Practice account will be generated in the systema and practice will be notified via email along with login credentials.
Access workflow diagram for practice registration.

### 2. Doctors
Doctor registration will be processed under the practices, as each practice will be registered and managing their doctors within their administrative dashboard. Doctors' registration will start with the Doctor Registration Form (DRF), as this form will be generated and filled into the system by the practice administrator / Staff (based on rights). Once the Doctor Registration Form (DRF) is submitted, doctors will receive the confirmation email of registration along with a Know Your Customer (KYC) link in order to verify their identity. Upon KYC verification is completed, the system administrator will be notified and based on the KYC result, the system administrator will confirm the account registration. This doctor will receive a welcome email along with login credentials.
<p align="right">(<a href="#top">Back to top</a>)</p>

### 3. Patient
From an online portal, patients can register with very basic information by providing their Phone, Name and email address. Through these steps patients will land on the dashboard and will be able to see or book their appointments. Rest of the information can be filled out in stages or doctors can fill in based on their appointments or desired need. The Practice Staff / doctor will register the patient with the minimum required information asked on. At first the Staff / doctor will add the patient's phone number and fetch the records, if the record exists then it will be auto-filled, else it will be filled by the Staff / doctor. Once a patient is registered, the system will create an account for the patient which contains every information of the patient along with the patient profile.
<p align="right">(<a href="#top">Back to top</a>)</p>

## High Level Architecture Diagram

<div align="center">
<a ref="https://github.com/Developers-Studio-Limited/iCarePro_Backend/blob/dev/documents/assets/high-level-architecture.png"  >
   <img src="https://github.com/Developers-Studio-Limited/iCarePro_Backend/blob/dev/documents/assets/high-level-architecture.png" width="700px" >
</a>
</div>


### Stack Used

-   [Laravel (Back-End)](https://laravel.com/)
-   [PostgreSQL (Database)](https://www.postgresql.org/)

## Installation Procedure  

### Manual Installation  

### Prerequisites

* Install Composer<br>
<a target="_blank" href="https://getcomposer.org/download">Download Composer</a> which one supported with system.

### Installation Steps

1. Clone the repository from git. <br />

``` 
https://github.com/Developers-Studio-Limited/iCarePro_Backend.git
```

2. Extract constants.zip in [documents](https://github.com/Developers-Studio-Limited/iCarePro_Backend/blob/dev/documents) folder. Place constants file in config constants folder.<br />

3. Setup database in constants. Setup email credentials in .env <br />

4. Install Composer <br />
```
composer install
```
5. Run Migrations <br />

```
php artisan migrate 
```
6. Run Seeder <br />
```
php artisan db:seed
```
8. Run Laravel Project <br />

```
php artisan serve 
```
## Installation via Docker
### Prerequisites

* Install Docker<br>
<a target="_blank" href="https://www.docker.com/"> Download Docker</a> which one supported with system.


1. Extract constants.zip in [documents](https://github.com/Developers-Studio-Limited/iCarePro_Backend/blob/dev/documents) folder. Place constants file in config constants folder.<br />

2. Setup database credentials in constant file <br />

3. Setup mail credentials in .env <br />

4. Run Docker Compose Command

```
docker-compose up
```

### Directory Structure

```
└─ iCarePro
   ├─ app
   │  ├─ Filters
   │  │  ├─ (Search Filters will be here)
   
   │  ├─ Helper
   │  │  ├─ (Helper Functions will be here)
   
   │  ├─ Http
   │  │  ├─ Controllers
   │  │  │  ├─ (Controllers will be here, controller will further call Services)
   │  │  ├─ Middleware
   │  │  │  ├─ (Middleware will be here)
   │  │  └─ Requests
   │  │     ├─ (Request validation classes will be here)
   
   │  ├─ Jobs
   │  │  ├─ (Queue jobs will be here)
   
   │  ├─ libs
   │  │  ├─ (Libs will be here)
   
   │  ├─ Mail
   │  │  ├─ (Mails will be here)
   
   │  ├─ Models
   │  │  ├─ (Models will be here)
   
   │  ├─ Repositories
   │  │  ├─ Modules Repositories will be here
   │  │  │  ├─ Interfaces will be here
   │  │  │  ├─ Repositories will be here

   │  ├─ Traits
   │  │  ├─ (Traits will be here)

   ├─ database
   │  ├─ migrations
   │  │  ├─ (Migrations will be here)
   │  ├─ Seeder
   │  │  ├─ (Seeders will be here)

   │  └─ views
   │     ├─ (Views will be here)
   ├─ routes
   │  ├─ (API routes will be here)
```

<!-- USAGE EXAMPLES -->
<!-- ## Usage -->

## Third Party Integrations

1. **<a href="https://shuftipro.com/">Shufti Pro</a>**
   <br/>Real-time Identity Verification KYC, AML and KYB. With Shufti Pro you get an extensive enterprise solution which is much more than a simple Identity Verification. The core functionalities cover each step from verifying a user to the double-checking of riskier instances. Take advantage of a 98.67% Accuracy Rate with Shufti Pro.

2. **<a href="https://stripe.com/">Stripe</a>**
   <br/>A fully integrated suite of payments products.Stripe's products power payments for online and in-person retailers, subscriptions businesses, software platforms and marketplaces, and everything in between. We also help companies beat fraud, send invoices, issue virtual and physical cards, get financing, manage business spend, and much more.

3. **<a href="https://www.sendinblue.com/">Send In Blue</a>**
   <br/>Sendinblue is the only all-in-one digital marketing platform empowering B2B and B2C businesses, ecommerce sellers and agencies to build customer relationships through end to end digital marketing campaigns, transactional messaging, and marketing automation.


4. **<a href="https://www.twilio.com/">Twilio</a>**
   <br/>Twilio’s mission is to unlock the imagination of builders. We’re the customer layer for the internet, powering the most engaging interactions companies build for their customers. We provide simple tools that solve hard problems, delivered as a developer-first cloud platform with global reach and no shenanigans pricing.
   
<p align="right">(<a href="#top">Back to top</a>)</p>

## External Packages

Following external packages are used:

1. [Lavevel Passport](https://laravel.com/docs/9.x/passport) <br/> This package adds functionality of Authentication of users.
2. [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) <br/> This package adds functionality to define different Roles and Permissions and assign the role to a user.
3. [L5 Swagger](https://github.com/DarkaOnLine/L5-Swagger) <br/> This package is a wrapper of [Swagger-php](https://github.com/zircote/swagger-php) and [swagger-ui](https://github.com/swagger-api/swagger-ui) adapted to work with Laravel. The actual Swagger spec is beyond the scope of this package. All L5-Swagger does is package up swagger-php and swagger-ui in a Laravel-friendly fashion, and tries to make it easy to serve. For info on how to use swagger-php [look here](https://zircote.github.io/swagger-php/). For good examples of swagger-php in action [look here](https://github.com/zircote/swagger-php/tree/master/Examples/petstore.swagger.io).
3. [Cartalyst](https://cartalyst.com/manual/stripe/2.0) <br/> A comprehensive API package for Stripe. The package requires PHP 5.5.9+ and follows the FIG standard PSR-1, PSR-2 and PSR-4 to ensure a high level of interoperability between shared PHP code and is fully unit-tested. For good examples of Laravel-Cartalyst in action [Look Here](https://github.com/cartalyst/stripe/tree/v2.2.0)
<p align="right">(<a href="#top">Back to top</a>)</p>

## Postman Collection Link
  https://github.com/Developers-Studio-Limited/iCarePro_Backend/tree/dev/documents/PostmanCollection

## Endpoints Swimlane Link
  https://github.com/Developers-Studio-Limited/iCarePro_Backend/tree/dev/documents/Swimlanes
 
## ERD Link
  https://github.com/Developers-Studio-Limited/iCarePro_Backend/blob/dev/documents/assets/ERD.pdf

</ol>

## Environment
Environment   | Configurations                                    | URL
--------      | -----------------                                 | ------ 
Development-Backend          | Development environment of Agent portal Backend  | [Development URL](https://dev.icarepro.health)
</details>

<p align="right">(<a href="#top">Back to top</a>)</p>

