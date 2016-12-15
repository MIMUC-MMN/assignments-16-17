# Assignment 08 - NodeJS Authentication #

## Skeleton ##
The skeleton is based on an express-generated app. What we added:
- config/database.js: Add your database configuration in here. Make sure the database you enter there already exists before you try to run the app.

- public/index.html, public/register.html, public/stylesheets/style.css: those files are used to display a registration / login page. 
- routes/auth: Is a full-blown, working example of an authentication module. See tutorial 07 for details.
- secret/index.html: Is a demo page. It's accessible from http://localhost:3000/secret when the app is running, and only when the user is logged in. The auth module provides a middleware function `ensureAuthentication` that we used in `app.js` to prevent non-logged in users from seeing the "secret garden".
- shoot/index.html: should be completed to work together with the back end. It should asynchronously tell the server the URL of the website screenshot. 
- shoot: this directory is also used by the shoot module to serve static content.
- util: contains an abstraction layer to handle the creation and retrieval of users.
- app.js mounts the auth module and needs some more functionality to also include the `shoot` module. You have to add this (there are lines marked by a `TODO`).
- package.json: contains all the dependencies that we need to solve this task. Especially passport, mysql, webshot, and bcrypt. 


## Pull Requests:
It's probably best if you somehow prefix your folder names with your initials / name / alias

Like so: `darkwingDuck_task01`, `darkwingDuck_task02`... you get the idea 