## Register

`POST /api/user/register`

parameters:

    * first_name
    * last_name
    * email
    * password
    * phone
    
response: 

````JSON````

## Sign-in

`POST /api/user/sign-in`

parameters:

    * email
    * password
    
response: 

````JSON````

## Get companies

`GET /api/user/companies`

query parameters:

    * api_token
    
response: 

````JSON````

## Add company

`POST /api/user/companies`

parameters:

    * title
    * phone
    * description
    * api_token
    
response: 

````JSON````

## Send request to recovery password

`POST /api/user/recovery-password`

parameters:

    * email
    
response: 

````JSON````

## Create a new password

`PATCH /api/user/recovery-password`

parameters:

    * email
    * password
    * recovery_token
    
response: 

````JSON````


