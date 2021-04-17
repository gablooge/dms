## SOLR 8 

### Installation

- Go to the solr directory
    > cd [PROJECT_DIR]/solr

- Run script installation
    > sudo chmod +x install.sh && ./install.sh

- Create another core
    > sudo docker exec -it dms-solr solr create_core -c pajak-online

- Delete core
    > sudo docker exec -it dms-solr solr delete -c pajak-online -p 8984

### Accessing solr with default installer script

- Check Docker Container List
    > sudo docker ps --filter name=dms-solr

- Check Port
    > sudo docker port dms-solr

- Inspect Container
    > sudo docker container inspect dms-solr