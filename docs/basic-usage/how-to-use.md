---
title: How to use the package
sidebar_position: 2.1
---

## Setting up the environment
You are required to set up the environment variables in your `.env` file. You can get the values from the Bandeyri Pay dashboard.

```bash
BANDEYRI_API_URL=https://api.example.com
BANDEYRI_CLIENT_ID=your-client-id
BANDEYRI_CLIENT_SECRET=your-client-secret
BANDEYRI_APP_SIGNING_SECRET=your-app-signing-secret
```

## Usage
Once you have set up the environment variables, you can start using the package. The main class is `BandeyriPay` and is registered in the service container as a singleton.
You may use either of the below methods to get an instance of the `BandeyriPay` class.

```php
// Using helper method
bandeyriPay();

// Using the app helper method
app(\Javaabu\BandeyriPay\BandeyriPay::class);
```

Once the instance is obtained, you can use the following methods to interact with the Bandeyri Pay API.
1. Get Agency information
```php
bandeyriPay()->getAgency();
```

2. Get Agency purposes
```php
bandeyriPay()->getPurposes();
```

3. Get all transactions
```php
bandeyriPay()->getTransactions();
bandeyriPay()->paginateTransactions(page: 2);
```

4. Get transaction
```php
bandeyriPay()->getTransaction(transactionId: 'transaction-id');
```

5. Create transaction
```php
$transaction_data = TransactionData::fromArray([...])
bandeyriPay()->createTransaction(data: $transaction_data);
```

All the methods will return a BandeyriPayResponse object. You can use the `toDto()` method to convert the response to a DTO object. Below is an example of how to use the response object.

```php
/* @var BandeyriPayResponse $agency_information */
$agency_information = bandeyriPay()->getAgency();

/* @var CollectionResponse $agency_information_response */
$agency_information_response = $agency_information->toDto();

/* @var AgencyResponse $agency_obj */
$agency = $agency_information_response->data;

$agency->name; // Agency name
$agency->business_area; // Agency business_area
$agency->timezone; // Agency timezone
$agency->type; // Agency type
$agency->domain; // Agency domain
$agency->additional_domains; // Agency additional domains
$agency->transaction_types; // Array of TransactionTypeResponse objects
$agency->contacts; // Array [agency => ContactResponse, focal_point => ContactResponse]
```

In addition to the `toDto()` method, you can use the below methods on the response object.

```php
$response->isSuccessful(); // Check if the request was successful
$response->toArray(); // Convert the response to an array
$response->toJson(); // Convert the response to a JSON string
$response->toDto(); // Convert the response to a DTO object
```
