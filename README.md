# Gowajee Course Registration

Data Types
==========

Course
------
{
	id: number,
	name: string,
    sections: [ Section ]
}

Section
-------
{
    id: number,
    teacher: string,
    enrolledCount: number,
    capacity: number,
    isEnrolled: boolean
}



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
	- return format:
	{
		functionName: String,
		params: {
			// like the input of the corresponding function
		},
		needsConfirm: boolean
	}

- POST	/api/recognize/confirm
	- description:  recognize a confirm/cancel command
	- params:
		- FILE:	wav		// via http file upload
	- return: recognition result (confirm/cancel) from the speech file
	- return format:
	{
		result: 'confirm'/'cancel'/'unknown'
	}

Core Functions
--------------

- GET 	/courses/all
	- name:			get_all_courses
	- description:	get info about all courses in the system
	- params: 		none
	- return: 		a detailed list of all available courses & sections.
	- return format:
	{
    	courses: [ Course ]
    }

- GET 	/courses
	- name:			get_courses_enrolled
	- description:	get info about all courses the user have enrolled
	- params: 		none
	- return: 		a detailed list of enrolled courses & sections.
	- return format:
	{
    	courses: [ Course ]
    }

- GET 	/courses/{id}
	- name:			get_course_by_id
	- description:	get info about a specific course
	- params: 		id of the course
	- return: 		the info about the course and its sections.
	- return format:
	{
    	course: Course
    }
    
- GET   /api/courses/time/{day}/{time}
	- name:			get_courses_by_time
	- description:	get info about courses offered on a specific time slot
	- params:
    	- day: 		'mon'/'tue'/'wed'/'thu'/'fri'/'sat'/'sun'
   		- time:		'morning'/'afternoon'
	- return: 		a list of courses & sections available on the given time slot
	- return format:
	{
		courses: [ Course ]
	}

- POST   /api/courses
	- name:			register_course
	- description:	register a specific course & section
	- params: 		JSON
	{
		course: number,		// course id
		section: number		// section id
	}
	- return: 		the registration results & info about the registered course & section
	- return format:
	{
		success: boolean
		course: Course
	}

- DELETE /api/courses
	- name:			withdraw_course
	- description:	withdraw a specific course
	- params: 		JSON
	{
		course: number		// course id
	}
	- return: 		the withdrawal results & info about the withdrawn course & section
	- return format:
	{
		success: boolean
		course: Course
	}
