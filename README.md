![Publish Docker](https://github.com/envyclient/revived-website/workflows/Publish%20Docker/badge.svg?branch=master)

# envy
The official website of the new Envy Client.

### docker
```yaml
version: '3.7'
services:
    envyclient-panel:
        image: haaaqs/envyclient:latest
        container_name: envyclient-panel
        ports:
            - "9191:9191"
        volumes:
            - /opt/envyclient/storage:/app/storage
        restart: always
        env_file:
            - .env
```
