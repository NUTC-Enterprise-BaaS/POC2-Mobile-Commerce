## Application Views
Application views are larger sections compared to widgets which is pretty identical to both modules and components. It is not necessary to have a view for your application but if your application requires a view, this is the place to be.

Example use of views:

* Displays a list of textbook that a user borrowed in their profile.
* Displays a list of reviews on the person's profile.
* Create a new blog post from a user's dashboard.
* Create a new recipe from the dashboard.

As of the day of writing this documentation, there are currently 2 views available:

* Dashboard view - This will allow your application to appear on the user's dashboard.
* Profile view - This will allow your application to appear on the user's profile.

Note: It is not compulsory to have both views. Consider that if you are writing an app that links to a bank, you don't want to show the user's wealth in their profile.


### View Types
Application views are divided into two types of views:

Difficulty: Easy

`embed` - Upon clicking on the application, the system will perform an ajax call to retrieve your app contents. This does not refreshes the page.

Difficulty: Advanced

`canvas` - Upon clicking on the application, the user will be redirected to a blank canvas with just the toolbar from EasySocial. This view is useful when you need to apply more advanced logics.
