###### [Home](README.md) • [Endpoints](/endpoints/README.md) • [Authentication and Authorization](/authentication-authorization.md) • [Script](/script.md)
---

# Authentication and Authorization
### Levels
* Guest - Can perform only single calculation per request, result returned directly as a response.
* Registered user
    * General User - Can perform multiple calculations per request, results are stored into the database, and can retrieved anytime using the `/result` endpoint.
    * Admin - Enjoys same rights as General, additionally can fetch status and result for any calculation.

