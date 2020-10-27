###### [Home](README.md) • [Endpoints](/endpoints/README.md) • [Authentication and Authorization](/authentication-authorization.md) • [Script](/script.md)
---
# Guide - Redshift API

### How API works?
The API provides various endpoints to make estimating redshift simple and easy. A user can perform calculations as a Guest or a registered user. A JSON request containing all the necessary measurements must be sent either to `http://redshift-01.cdms.westernsydney.edu.au/redshift/api` endpoint for a registered user or `http://redshift-01.cdms.westernsydney.edu.au/redshift/api/guest` endpoint for a guest. 

Upon receival of new user request, it is then converted into batches and added to a work queue one-by-one, on the other end of the queue, a Process retrieves the measurements and passes them to requested Python script. When calculation is completed the result is parsed back by the Worker process and stored into the database.

A registered user can later use `http://redshift-01.cdms.westernsydney.edu.au/redshift/api/result` endpoint to fetch the result using the ‘calculation_id’ received earlier when request was submitted. Furthermore, `http://redshift-01.cdms.westernsydney.edu.au/redshift/api/status` endpoint can be used to fetch the status of the calculation.