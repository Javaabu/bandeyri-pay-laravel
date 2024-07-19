---
title: How to use the package
sidebar_position: 2.2
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
### Get Agency information
This method provides information about the government office responsible for the Bandeyri Pay API. For example, if the Ministry of Islamic Affairs uses the API, the agency information will pertain to the Ministry of Islamic Affairs.
```php
bandeyriPay()->getAgency();
```

### Get Agency purposes
This method provides a list of services offered by the agency, similar to budge codes where specific types of funds are collected under designated codes. For example, if the Ministry of Islamic Affairs uses the API, the purposes will be the services provided by the Ministry. Each transaction will have a specific purpose, and the payment will be made for that purpose.
```php
bandeyriPay()->getPurposes();
```

### Get all transactions
This method provides a list of all transactions made through the Bandeyri Pay API. You can also paginate the results by passing the page number as an argument to the `paginateTransactions` method.
```php
bandeyriPay()->getTransactions();
```

If you would like to paginate the transactions, you can pass the page number as an argument to the `getTransactions` method. The transactions are paginated with a default limit of 15 transactions per page. Bandeyri Pay API does not allow you to change the per page limit.

```php
bandeyriPay()->getTransactions(page: 1);
```

### Get transaction
This method provides information about a specific transaction. You need to pass the transaction ID as an argument to the `getTransaction` method.
```php
bandeyriPay()->getTransactionById(transactionId: 'transaction-uuid');
```

### Create transaction
This method is used to create a new transaction. You need to pass the transaction data as an argument to the `createTransaction` method. The transaction data should be an instance of the `TransactionData` class.
```php
$transaction_data = TransactionData::fromArray([...])
bandeyriPay()->createTransaction(data: $transaction_data);
```
