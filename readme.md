The goal is to build an API that's queryable via HTTP and via Pub/Sub. The API will take a UTC date as an input and an integer that will represent an amount of business days past the date after which the settlement will reach the bank account.


Test Scenarios
The following test scenarios must be working:

Calling the API /api/v1/businessDates/getBusinessDateWithDelay with date "November 10 2018", delay 3 should return November 15th, 2 weekend days and 1 holiday day
Calling the API with date "November 15 2018", delay 3 should return November 19th, 2 weekend days and 0 holiday days
Calling the API with date "December 25 2018", delay 20 should return January 18th 2019, 8 weekend days and 2 holiday days


Url :
http://localhost/laraveltest/index.php/api/v1/businessDates?%20initialDate=Dec%2024%202019&delay=2


response screenshot: 
https://prnt.sc/oie1dz

Routing: routes/api
Controllers:
/app/Http/Controllers/Settlement/SettlementController.php
