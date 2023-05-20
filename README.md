<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Inventory Management System

## User Guide

### Description

This system allow customers to place orders, when a customer makes an order that includes a specific product the system will update the stock of each of the ingredients so it reflects the amounts consumed.

Also when any of the ingredients stock level reaches 50%, the system should send an email message to alert the merchant they need to buy more of this ingredient.

The system accepts two types of products: compound product ex: Burger not an ingredient product ex: onion.

### Usage

The module is used to :
1 - Authenticate customers while checking out.
2 - Store customers' orders along with each order if.
3 - Update stock of each product after placing orders.
4 - Send email to seller incase any ingredient stock level reached 50%.

## Implementation Guide

### Configuration

* Configure seller data including.
  * SELLER_NAME.
  * SELLER_EMAIL.
* Configure DEFAULT_STOCK_ID to specify a stock to be used during placing orders.
* Configure _TOKEN used during customers authentication.

### Installation

Migrate data using ``php artisan migrate``
Then seed the database ``php artisan db:seed``
To run tests use ``composer tests``

### Database Structure

TODO:: WORK ON THAT

### API Documentation / Consumers' Guide
1) Creating Order.
    - Endpoint: APP_URL/api/orders;
    - Method: POST
    - Headers: 
    ```php
    {
        'Authorization'=>'Bearer '.$token,
        'Accept' => 'application/json'
    }
    ```
    - Body: 
    ```php
    {
    "products": [
        {
            "product_id": 4,
            "quantity": 3
        },
        {
            "product_id": 3,
            "quantity": 40
        }
        ] 
    }
    ```
    - Restrictions: 
      1) ```products``` attribute is required.
      2) ```product_id quantity``` are also required for each product.
      3) The first product should always be a compound product.
