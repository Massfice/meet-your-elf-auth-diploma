# meet-your-elf-auth-diploma

## Overwiev

###### General
This is my auth application for my diploma project. My diploma project will use several microservices. And it's authorization i authentication point for all of them. It's stateless but it uses Redis database. It provides API endpoints for different purposes.

###### Tech Stack
It uses:
- PHP 7.4
- Redis
- Heroku (it's already deployed: [here](http://meet-your-elf-auth.herokuapp.com/public/))
- [Okta](https://www.okta.com/) and [Okta Developer](https://developer.okta.com/)
- [My own PHP mini-framework](https://github.com/Massfice/application)

## Solution

###### Main Usage
It generates token based on provided credentials and validates this token in future request. It can also create new account.

###### API Endpoints:
- Schema: {host}/public/{action}/json. where:
    - {host} is localhost or meet-your-elf-auth.herokuapp.com
    - {action} is an action that we want to perform
- Endpoints:
    - {host}/public/token/json:
        - **POST**: It requires *username* and *password* provided in json format. It validates these credentials against my Okta instance/org. This is something like proxy. I can always change Okta on something different, e.g. on my own database without impacting on my other applications/microservices. It also simplifies http requests.