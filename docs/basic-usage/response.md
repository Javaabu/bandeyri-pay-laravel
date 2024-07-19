---
title: Response
sidebar_position: 2.3
---

# Response Handling

All the methods will return a BandeyriPayResponse object. You can use the `toDto()` method to convert the response to a DTO object. Below is an example of how to use the response object.

## Converting the response to a DTO object
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

## Additional methods
In addition to the `toDto()` method, you can use the below methods on the response object.

```php
$response->isSuccessful(); // Check if the request was successful
$response->toArray(); // Convert the response to an array
$response->toJson(); // Convert the response to a JSON string
$response->toDto(); // Convert the response to a DTO object
```

