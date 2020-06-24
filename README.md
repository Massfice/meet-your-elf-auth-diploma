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
- Composer
- Git

## Solution

###### Main Usage
It generates token based on provided credentials and validates this token in future request. It can also create new account.

###### API Endpoints:
- Schema: {host}/public/{action}/json. where:
    - {host} is localhost or meet-your-elf-auth.herokuapp.com
    - {action} is an action that we want to perform
- Endpoints:
    - **{host}/public/token/json:**
        - **POST**: It requires *username* and *password* provided in json format. It validates these credentials against my Okta instance/org. This is something like proxy. I can always change Okta on something different, e.g. on my own database without impacting on my other applications/microservices. It also simplifies http requests. It uses *token inside token* solution. First it takes appropriate user secret (based on username) from Redis database. Then it creates JWT based on taken user secret. Then wrappes generated token and username into *common data object* and takes *general secret* from Redis database. Final step is creating token based on general secret and common data object.
        - **GET**: It performs opposite steps than *token post*. First it decodes token provided by *Authorization Bearer* header using *general secret*. Then reads username from decoded token and retrieve *user secret* (by username) from Redis and decode *inside token* using user secret.
    - **{host}/public/register/json:**
        - **POST**: This endpoint expects *username*, *password*, *repassword*, *firstName* and *lastName* in json format. It validates provided data against own rules and then forwarded it into Okta to further process. If everything went good, user would be created.
    - **{host}/public/secret/json:**
        - **PUT**: This endpoint expects "Authorization Bearer" header with token. It decodes *outside token* and then retrieves username. Then it generates new secret for particular user randomly and saves this secret in Redis database. Prior tokens will be instantly *blacklisted* and unreadable. It *destroys* all token generated for particular user. It's logout mechanism and additional security layer.

###### Cors Config
Currently it's configured to support local development. It's not commercial project and I decided to allow for each endpoint:
- **Headers:**
    - Authorization
    - Content-Type
- **Methods:**
    - GET
    - PUT
    - POST
    - DELETE
    - HEAD
- **Origins:**
    - "http://localhost:3000"
    - "http://localhost:8000"
    - "http://localhost:8080"
    - "http://localhost:80"
    - "http://localhost"

I think, it's enough, but if you need something different, just clone it into your local http root directory:
`git clone https://github.com/Massfice/meet-your-elf-auth-diploma`
And make changes in CorsConfig (located in *src/Customs*) or Config (located in *src/Configs*). **Config class** is developer customizable class that are executed first. Then install composer depedencies:
`composer install`
Make sure it is located directly into local server root directory, because there's a problem with action/URI params/clean urls resolving when it is placed into subfolders.

## End Words

I'm Adrian Larysz. Like I said, it's for my diploma project. To prove that I'm the author, I created additional endpoint: [About](https://meet-your-elf-auth.herokuapp.com/public/about/json).

