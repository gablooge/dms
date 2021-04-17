#!/bin/bash

if [ -x "$(command -v docker)" ]; then
    echo "Create solr installation directory..."
    # command
    sudo mkdir -p /opt/solr/docker/data
    sudo chmod -R 777 /opt/solr/docker
    
cat > /opt/solr/docker/docker-compose.yml <<EOF
version: '3'
services:
  solr:
    container_name: dms-solr
    image: solr:8
    ports:
      - "8984:8983"
    volumes:
      - /opt/solr/docker/data:/var/solr
    command:
      - solr-precreate
      - dms
EOF

    cd /opt/solr/docker/
    sudo docker-compose down && sudo docker-compose up -d
else
    echo "Please Install docker first!"
    # command
fi