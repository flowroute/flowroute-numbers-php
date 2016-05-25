# flowroute-numbers-php

## What is it?

**Flowroute-numbers-php** is a php API Wrapper that provides methods for interacting with **v1** (version 1) of the[Flowroute](https://www.flowroute.com) API. These methods can be used to accomplish the following:

* Search for purchasable phone numbers
* Purchase phone numbers
* View your owned phone numbers and their related details
* Create new inbound routes
* Update the primary and failover route on a phone number

### Documentation 
The full documentation for the v2 Flowroute API is available [here](https://developer.flowroute.com/v2.0/docs).

##Before you begin

The following are required before you can deploy the SDK.

### Have your API credentials

You will need your Flowroute API credentials (Access Key and Secret Key). These can be found on the **Preferences > API Control** page of the [Flowroute](https://manage.flowroute.com/accounts/preferences/api/) portal. If you do not have API credentials, contact <mailto:support@flowroute.com>.

### Know your Flowroute phone number

To create and send a message, you will need your Flowroute phone number, which should be enabled for SMS. If you do not know your phone number, or if you need to verify whether or not it is enabled for SMS, you can find it on the [DIDs](https://manage.flowroute.com/accounts/dids/) page of the Flowroute portal.

###Download Composer

Composer is used to manage the dependencies for the PHP SDK. This SDK does not cover those steps. See Composer's [Getting Started](https://getcomposer.org/doc/00-intro.md) guide at the Composer web site for the steps to download the setup file. Download, but do not install, Composer-setup.phar. Only after installing the libraries do you install Composer.

## Install the libraries

> **Note:** You must be connected to the Internet in order to install the required libraries.

1. Open a terminal session. 

2. If needed, create a parent directory folder where you want to install the SDK.
 
3. Go to the newly created folder, and run the following:

 	`https://github.com/flowroute/flowroute-numbers-php.git`
 	
 	The `git clone` command clones the **flowroute-numbers-php** respository as a sub directory within the parent folder.
 	
4.	Change directories to the newly created **flowroute-numbers-php** directory.

##Install Composer

1.	Move the downloaded **composer.phar** and **composer-setup.php** files to the **flowroute-numbers-php** directory.

	>**Note:** **composer.phar** must be in the **flowroute-numbers-php** directory in order to install correctly. Composer requires a **composer.json** file, which is included in the imported SDK to help manage dependencies.

2. 	From a terminal window, run the following:

		php composer.phar install

 	Composer sets up the required file structure.
  
## Create a PHP file to import the Controllers and Models

The following shows how to import the SDK and set up your API credentials. Importing the SDK allows you to instantiate the Controllers, which contains the methods used to perform tasks with the SDK. In order to do this, a PHP file is required. 

1. Using a code text editor — for example, *Sublime Text* — create a new file and add the following lines to import the Controllers and Methods:

		<?php
		require_once('vendor/autoload.php');
	
		use FlowrouteNumbersLib\Controllers\InboundRoutesController;
		use FlowrouteNumbersLib\Controllers\PurchasablePhoneNumbersController;
		use FlowrouteNumbersLib\Controllers\TelephoneNumbersController;
		
		$irc = new InboundRoutesController();
		$pnc = new PurchasablePhoneNumbersController();
		$tnc = new TelephoneNumbersController();
		
		use FlowrouteNumbersLib\Models\BillingMethod;
		use FlowrouteNumbersLib\Models\Route;
		
		?>
   
3. Save the file in your top-level **flowroute-numbers-php** folder. For this example, the PHP file is named ***import.php***.

4.	On the command line, run the PHP file you just created. For example,

		run import.php

4.	Next, configure your API Access Key and Secret Key.

5.	From your **flowroute-numbers-php** folder change directories to **src**. 

6.	Using a text editor, open **Configuration.php**. 

7.	In **Configure.php** replace AccessKey and SecretKey with your Flowroute API credentials. The file should resemble the following:

		<?php
		/*
		 * FlowrouteNumbersLib
		 *
		 * This file was automatically generated for flowroute by APIMATIC BETA v2.0 on 02/12/2016
		 */
	
		namespace FlowrouteNumbersLib;
	
		class Configuration {
 			//The base Uri for API calls
   			public static $BASEURI = 'https://api.flowroute.com/v1';
	
			//Tech Prefix
    		//TODO: Replace the $username with an appropriate value
			public static $username = '1111111';

			//API Secret Key
			//TODO: Replace the $password with an appropriate value
			public static $password = 'm8axLA45yds7kmiC88aOQ9d5caADg6vr';
		}
8.	Save the file.

	The Controllers point automatically to **Configuration.php**, so there is no need to do anything else with this file.
	
## List of Methods and Example Uses


### PurchasablePhoneNumbersController

The Purchasable Phone Numbers Controller contains all of the methods necessary to search through Flowroute's phone number inventory. 

Methods must be added to a PHP file and that file run from a command line. For example, you can create a`php purchase.php` contains the following information:

	<?php

	require_once('vendor/autoload.php');

	use FlowrouteNumbersLib\Controllers\PurchasablePhoneNumbersController;
	
	$pnc = new PurchasablePhoneNumbersController();

	?>

You can then add lines for each of the following PurchasePhoneNumbersController methods and comment out each line as needed, or create unique files for each of the following methods:

*	[listAvailableNPAs](#listnpa)
* 	[listAreaAndExchange](#listnpanxx)
* 	[search](#searchno)


#### `listAvailableNPAs ($limit)`<a name=listnpa></a>

#####Usage
Add the following lines to your PHP file and run.

	$response = $pnc->listAvailableNPAs($limit=null);
	print_r($response);`

The `listAvailableNPAs` method allows you to retrieve a list of every NPA (area code) available in Flowroute's phone number inventory.

| Parameter | Required | Usage                                 |
|-----------|----------|---------------------------------------|
| limit     | False    | Controls the number of items returned. The maximum number of items is 200. If no number is passed, `null` is passed, which returns all NPAs. |

##### Example usage
	
	$response = $pnc->listAvailableNPAs($limit=3);
	print_r($response);

#####Example response
For the response where a limit of 3 is passed, the first three NPAs are returned:

```sh
(
    [npas] => stdClass Object
        (
            [201] => stdClass Object
                (
                    [nxxs] => /v1/available-tns/npanxxs/?npa=201
                    [tns] => /v1/available-tns/tns/?npa=201
                )

            [203] => stdClass Object
                (
                    [nxxs] => /v1/available-tns/npanxxs/?npa=203
                    [tns] => /v1/available-tns/tns/?npa=203
                )

            [202] => stdClass Object
                (
                    [nxxs] => /v1/available-tns/npanxxs/?npa=202
                    [tns] => /v1/available-tns/tns/?npa=202
                )

        )

    [links] => stdClass Object
        (
            [next] => /v1/available-tns/npas/?limit=3&page=2
        )
```

#### `listAreaAndExchange ($limit,$npa,$page)`<a name=listnpanxx></a>

#####Usage
	listAreaAndExchange ($limit=null,$npa=null,$page=null)

The `listAreaAndExchange` method allows you to retrieve a list of every NPA-NXX (area code and exchange) available in Flowroute's phone number inventory.

| Parameter | Required | Usage                                                         |
|-----------|----------|---------------------------------------------------------------|
| limit     | False    |  Controls the number of items returned. The maximum number of items is 200. If no number is passed, `null` is passed, which returns all NPA NXX combinations.                        |
| npa       | False    | Limits results to the specified NPA. If `null` is passed, all NPAs are returned.|
| page      | False    | Sets which page of the results is returned.` Next` and `Prev` URLs provided at the bottom of the response provide navigation pointers. If `null` is passed, all pages are returned.   |

##### Example usage

In the following, a request is made to return only `2` results on page `3` for NPA `203`:
		
	$response = $pnc->listAreaAndExchange($limit=2,$npa=203,$page=3);
	print_r($response);
	
#####Example response
Based on the example usage above, the following two NPA NXX combinations are returned on page 2, organized by NPANXX. 

```sh
(
    [npanxxs] => stdClass Object
        (
            [203583] => stdClass Object
                (
                    [tns] => /v1/available-tns/tns/?npa=203&nxx=583
                )

            [203567] => stdClass Object
                (
                    [tns] => /v1/available-tns/tns/?npa=203&nxx=567
                )

        )

    [links] => stdClass Object
        (
            [prev] => /v1/available-tns/npanxxs/?npa=203&limit=2&page=2
            [next] => /v1/available-tns/npanxxs/?npa=203&limit=2&page=4
        )

)
```
#### `search ($limit = NULL,$npa = NULL,$nxx = NULL,$page = NULL,$ratecenter = NULL,$state = NULL,$tn = NULL)`<a name=searchno></a>

The search method is the most robust option for searching through Flowroute's purchasable phone number inventory. It allows you to search by NPA, NXX, Ratecenter, State, and TN.

| Parameter  | Required                       | Usage                                                                     |
|------------|--------------------------------|--------------------------------------------------------|
| limit      | False                          | Controls the number of items returned (Max 200)                                     |
| npa        | False, unless nxx is present   | Limits results to the specified NPA (also known as area code)             |
| nxx        | False                          | Limits results to the specified NXX (also known as exchange)              |
| page       | False                          | Determines which page of the results is returned                          |
| ratecenter | False                          | Limits results to the specified ratecenter                                |
| state      | False, unless state is present | Limits results to the specified state                                     |
| tn         | False                          | Limits results to the specified telephone number (supports prefix search) |

##### Example Usage

	$response = $pnc->search(10,206,641,null,seattle,wa,null);
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

> If your query is succesful you will be returned an empty string and a 201 Created

#### listAccountTelephoneNumbers ($limit = NULL,$page = NULL,$pattern = NULL)

The listAccountTelephoneNumbers method is used to retrieve a list of all of the phone numbers on your Flowroute account.

| Parameter | Required | Usage                                                     |
|-----------|----------|-----------------------------------------------------------|
| limit     | False    | Controls the number of items returned (Max 200)           |
| page      | False    | Determines which page of the results is returned          |
| pattern   | False    | A telephone number to search for (supports prefix search) |

##### Example Usage
	
	$response = $tnc->listAccountTelephoneNumbers(1,null,1206);
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
	$response = $tnc->update('12064205780',$rtes);
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

	$response = $irc->mlist(10,null);
	print_r($response);
	
#### createNewRoute ($routeName,$type,$value) 

The createNewRoute method is used to create a new inbound route.

| Parameter | Required | Usage                                                                                   |
|-----------|----------|-----------------------------------------------------------------------------------------|
| route_name | True     | The name you would like to assign to the new route (supports alphanumeric characters)   |
| mtype      | True     | The type of route you would like to create. Valid options are "HOST", "PSTN", and "URI" |
| value     | True     | The actual route that you would like to create                                          |

##### Example Usage

	$response = $irc->createNewRoute('PSTNroute1','PSTN','19513232211');
	print_r($response);
	
	$response = $irc->createNewRoute('HOSTroute1','HOST','4.239.23.40:5060');
	print_r($response);
	
	$response = $irc->createNewRoute('URIroute1','URI','sip:120664480000@215.122.69.152:5060');
	print_r($response);