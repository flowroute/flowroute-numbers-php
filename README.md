# flowroute-numbers-php

## What is it?

Flowroute-numbers-php is a php API Wrapper that provides methods for interacting with [Flowroute's](https://www.flowroute.com) v1 API. These methods can be used to accomplish the following:

* Search for purchasable phone numbers
* Purchase phone numbers
* View your owned phone numbers and their related details
* Create new inbound routes
* Update the primary and failover route on a phone number

## Documentation 
The full documentation for Flowroute's v1 API is available at [Developer.flowroute.com](https://developer.flowroute.com/).

## How To Install 

We are using composer to manage the dependencies for the SDK and have already included a composer.json file for you. If you do not already have composer setup, please look at [Composer's Getting Started](https://getcomposer.org/doc/00-intro.md) article. Once you have composer setup, run the following command:

	cd flowroute-numbers-php/
	php composer.phar install

> Note: You will need to be connected to the internet in order to install the required packages
  
## How To Get Setup

The following shows how to import the SDK and setup your API credentials.

1) Import the SDK module:

	require_once('vendor/autoload.php');
	
	use FlowrouteNumbersLib\Controllers\InboundRoutesController;
	use FlowrouteNumbersLib\Controllers\PurchasablePhoneNumbersController;
	use FlowrouteNumbersLib\Controllers\TelephoneNumbersController;

2) Import the Models

	use FlowrouteNumbersLib\Models\BillingMethod;
	use FlowrouteNumbersLib\Models\Route;
   
