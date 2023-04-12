# tempnumber-api-client

An API client for Temp-Number service ( https://temp-number.org/ ) written in PHP.

If you encounter a bug or have an idea to improve the code, feel free to open an issue or submit a pull request.

## Installation

````
$ Composer require ahmedghanem00/tempnumber-api-client
````

## Usage

#### Client Initialization :

````php
use ahmedghanem00\TempNumberClient\Client;

$client = new Client('YOUR_API_TOKEN');
````

#### Retrieve Balance :

````php
try {
    echo $client->retrieveBalance(); // float "3.15"
} catch (Exception $e) {
    echo $e->getMessage();
}
````

#### Requesting new activation :

````php
use ahmedghanem00\TempNumberClient\Enum\Country;
use ahmedghanem00\TempNumberClient\Enum\Service;

try {
    # You can either use Service & Country enums as arguments or use the ids directly ( 'facebook', 'us' )
    # If the activation price is higher than the given expected, the 'ExpectedPriceException' will be thrown
    $newActivation = $client->requestNewActivation(Service::Facebook, Country::United_States, $expectedPrice = 2.5);
    
    [$countryCode, $numberOnly, $formattedPhoneNumberWithCountryCode] = [
        $newActivation->getPhoneNumberCountryCode(),
        $newActivation->getPhoneNumberWithoutCountryCode(),
        $newActivation->getFormattedPhoneNumberWithCountryCode()
    ];
    
    # Will continuously polling the activation data from server until the specified condition is met.
    # Otherwise, the appropriate exception will be thrown
    $fulfilledActivationWithSmsMessage = $client->waitForActivationStatus($newActivation->getId(), ActivationStatus::SMS_RECEIVED, $pollingInterval = 2, $maxDuration = 100);

    echo $fulfilledActivationWithSmsMessage->getReceivedSMS(); ## "Thanks for activation. Your activation code is: 5678"
    echo $fulfilledActivationWithSmsMessage->getDetectedOtpCodeFromReceivedSMS(); ## "5678"
} catch (Exception $e) {
    echo $e->getMessage();
}
````

#### Retry Activation :

In case you have specific activation that needs to be retried

````php
try {
    $client->retryActivation($newActivation->getId());
} catch (Exception $e) {
    echo $e->getMessage();
}
````

#### Retrieve specific activation data :

````php
try {
    $activationData = $client->retrieveActivationData(181822);
    
    echo $activationData->getStatus(); // string "smsRequested"
    echo $activationData->getCreationTimestamp(); // int "1681333257"
    echo $activationData->getRemainingSecondsToExpire(); // int "300"
    echo $activationData->isRetryable(); // bool "false"
    echo $activationData->getPhoneNumberWithoutCountryCode() // string "(555) 555-1234"
catch (Exception $e) {
    echo $e->getMessage();
}
````

#### Retrieve specific Service & country Info :

````php
use ahmedghanem00\TempNumberClient\Enum\Country;
use ahmedghanem00\TempNumberClient\Enum\Service;

try {
    $info = $client->retrieveCountryServiceInfo(Service::Instagram, Country::Russia)
    
    echo $info->hasNumbers(); // bool "true"
    echo $info->getPrice(); // float "2.15"
} catch (Exception $e) {
    echo $e->getMessage();
}
````

#### Retrieve all services/countries info :

````php
# Grouped by service
$infoGroupedByService = $client->retrievePriceListByService();

# OR by country
$infoGroupedByCountry = $client->retrievePriceListByCountry();

foreach ($infoGroupedByCountry as $country) {
    echo $country->getName(); // 'uk'
    
    foreach ($country->services() as $countryService) {
        echo $countryService->getName(); // string "facebook"
        echo $countryService->getPrice(); // float "1.25"
    }
}
````

#### Retrieve all previous activations data :

````php
$result = $client->retrieveActivationHistory($page = 2, $limit = 5);

echo $result->getPageIndex(); // 2
echo $result->getPagesCount(); // 30
echo $result->getActivationsCount(); // 200

foreach ($result->activations() as $activation) {
    echo $activation->getId();
    echo $activation->getStatus();
    echo $activation->getPrice();
    echo $activation->getFormattedPhoneNumberWithCountryCode();
    echo $activation->getReceivedSMS();
}
````


##
