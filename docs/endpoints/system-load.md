###### [Home](../) • [Endpoints](README.md) • [Authentication and Authorization](../authentication-authorization.md) • [Script](../script.md)
---

# /system-load `POST` `Content-Type: application/json`
Returns the system-load metrics of the server. *Server should be Linux, otherwise this endpoint wouldn't work.* In response, `system-load` attribute is array containing three floats, which represents the system-load in last 1, 5 and 15 minutes respectively.

### Authentication Token required?
**Yes**

### Authorization
* Admin

### Sample JSON Request
```
{
    "token": "k4edH5LHuJqDCe2ZRlbCjkhkhkjuu9BX9NdAN0p\/b51OnUG2kQfxi"
}
```

### Sample JSON Response
```
{
    "system-load": [0.1, 0.54, 0.90],
    "errors": []
}
```
### Attributes' Details
| Attribute | Can be Null? | Datatype |
|-----------|--------------|----------|
| `token` | *no* | `string` |