3) Configure your API Username and Password from [Flowroute Manager](https://manage.flowroute.com/accounts/preferences/api/) in src/Configuration.php.
 > If you do not have an API Key contact support@flowroute.com:

	16 public static $username = 'AccessKey';
	20 public static $password = 'SecretKey';
	

## List of Methods and Example Uses

### PurchasablePhoneNumbersController

The Purchasable Phone Numbers Controller contains all of the methods neccesary to search through Flowroute's phone number inventory. 

#### listAvailableNPAs ($limit) 

The listAvailableNPAs method allows you to retrieve a list of every NPA (area code) available in Flowroute's phone number inventory.

| Parameter | Required | Usage                                 |
|-----------|----------|---------------------------------------|
| limit     | False    | Controls the number of items returned (Max 200) |

##### Example Usage
	
	$response = $pnc->listAvailableNPAs();
	print_r($response);

#### listAreaAndExchange ($limit = NULL,$npa = NULL,$page = NULL)

The listAreaAndExchange method allows you to retrieve a list of every NPA-NXX (area code and exchange) available in Flowroute's phone number inventory.

| Parameter | Required | Usage                                                         |
|-----------|----------|---------------------------------------------------------------|
| limit     | False    | Controls the number of items returned (Max 200)                         |
| npa       | False    | Limits results to the specified NPA (also known as area code) |
| page      | False    | Determines which page of the results is returned              |

##### Example Usage
		
	$response = $pnc->listAreaAndExchange();
	print_r($response);
	
#### search ($limit = NULL,$npa = NULL,$nxx = NULL,$page = NULL,$ratecenter = NULL,$state = NULL,$tn = NULL)

The search method is the most robust option for searching through Flowroute's purchasable phone number inventory. It allows you to search by NPA, NXX, Ratecenter, State, and TN.

| Parameter  | Required                       | Usage                                                                     |
|------------|--------------------------------|---------------------------------------------------------------------------|
| limit      | False                          | Controls the number of items returned (Max 200)                                     |
| npa        | False, unless nxx is present   | Limits results to the specified NPA (also known as area code)             |
| nxx        | False                          | Limits results to the specified NXX (also known as exchange)              |
| page       | False                          | Determines which page of the results is returned                          |
| ratecenter | False                          | Limits results to the specified ratecenter                                |
| state      | False, unless state is present | Limits results to the specified state                                     |
| tn         | False                          | Limits results to the specified telephone number (supports prefix search) |

##### Example Usage

	$response = $pnc->search(10, 206, 641, null, "seattle", "wa", null);
	print_r($response);
	
### TelephoneNumbersController

The Telephone Numbers Controller contains all of the methods neccesary to purchase a new phone number and to manage your owned phone number inventory.

#### purchase ($billing,$number)

The purchase method is used to purchase a telephone number from Flowroute's inventory.

| Parameter       | Required | Usage                                                                                |
|-----------------|----------|--------------------------------------------------------------------------------------|
| billing         | True     | A JSON object that specifies which billing method to use. Either "METERED" or "VPRI" |
| telephoneNumber | True     | The telephone number that you would like to purchase                                 |
	
##### Example Usage

	$billing = '{"billing_method": "VPRI"}';
	$response = $tnc->purchase($billing, '12094350424');
	print_r($response);

> If your query is successful you will be returned an empty string and a 201 Created

#### listAccountTelephoneNumbers ($limit = NULL,$page = NULL,$pattern = NULL)

The listAccountTelephoneNumbers method is used to retrieve a list of all of the phone numbers on your Flowroute account.

| Parameter | Required | Usage                                                     |
|-----------|----------|-----------------------------------------------------------|
| limit     | False    | Controls the number of items returned (Max 200)           |
| page      | False    | Determines which page of the results is returned          |
| pattern   | False    | A telephone number to search for (supports prefix search) |

##### Example Usage
	
	$response = $tnc->listAccountTelephoneNumbers(1, null, 1206);
	print_r($response);

#### telephoneNumberDetails ($telephoneNumber) 

The telephoneNumberDetails method is used to retrieve the billing method, primary route, and failover route for the specified telephone number. 

| Parameter       | Required | Usage                                             |
|-----------------|----------|---------------------------------------------------|
| telephoneNumber | True     | The telephone number that you would like to query |

##### Example Usage

	$response = $tnc->telephoneNumberDetails(12064205788);
	print_r($response);

#### update ($number,$routes)

The update method is used to update both the primary and failover route for a phone number. Both the primary and failover route must be specified inside of an array (see Example Usage). The first route name specified will be assigned as the primary route and the second route name specified will be assigned as the failover route. The list of available route names can be retrieved by using the list method in the InboundRoutesController.

| Parameter       | Required | Usage                                                                  |
|-----------------|----------|------------------------------------------------------------------------|
| telephoneNumber | True     | The telephone number that you would like to update routes for          |
| routes          | True     | The names of the primary and failover routes for the phone number (must be an array) |

##### Example Usage
	
	$rtes = '{"routes": [{"name": "ea4f4056663e27b082999689982e4771"}, {"name": "5ec40d37d10ae11fe5690c0b00f6a903"}]}'; 
	$response = $tnc->update('12064205780', $rtes);
	print_r($response);
	
> A list of all available route names can be found by using the mlist function in the InboundRoutesController.

### InboundRoutesController

The Inbound Routes Controller contains the methods required to view all of your existing inbound routes and to create new inbound routes.

#### mlist ($limit = NULL,$page = NULL)

The list method is used to return all of the existing inbound routes from your Flowroute account.

| Parameter | Required | Usage                                            |
|-----------|----------|--------------------------------------------------|
| limit     | False    | Controls the number of items returned (Max 200)  |
| page      | False    | Determines which page of the results is returned |

##### Example Usage

	$response = $irc->mlist(10, null);
	print_r($response);
	
#### createNewRoute ($routeName,$type,$value) 

The createNewRoute method is used to create a new inbound route.

| Parameter | Required | Usage                                                                                   |
|-----------|----------|-----------------------------------------------------------------------------------------|
| route_name | True     | The name you would like to assign to the new route (supports alphanumeric characters)   |
| type      | True     | The type of route you would like to create. Valid options are "HOST", "PSTN", and "URI" |
| value     | True     | The actual route that you would like to create                                          |

##### Example Usage

	$response = $irc->createNewRoute('PSTNroute1', 'PSTN', '19513232211');
	print_r($response);
	
	$response = $irc->createNewRoute('HOSTroute1', 'HOST', '4.239.23.40:5060');
	print_r($response);
	
	$response = $irc->createNewRoute('URIroute1', 'URI', 'sip:120664480000@215.122.69.152:5060');
	print_r($response);