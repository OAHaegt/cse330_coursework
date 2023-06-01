# CSE330
# Module 2 Group
Jefferson Wang, 473824, OAHaegt； Dean Yu 496841 XiaobingDean.

# A Simple File Sharing Site:

- http://ec2-13-113-251-204.ap-northeast-1.compute.amazonaws.com/~edochin/module2-group-473824-496841/login.php

# Notes:
- Four default users have been already registered in the site, which the grader can directly log in to. They are: jack, alice, bob, and charles. A hello.txt and gohan.jpg have already been uploaded as alice for testing and demoing purposes.
- For the creative portion, we implemented the support for siging up as a new user, as well as the functionality to send a file from a user's directory to a receiver's directory.

---

## Grading- Module 2 Group

1.  **File Sharing Site (40 Points):**
    -   _**File Management (25 Points):**_
        -   [x] Users should not be able to see any files until they enter a username and log in (4 points)
        -   [x] Users can see a list of all files they have uploaded (4 points)
        -   [x] Users can open files they have previously uploaded (5 points)   
        -   [ ] Users can upload files (4 points)
            
            *-4 Points: Unable to upload any type of file*
            
        -   [x] Users can delete files. If a file is "deleted", it should actually be removed from the filesystem (4 points)
        -   [x] The directory structure is hidden. Users should not be able to access or view files by manipulating a URL. (2 points)
        -   [x] Users can log out (2 points)
            
    -   _**Best Practices (10 Points):**_
        -   [ ] Code is well formatted and easy to read, with proper commenting (4 points)

			_-1 Point: Lack of comments, overall inadequate documentation of code_

        -   [ ] The site follows the FIEO philosophy (3 points)
	
			_-3 Points: Does not filter input for open file functionality or for sharing file functionality. (Could have more filtering/excaping issues but stopped there)_

        -   [ ] All pages pass the W3C validator (3 points)

			_-3 Points: Errors found in W3C HTML validator testing show.php's direct input._

    -   _**Usability (5 Points):**_
        -   [ ] Site is intuitive to use and navigate (4 points)
	
			_-2 Points: Needing to manually pressing the back button for certain pages._
			
        -   [x] Site is visually appealing (1 point)
2.  **Creative Portion (15 Points)**  

	_-7.5 Points: Although user input is validated correctly for creating additional users, repeated usernames are still allowed despite the attempt to validate for them. Moreover, although the basic functionality works for sharing files with other users, both of the inputs (the file shared and the username shared with) aren't filtered. Overall, I gave half credit on both features of the Creative Portion._

### Grade: 63% (34.5/55 pts)
####  *Graded By Wyatt Kellett*
