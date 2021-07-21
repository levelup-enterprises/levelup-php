# Getting started

1. Install vendor libraries ( _composer install_ )

2. Use the _src/config/index_ file to set up your site information

3. Add your accessible pages to the templates directory

   - Router only allows page request to pages in templates directory
   - Source folder can be changed in config file

4. Add pages/views to the templates directory
   - Add a new directory for every page.
     - _Must include an index.php file_

# Structure

## ROOT

Basic project setup files are stored here.

- index.php

  - Initializes project routes, sessions, authentication and page views.

## Public

Store all public accessible content here.

- SCSS

  - All scss styles are compiled on load while in a local environment and saved to the same named css file.

- CSS

  - All styles are loaded through core.css
    - Feel free to use any other method.

- Images
- JS
  - Include any custom js here

## SRC

All backend content is stored here.

- Config

  - Project setup
  - Configure site info
  - Store all sensitive data
  - Includes database connection file

- Controllers
  Route controllers are stored here

  - Route (project router)
  - Link (bridges all components of the project)

- Models
  Custom modules are stored here

  - Export

  Http

  - Request
  - Response
  - Session

  Utilities

  - Browser
  - DB
  - Utility

- Root

  Default script location for http request and middleware

  - **Modify default location in src/config file**

## Templates

All view content is stored here.

\*All valid routes are established in this folder (EXCEPT for your components directory).

- Components
  Main site components are stored here.
  EX: footer, header...

  - Common
    - Place reusable components here.

- Pages

  - All page specific content is rendered here.
  - The directory name is the page name

    - _Requires an index page to render correctly_

  - Nest additional directories to build out more complex projects.

  ### Basic Router Use:

      This structure:

        templates/
          home/
            index.php (accessible page)
            view.php (page content)
          admin/
            index.php (accessible page)
            view.php (page content)
            users/
              index.php (accessible page)
              view.php (page content)
          404/
            index.php (accessible page)

      Allows these links:

      site.com/
      site.com/home
      site.com/admin
      site.com/admin/users
      site.com/404

## Vendor

All vendor required files are stored here.

### Vendor Libraries

- AWS (email smtp and texting)
- phpmailer (emails)
- thingengineer (db library shortcode)

# Special Functions

## Authentication

- A random 20 bit token is generated on each new session.
- The token is stored as the session variable _auth_token_.
- All incoming request headers are compared with the token.

## AJAX Calls

- All ajax calls are only allowed access to the SRC root directory
  - Adjust the const **SCRIPTS** in the config file to change location.
  - All other files are off limit.
- All ajax calls are ran through header authentication automatically.

## Happy building!
