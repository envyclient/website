![Publish Docker](https://github.com/envyclient/revived-website/workflows/Publish%20Docker/badge.svg?branch=master)

# envy
The official dashboard of Envy Client.

### docker
```yaml
version: '3.7'
services:
    envyclient-dashboard:
        image: haaaqs/envyclient:latest
        container_name: envyclient-dashboard
        ports:
            - "9191:9191"
        restart: always
        env_file:
            - .env
```
