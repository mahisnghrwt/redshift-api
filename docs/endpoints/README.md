###### [Home](../) • [Endpoints](README.md) • [Authentication and Authorization](../authentication-authorization.md) • [Script](../script.md)
---

# Endpoints

| Description                           | HTTP method  | Endpoint                                                                   | Authentication Token required? |
| ----------------------                | ------- | -----------------                                                               | --------------- |
| Submit single/multiple calculations as a General User/Admin                   | `POST`  | ```http://redshift-01.cdms.westernsydney.edu.au/redshift/api/```                | *yes*           |
| Submit a single calculation as a Guest                | `POST`  | ```http://redshift-01.cdms.westernsydney.edu.au/redshift/api/guest/```          | *no*            |
| Fetch list of available methods                       | `GET`   | ```http://redshift-01.cdms.westernsydney.edu.au/redshift/api/methods/```        | *no*            |
| Get calculation status                                | `POST`  | ```http://redshift-01.cdms.westernsydney.edu.au/redshift/api/status/```         | *yes*           |
| Get calculation result                                | `POST`  | ```http://redshift-01.cdms.westernsydney.edu.au/redshift/api/result/```         | *yes*           |
| Get system-load of the server                         | `POST`  | ```http://redshift-01.cdms.westernsydney.edu.au/redshift/api/system-load/```    | *yes*           |


### Endpoints
* [/](api.md)
* [/guest](api-guest.md)
* [/status](status.md)
* [/result](result.md)
* [/system-load](system-load.md)
* [/methods](methods.md)