# How to run  
Run `./start.sh`  
Enter docker container `docker exec -it docker_php-app_1 bash`  
Run Tour Pre Processor Script `php /app/tour-pre-processor.php`  
Run Tour Processor Script `php /app/tour-processor.php`  

## Q&A
### I. What problems do you identify in the current setup? Please enumerate them with a brief description of why you believe they are problems, and what risks they carry.  
1- If services like Tour files or Log are unavailable the new data will be lost;  
2- If the importer waits an answer from Tour Files or Log and these services are slow or overloaded you will have the importer stuck until the services are back or running ok;  
3- Anything new you desire to do after de Importer finish, you probably will have to implement something on the Tour Importer script, bringing unnecessary risk, besides as more things you need, bigger and probably slower the script will be.  

### II. What new architecture would you suggest that optimizes for performance, scalability, and reliability?  
![New architecture suggestion](https://i.imgur.com/8IGbGu6.png)  

### III. How would you ensure your chosen architecture is working as expected?  
- Integration Testing  
- Observability (eg. New Relic)  

## Explanation of the new architecture
### Tour Importer
- Responsible to get the data from operators API and insert into 2 topics (log and tour_file). 
### Log "consumer"
- Read topic 'logs' from Event Streaming;
- Microservice responsible to deal with the data and save on the log service.
### Tour Files (tour-pre-processor)
- Read topic 'tour-files' from Event Streaming;
- Transform the information took from Tour Importer on Tour Radar JSON format;
- Send data to QUEUE service.
### Tour Processor (tour-processor)
- Read from QUEUE service;
- Deal with assets (images, files, etc...);
- Save all data in the website database.

## Extra
RabbitMQ is running under [cloudamqp.com]()