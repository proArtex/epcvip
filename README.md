#EPCVIP test

## Setup
```
git clone https://github.com/proArtex/epcvip.git
cd epcvip
composer install
cp .env .env.local
# put appropriate envs to .env.local
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate -n
bin/console doctrine:fixtures:load -n
```

---
## Run
```
bin/console server:run
# server is listening on http://127.0.0.1:8000
```

---
## API
### Auth
Use `Authorization` header with `Bearer` token equals to `API_TOKEN` env
### Endpoints
Run `bin/console debug:route | grep api` to go through API endpoints

---
## Forms
Visit http://127.0.0.1:8000/customer

---
## Comments
* Auth is made like this just to save time
* ISSN generation algorithm is wrong just to save time
* API exceptions are handled a bit different in `prod` environment
* The code consists few TODOs just to save time
### approach
I strongly believe that entities should not have invalid state.
Validation should not be made after entity becomes invalid.
Separate setters can cause invalid state if data is coupled.
Forms for entities kill them, so I used DTOs to keep entities valid.
