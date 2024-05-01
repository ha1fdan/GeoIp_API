# IP Lookup API using Nginx, PHP-FPM, and GeoIP Update

This repository sets up a Docker-based environment for an IP Lookup API with Nginx, PHP-FPM, and GeoIP Update for automatic GeoIP database updates from MaxMind.

## Prerequisites

Before you start, make sure you have Docker and Docker Compose installed on your system.

## Getting Started

### Setup GeoIP Update

To update GeoIP databases automatically, you need a license key from MaxMind. Follow these steps:

1. Visit MaxMind's website and create an account.
2. Follow the instructions to obtain a license key: https://dev.maxmind.com/geoip/updating-databases#2-obtain-geoipconf-with-account-information
3. Create a `.env` file in the root directory with your credentials:

```conf
GEOIPUPDATE_ACCOUNT_ID=your_account_id
GEOIPUPDATE_LICENSE_KEY=your_license_key
GEOIPUPDATE_EDITION_IDS=GeoLite2-ASN GeoLite2-City GeoLite2-Country
GEOIPUPDATE_FREQUENCY=72 # in hours
```

Replace `your_account_id` and `your_license_key` with your actual account details.

### Running the Docker Compose

Launch the services using Docker Compose:

docker-compose up -d

This command starts Nginx, PHP-FPM, and GeoIP Update container.

### Accessing the API

Access your API by navigating to:

http://localhost:8080

### Stopping the Service

To stop all services, use:

docker-compose down


## Contributing

Contributions are welcome. Please open an issue first to discuss what you would like to change.

Make sure to update tests as appropriate.