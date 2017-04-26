# Gowajee Course Registration

2110432 Automatic Speech Recognition - Term Project

After pulling
=============
1. create a new database
2. if .env file does not exist, copy .env.example file and rename to .env
3. in .env file, set:
	- database name (DB_DATABASE)
	- db host & port (DB_HOST & DB_PORT), default port of MySQL is 3306
	- db username & password (DB_USERNAME & DB_PASSWORD)
4. initialize database
```
php artisan migrate
php artisan db:seed
```

Starting the server
===================
run
```
php artisan serve
```
the web page will be available at http://localhost:8000
use this user to test the site
```
usr: user@user.com
pwd: user
```

Data Types
==========

Course
------
```javascript
{
	id: number,
	name: string,
}
```

Section
-------
```javascript
{
    id: number,
    lecturer: string,
    enrolled: number,
    capacity: number,
    isEnrolled: boolean
}
```



Pages
=====

- GET 	/login 	=> login page
- POST  /login  => login()
- GET   / 		=> index page



API Endpoints
=============

Speech Recognition
------------------

- POST  /api/recognize/function
	- description: 	translate a wav to a function & param
	- params:
		- FILE:	wav		// via http file upload
	- return: the function interpreted from the speech file
	- return format: JSON
	```javascript
	{
		functionName: String,
		params: {
			... // like the input of the corresponding function
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
		result: 'confirm'/'cancel'/'unknown'
	}
	```

Core Functions
--------------

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
		course: number,		// course id
		section: number		// section id
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
		course: number		// course id
	}
	```
	- return: 		the withdrawal results & info about the withdrawn course & section
	- return format: JSON
	```javascript
	{
		success: boolean
	}
	```
