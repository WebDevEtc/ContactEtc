* Not maintained any longer. This is a few years old now.

# WebDevEtc ContactEtc - A full contact form for your Laravel Project, including everything you need to install and get a contact form up and working almost instantly

## Fully customisable - lots of options to suit your needs.

- Set who to email the completed contact form response to (i.e. your email address)
- Customise the fields on your contact page(s), so you can have any number of fields. Fields are completely customisable
- Includes anti spam with recaptcha
- Supports multiple contact forms on one laravel app. Just copy/paste some route lines (and change them slightly) and it will be working straight away (see docs for details)
- includes view files. It uses `@extends("layouts.app")` so it *should* work with most laravel installations (if not, its a simple edit to do). When you follow the installation guide it will copy all view files over to `/vendor/webdevetc/contactetc` anyway so you can easily edit as required.
- includes tests.

Although building a contact form is very simple, it is a bit of a waste of time (and many Laravel web apps tend to have a contact form) - hopefully this can just save a bit of time. And also, I hope it is customisable enough to be of use! I'm not a fan of packages that don't let you easily modify how they work.

## Installation Guide

Please visit [the laravel contact form documentation here](https://webdevetc.com/contactetc/). The installation process only takes a couple of minutes, but there are a few things to be aware of.

## release history/change log

- v1.0.7 - small changes, typos, etc
- v1.0 - initial release (been using it on internal projects for a while, so it has been tested in the real world quite a bit)
