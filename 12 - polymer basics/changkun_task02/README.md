## RAIL Model

Problem: RAIL Model is a user-centric model for performance, which gives a model tries to solve the problem of the performance optimization of a web application.

Solution:

It says that every web application has four distinct aspects to its life cycle, and performance fits into them in different ways:

- Response,
- Animation,
- Idle,
- Load

General Idea:

All aspects has its threshold.

## PRPL Pattern 

The PRPL pattern is stands for:

- Push critical resources for the initial route;
- Render initial route;
- Pre-cache remaining routes;
- Lazy-load and create remaining routes on demand.

## Progressive Web Application (PWA)

Progressive Web Apps are user experiences that have the reach of the web, and are:

- Reliable - Load instantly and never show the downasaur, even in uncertain network conditions.
- Fast - Respond quickly to user interactions with silky smooth animations and no janky scrolling.
- Engaging - Feel like a natural app on the device, with an immersive user experience.

## Critical Rendering Path

It is the series of events that must take place to render (display) the initial view of a webpage.
Example: get html > get resources > parse > display webpage
Optimizing these events result in significantly faster webpages.

## Accelerated Mobile Pages (AMP)

For many, reading on the mobile web is a slow, clunky and frustrating experience - but it doesn’t have to be that way. The Accelerated Mobile Pages (AMP) Project is an open source initiative that embodies the vision that publishers can create mobile optimized content once and have it load instantly everywhere.

## App Shell

An application shell (or app shell) architecture is one way to build a Progressive Web App, which is the minimal HTML, CSS, and JavaScript powering a user interface. The application shell should:

- load fast
- be cached
- dynamically display content