## Install
* install docker, docker compose
* run `make up`
* run `make composer-install`

## API
```
curl -X POST "http://localhost:8080/v1/payment/shift4" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "amount": "12.34",
    "currency": "USD",
    "card": {
      "number": "4242424242424242",
      "expYear": "2025",
      "expMonth": "12",
      "cvv": "123"
    }
  }'
```
```
curl -X POST "http://localhost:8080/v1/payment/aci" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "amount": "12.34",
    "currency": "EUR",
    "card": {
      "number": "4200000000000000",
      "expYear": "2034",
      "expMonth": "05",
      "cvv": "123"
    }
  }'
```

## CLI command
* run `docker exec -it card_payment_service_php_fpm bash`
* run `bin/console app:payment:create shift4 12.34 USD 4242424242424242 2025 12 123`
* run `bin/console app:payment:create aci 12.34 EUR 4200000000000000 2034 05 123`

## Run tests
`make test`
