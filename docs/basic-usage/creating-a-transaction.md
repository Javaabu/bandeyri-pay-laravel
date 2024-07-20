---
title: Creating a transaction
sidebar_position: 2.4
---

# Creating a transaction

This method is used to create a new transaction. You need to pass the transaction data as an argument to the `createTransaction` method. The transaction data should be an instance of the `TransactionData` class.

Below is an example of how to create a transaction using an array.

```php
use Javaabu\BandeyriPay\DTO\TransactionData;

$transaction_data = TransactionData::fromArray([
    "currency" => "MVR",
    "purposes" => [
        [
            "id" => "a443ced2d143483cbc963176ecd14601",
            "amount" => 1040
        ],
        [
            "local_code" => "LC123",
            "amount" => 1040
        ],
        [
            "id" => "a443ced2d143483cbc963176ecd14602", // only id will be used when bother local_code and id are provided
            "local_code" => "LC124",
            "amount" => 1040
        ]
    ],
    "customer" => [
        "type" => "Individual Local",
        "id" => "A123456",
        "name" => "Ibrahim Rasheed"
    ],
    "return_url" => "https://www.example.com/payment"
]);
    
```
