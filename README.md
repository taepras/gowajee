# Gowajee Course Registration


2110432 Automatic Speech Recognition - Term Project 2016/2
"ASR for ASR using ASR"



System Requirements
===================

1. PHP version >= 5.6.4
2. MySQL Database
(for 1. and 2., Check [laravel 5.4](https://laravel.com/docs/5.4) documentation for more info.)
3. Docker
4. Python 3



Setting Up
==========

Application server
------------------

1. initialize project by running
```
composer install
```
2. create a new database with a desired name
3. if .env file does not exist, copy .env.example file and rename to .env
4. in .env file, set:
	- database name (DB_DATABASE)
	- db host & port (DB_HOST & DB_PORT), default port of MySQL is 3306
	- db username & password (DB_USERNAME & DB_PASSWORD)
5. create key for the app
```
php artisan key:generate
```
6. initialize database
```
php artisan migrate
php artisan db:seed
```

Speech Recognition server
-------------------------
1. install the required 'ws4py' python module
```
pip install ws4py
```
2. pull the recognition server's docker container
```
docker pull jcsilva/docker-kaldi-gstreamer-server
```
3. start the docker container and mount the 'models' folder to it with the full path of the models folder of this repository.
```
docker run --name [the desired container name] -it -p 8080:80 -v [path for models folder]:/opt/models jcsilva/docker-kaldi-gstreamer-server:latest /bin/bash
```
4. inside the docker container, run:
```
/opt/start.sh -y /opt/models/sample_nnet2.yaml
```
5. test the recognition server from command prompt or terminal (run this command with the working directory at the project root)
```
python public/python/client.py -u ws://localhost:8080/client/ws/speech -r 32000 models/samples/000.wav
```



Starting the server
===================

1. start the application server by
```
php artisan serve
```
2. start the application server by
```
docker start -i [the name set for the docker container]
```
3. the web page will be available at http://localhost:8000
use this user to test the site
```
usr: user@user.com
pwd: user
```



The API
=======

Data Types
----------

### Course

```javascript
{
	id: String,
	name: String,
	is_enrolled: boolean
}
```

### Section

```javascript
{
    id: String,
    lecturer: String,
    enrolled: number,
    capacity: number,
    is_enrolled: boolean
}
```


Pages
-----

- GET 	/login 	=> login page
- POST  /login  => login()
- GET   / 		=> index page


API Endpoints
-------------

### Speech Recognition

- POST  /api/recognize/function
	- description: 	translate a wav to a function & param
	- params:
		- FILE:	wav		// via http file upload
	- return: the function interpreted from the speech file
	- return format: JSON
	```javascript
	{
		sentence: String,
		functionName: String,
		params: {
			... // like that the input of the corresponding function
		},
		needsConfirm: boolean
	}
	```

- POST	/api/recognize/confirm
	- description:  recognize a confirm/cancel command
	- params:
		- FILE:	wav		// via http file upload
	- return: recognition result (confirm/cancel) from the speech file
	- return format: JSON
	```javascript
	{
		sentence: String,
		result: 'confirm'/'cancel'/'unknown'
	}
	```

### Core Functions

- GET 	/courses/all
	- name:			get_all_courses
	- description:	get info about all courses in the system
	- params: 		none
	- return: 		a detailed list of all available courses & sections.
	- return format: JSON
	```javascript
	[
		{
			Course,
			sections: [ Section ]
		}
	]
    ```

- GET 	/courses
	- name:			get_enrolled_courses
	- description:	get info about all courses the user have enrolled
	- params: 		none
	- return: 		a detailed list of enrolled courses & sections.
	- return format: JSON
	```javascript
	[
		{
			Section,
			course: [ Course ]
		}
	]
    ```

- GET 	/courses/{id}
	- name:			get_course_by_id
	- description:	get info about a specific course
	- params: 		id of the course
	- return: 		the info about the course and its sections.
	- return format: JSON
	```javascript
	{
		Course,
		sections: [ Section ]
	}
    ```

- GET   /api/courses/time/{day}/{time}
	- name:			get_courses_by_time
	- description:	get info about courses offered on a specific time slot
	- params:
    	- day: 		'mon'/'tue'/'wed'/'thu'/'fri'/'sat'/'sun'
   		- time:		'morning'/'afternoon'
	- return: 		a list of courses & sections available on the given time slot
	- return format: JSON
	```javascript
	[
		{
			Course,
			sections: [ Section ]
		}
	]
	```

- POST   /api/courses
	- name:			register_course
	- description:	register a specific course & section
	- params: 		JSON
	```javascript
	{
		course: String,		// course id
		section: String		// section number
	}
	```
	- return: 		the registration results & info about the registered course & section
	- return format: JSON
	```javascript
	{
		success: boolean
	}
	```

- DELETE /api/courses
	- name:			withdraw_course
	- description:	withdraw a specific course
	- params: 		JSON
	```javascript
	{
		course: String		// course id
	}
	```
	- return: 		the withdrawal results & info about the withdrawn course & section
	- return format: JSON
	```javascript
	{
		success: boolean
	}
	```
