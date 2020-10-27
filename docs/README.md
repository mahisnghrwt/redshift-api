# Guide - Redshift API

### How API works?
The API provides various endpoints to make estimating redshift simple and easy. A user can perform calculations as a Guest or a registered user. A JSON request containing all the necessary measurements must be sent either to `http://redshift-01.cdms.westernsydney.edu.au/redshift/api` for a registered user or `http://redshift-01.cdms.westernsydney.edu.au/redshift/api/guest` for a guest. The request is converted into batches and added to a work queue, on the other end of the queue, a Process retrieves the measurements and passes them to requested Python script. Upon completion of the calculation result is parsed back by the Worker process and stored into the database. A registered user can later use `http://redshift-01.cdms.westernsydney.edu.au/redshift/api/result` endpoint to fetch the result using the ‘calculation_id’ received earlier when request was submitted. Furthermore, `http://redshift-01.cdms.westernsydney.edu.au/redshift/api/status` endpoint can be used to fetch the status of the calculation.


### Navigation Bar
[Home](/) -- [Endpoints](/endpoints/README.md) -- [Authentication and Authorization](/authentication-authorization.md) -- [Script](/script.md)

### Navigation
* [Home](/)
* [Endpoints](/endpoints/README.md)
* [Authentication and Authorization](/authentication-authorization.md)
* [Script](/script.md)