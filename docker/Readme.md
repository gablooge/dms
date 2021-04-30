
- Masuk ke docker folder yang ada dalam directory project

> cd [PROJECT_DIR]/docker

- Build image dan jalankan container pada background
> docker-compose up -d --build

- Cek container
> docker ps

- Masuk ke centos container system
> docker exec -it dms_app /bin/bash