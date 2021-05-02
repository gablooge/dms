
- Masuk ke docker folder yang ada dalam directory project

> cd [PROJECT_DIR]/docker

- Build and Run 
> docker-compose up -d --build

- Build image 
> docker-compose build

- Run images
> docker run --tmpfs /run -v /sys/fs/cgroup:/sys/fs/cgroup:ro --name dms_app dms_app:latest  /sbin/init

- Cek container
> docker ps

- Masuk ke centos container system
> docker exec -it dms_app /bin/bash

- Stop Container
> docker container stop dms_app

- delete container
> docker container rm dms_app